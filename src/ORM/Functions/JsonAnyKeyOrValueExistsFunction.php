<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Functions;

use Nextras\Dbal\QueryBuilder\QueryBuilder;
use Nextras\Orm\Collection\Functions\IQueryBuilderFunction;
use Nextras\Orm\Collection\Helpers\DbalExpressionResult;
use Nextras\Orm\Collection\Helpers\DbalQueryBuilderHelper;
use function array_key_last;
use function assert;
use function count;
use function is_array;

final class JsonAnyKeyOrValueExistsFunction implements IQueryBuilderFunction
{

	/**
	 * @param array<mixed> $args
	 */
	public function processQueryBuilderExpression(
		DbalQueryBuilderHelper $helper,
		QueryBuilder $builder,
		array $args
	): DbalExpressionResult
	{
		assert(count($args) === 2);

		$expression = $helper->processPropertyExpr($builder, $args[0]);

		$value = $args[1];

		assert(is_array($value));

		$inlineValue = '';
		$last = array_key_last($value);
		foreach ($value as $key => $item) {
			$inlineValue .= "'{$item}'";
			if ($key !== $last) {
				$inlineValue .= ', ';
			}
		}

		$inlineValue = "array[{$inlineValue}]";

		return $expression->append('?| %raw', $inlineValue);
	}

}
