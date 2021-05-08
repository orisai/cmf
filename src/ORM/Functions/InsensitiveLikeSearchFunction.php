<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Functions;

use Nette\Utils\Strings;
use Nextras\Orm\Collection\Functions\BaseCompareFunction;
use Nextras\Orm\Collection\Helpers\DbalExpressionResult;
use Orisai\Exceptions\Logic\InvalidArgument;
use function array_key_last;
use function count;
use function get_debug_type;
use function is_array;
use function is_string;
use function mb_strtolower;
use function reset;
use function strtr;

final class InsensitiveLikeSearchFunction extends BaseCompareFunction
{

	protected function evaluateInPhp(mixed $sourceValue, mixed $targetValue): bool
	{
		$sourceValueNorm = $this->normalize((string) $sourceValue);

		if (is_array($targetValue)) {
			foreach ($targetValue as $item) {
				if ($this->normalize((string) $item) === $sourceValueNorm) {
					return true;
				}
			}

			return false;
		}

		return $sourceValueNorm
			=== $this->normalize($targetValue);
	}

	private function normalize(string $string): string
	{
		return mb_strtolower(Strings::toAscii($string));
	}

	protected function evaluateInDb(DbalExpressionResult $expression, mixed $value): DbalExpressionResult
	{
		if (is_array($value)) {
			if (count($value) === 0) {
				return new DbalExpressionResult(['1=0'], $expression->isHavingClause);
			}

			if (count($value) === 1) {
				$value = reset($value);
			} else {
				$valueLastKey = array_key_last($value);
				$valueInline = 'array[';
				foreach ($value as $key => $item) {
					$itemLike = $this->convertLikeToSql($item, 0);
					$valueInline .= "UNACCENT({$itemLike}) " . 'COLLATE "ori"."strict"';

					if ($key !== $valueLastKey) {
						$valueInline .= ', ';
					}
				}

				$valueInline .= ']';

				return new DbalExpressionResult(['UNACCENT(%ex) ILIKE ANY (%raw)', $expression->args, $valueInline]);
			}
		}

		if ($value === null) {
			return $expression->append('IS NULL');
		}

		if (is_string($value)) {
			return new DbalExpressionResult(
				['UNACCENT(%ex) ILIKE UNACCENT(%_like_) COLLATE "ori"."strict"', $expression->args, $value],
			);
		}

		$debugType = get_debug_type($value);

		throw InvalidArgument::create()
			->withMessage("Value of type {$debugType} is not supported.");
	}

	private function convertLikeToSql(string $value, int $mode): string
	{
		$value = strtr($value, [
			"'" => "''",
			'\\' => '\\\\',
			'%' => '\\%',
			'_' => '\\_',
		]);

		return ($mode <= 0 ? "'%" : "'") . $value . ($mode >= 0 ? "%'" : "'");
	}

}
