<?php declare(strict_types = 1);

namespace Tests\OriCMF\Core\Unit\ORM\Filter;

use Nextras\Orm\Collection\Expression\LikeExpression;
use Nextras\Orm\Collection\Functions\CompareEqualsFunction;
use Nextras\Orm\Collection\Functions\CompareGreaterThanFunction;
use Nextras\Orm\Collection\Functions\CountAggregateFunction;
use Nextras\Orm\Collection\Functions\SumAggregateFunction;
use Nextras\Orm\Collection\ICollection;
use OriCMF\Core\ORM\Filter\FindFilter;
use PHPUnit\Framework\TestCase;

final class FindFilterTest extends TestCase
{

	public function testNoConditions(): void
	{
		$find = new FindFilter();

		self::assertSame([], $find->getConditions());
	}

	public function testOperators(): void
	{
		$find = new FindFilter();

		$find->equal('equal', 'v');
		$find->notEqual('notEqual', 'v');
		$find->greater('greater', 1);
		$find->greaterOrEqual('greaterOrEqual', 1);
		$find->lower('lower', 1);
		$find->lowerOrEqual('lowerOrEqual', 1);
		$find->like('like', $like = LikeExpression::startsWith('l'));
		$find->operator('??', 'operator', 'v');

		self::assertSame(
			[
				ICollection::AND,
				['equal' => 'v'],
				['notEqual!=' => 'v'],
				['greater>' => 1],
				['greaterOrEqual>=' => 1],
				['lower<' => 1],
				['lowerOrEqual<=' => 1],
				['like~' => $like],
				['operator??' => 'v'],
			],
			$find->getConditions(),
		);
	}

	public function testFunctions(): void
	{
		$find = new FindFilter();

		$f1 = $find->createFunction(CountAggregateFunction::class, 'property');
		$f2 = $find->createFunction(CompareEqualsFunction::class, 'property', 'val1', 'val2', 'val3');

		self::assertSame(
			[
				CountAggregateFunction::class,
				'property',
			],
			$f1,
		);

		self::assertSame(
			[
				CompareEqualsFunction::class,
				'property',
				'val1',
				'val2',
				'val3',
			],
			$f2,
		);

		$find->function(CompareEqualsFunction::class, 'property');
		$find->function(CompareGreaterThanFunction::class, $f1);
		$find->function(SumAggregateFunction::class, 'property', 'val1', 'val2');

		self::assertSame(
			[
				ICollection::AND,
				[
					CompareEqualsFunction::class,
					'property',
				],
				[
					CompareGreaterThanFunction::class,
					[
						CountAggregateFunction::class,
						'property',
					],
				],
				[
					SumAggregateFunction::class,
					'property',
					'val1',
					'val2',
				],
			],
			$find->getConditions(),
		);
	}

	public function testRaw(): void
	{
		$find = new FindFilter();

		$find->raw(['raw', 'array']);

		self::assertSame(
			[
				ICollection::AND,
				['raw', 'array'],
			],
			$find->getConditions(),
		);
	}

	public function testOr(): void
	{
		$find = new FindFilter();

		$find->or(static function (FindFilter $scope): void {
			$scope->equal('property', '2b');
			$scope->notEqual('property', '2b');
		});

		self::assertSame(
			[
				ICollection::OR,
				['property' => '2b'],
				['property!=' => '2b'],
			],
			$find->getConditions(),
		);
	}

	public function testAnd(): void
	{
		$find = new FindFilter();

		$find->and(static function (FindFilter $scope): void {
			$scope->greaterOrEqual('property', 10);
			$scope->lowerOrEqual('property', 20);
		});

		self::assertSame(
			[
				ICollection::AND,
				['property>=' => 10],
				['property<=' => 20],
			],
			$find->getConditions(),
		);
	}

	public function testLogicalOperators(): void
	{
		$find = new FindFilter();

		$find->and(static function (FindFilter $scope): void {
			$scope->greaterOrEqual('property1', 10);
			$scope->lowerOrEqual('property1', 20);
		});

		$find->or(static function (FindFilter $scope): void {
			$scope->equal('property2', '2b');
			$scope->notEqual('property2', '2b');
		});

		$find->and(static function (FindFilter $scope): void {
			// Empty, does not render
		});

		$find->or(static function (FindFilter $scope): void {
			// Empty, does not render
		});

		self::assertSame(
			[
				ICollection::AND,
				[
					ICollection::AND,
					['property1>=' => 10],
					['property1<=' => 20],
				],
				[
					ICollection::OR,
					['property2' => '2b'],
					['property2!=' => '2b'],
				],
			],
			$find->getConditions(),
		);
	}

	public function testIsEmpty(): void
	{
		$find = new FindFilter();

		self::assertTrue($find->isEmpty());

		$find->equal('foo', 'bar');
		self::assertFalse($find->isEmpty());
	}

}
