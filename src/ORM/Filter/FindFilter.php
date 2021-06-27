<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Filter;

use Closure;
use Nextras\Orm\Collection\Expression\LikeExpression;
use Nextras\Orm\Collection\Functions\IArrayFunction;
use Nextras\Orm\Collection\Functions\IQueryBuilderFunction;
use Nextras\Orm\Collection\ICollection;
use OriCMF\Core\ORM\Functions\InsensitiveLikeSearchFunction;
use function array_merge;
use function array_unshift;
use function count;

class FindFilter
{

	/** @var array<mixed> */
	private array $conditions = [];

	public function __construct(private string $logicalOperator = ICollection::AND)
	{
	}

	public function equal(string $property, mixed $value): void
	{
		$this->operator('', $property, $value);
	}

	public function notEqual(string $property, mixed $value): void
	{
		$this->operator('!=', $property, $value);
	}

	public function greater(string $property, mixed $number): void
	{
		$this->operator('>', $property, $number);
	}

	public function greaterOrEqual(string $property, mixed $number): void
	{
		$this->operator('>=', $property, $number);
	}

	public function lower(string $property, mixed $number): void
	{
		$this->operator('<', $property, $number);
	}

	public function lowerOrEqual(string $property, mixed $number): void
	{
		$this->operator('<=', $property, $number);
	}

	public function like(string $property, LikeExpression $expression): void
	{
		$this->operator('~', $property, $expression);
	}

	public function operator(string $operator, string $property, mixed $value): void
	{
		$this->raw([
			"{$property}{$operator}" => $value,
		]);
	}

	/**
	 * @param class-string<IArrayFunction|IQueryBuilderFunction> $function
	 * @param string|array<mixed>                                $expression
	 */
	public function function(string $function, string|array $expression, mixed ...$values): void
	{
		$this->raw($this->createFunction($function, $expression, ...$values));
	}

	/**
	 * @param class-string<IArrayFunction|IQueryBuilderFunction> $function
	 * @param string|array<mixed>                                $expression
	 * @return array<mixed>
	 */
	public function createFunction(string $function, string|array $expression, mixed ...$values): array
	{
		return array_merge([$function, $expression], $values);
	}

	public function search(string $property, mixed $value): void
	{
		$this->function(InsensitiveLikeSearchFunction::class, $property, $value);
	}

	/**
	 * @param array<mixed> $condition
	 */
	public function raw(array $condition): void
	{
		$this->conditions[] = $condition;
	}

	/**
	 * @param Closure(FindFilter): void $conditions
	 */
	public function and(Closure $conditions): void
	{
		$this->logicalOperator($conditions, ICollection::AND);
	}

	/**
	 * @param Closure(FindFilter): void $conditions
	 */
	public function or(Closure $conditions): void
	{
		$this->logicalOperator($conditions, ICollection::OR);
	}

	/**
	 * @param Closure(FindFilter): void $conditions
	 */
	private function logicalOperator(Closure $conditions, string $operator): void
	{
		$find = new FindFilter($operator);

		$conditions($find);

		$raw = $find->getConditions();

		if ($raw === []) {
			return;
		}

		$this->raw($raw);
	}

	/**
	 * @return array<mixed>
	 */
	public function getConditions(): array
	{
		// No conditions, empty result
		$count = count($this->conditions);
		if ($count === 0) {
			return [];
		}

		// Only condition is inner logical operator, optimize it
		if ($count === 1) {
			$key = $this->conditions[0][0] ?? null;
			if ($key === ICollection::AND || $key === ICollection::OR) {
				return $this->conditions[0];
			}
		}

		$conditions = $this->conditions;
		array_unshift($conditions, $this->logicalOperator);

		return $conditions;
	}

	public function isEmpty(): bool
	{
		return $this->conditions === [];
	}

}
