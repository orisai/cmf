<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Functions;

use Nextras\Orm\Collection\Functions\BaseCompareFunction;
use Nextras\Orm\Collection\Helpers\DbalExpressionResult;
use function assert;
use function is_string;

/**
 * Temporary solution for 1:m and m:m checks that NONE of the values on other side match.
 * Current implementation of not equal in orm checks that ANY value (at least one) does not match.
 */
final class ToManyNotEqualFunction extends BaseCompareFunction
{

	protected function evaluateInDb(DbalExpressionResult $expression, mixed $value): DbalExpressionResult
	{
		assert(is_string($value));
		//TODO - escaping, replace %raw with array modifier
		$valueInline = "ARRAY ['$value']";

		return new DbalExpressionResult(['NOT (array_agg(%ex) @> %raw)', $expression->args, $valueInline], true);
	}

	protected function evaluateInPhp(mixed $sourceValue, mixed $targetValue): bool
	{
		// TODO: Implement evaluateInPhp() method.
		return false;
	}

}
