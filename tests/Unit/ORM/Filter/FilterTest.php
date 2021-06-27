<?php declare(strict_types = 1);

namespace Tests\OriCMF\Core\Unit\ORM\Filter;

use OriCMF\Core\ORM\Filter\Filter;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{

	public function testDefault(): void
	{
		$filter = new Filter();

		self::assertSame([], $filter->find()->getConditions());
		self::assertSame([], $filter->order()->getOrder());
		self::assertSame([null, null], $filter->getLimit());
	}

	public function testLimit(): void
	{
		$filter = new Filter();

		self::assertSame([null, null], $filter->getLimit());

		$filter->limit(10, 5);
		self::assertSame([10, 5], $filter->getLimit());

		$filter->limit(10);
		self::assertSame([10, null], $filter->getLimit());
	}

	public function testIsEmpty(): void
	{
		$filter = new Filter();

		self::assertTrue($filter->isLimitEmpty());
		self::assertTrue($filter->isEmpty());

		$filter->limit(1);
		self::assertFalse($filter->isLimitEmpty());
		self::assertFalse($filter->isEmpty());

		$filter = new Filter();
		$filter->find()->equal('foo', 'bar');
		self::assertFalse($filter->isEmpty());

		$filter = new Filter();
		$filter->order()->property('foo');
		self::assertFalse($filter->isEmpty());
	}

}
