<?php declare(strict_types = 1);

namespace OriCMF\Core\SQL\Modifier;

use Nextras\Dbal\Connection;
use Orisai\Exceptions\Logic\InvalidArgument;
use function array_key_last;
use function assert;
use function get_debug_type;
use function is_array;
use function is_bool;
use function is_finite;
use function is_float;
use function is_int;
use function is_object;
use function is_string;
use function json_encode;
use function method_exists;
use function str_contains;
use function strlen;
use function substr;

final class ArrayExpressionModifier implements ExpressionModifier
{

	public function valueToExpression(mixed $value, Connection $connection): string
	{
		assert(is_array($value));

		return "array [{$this->process($connection, $value)}]";
	}

	/**
	 * @param array<mixed> $value
	 */
	protected function process(Connection $connection, array $value): string
	{
		$driver = $connection->getDriver();
		$valueSeparator = ', ';
		$processed = '';
		$last = array_key_last($value);
		foreach ($value as $key => $item) {
			if (is_array($item)) {
				$processed .= $this->valueToExpression($item, $connection);
			} elseif (is_string($item)) {
				$processed .= $driver->convertStringToSql($item);
			} elseif (is_int($item)) {
				$processed .= (string) $item;
			} elseif (is_float($item)) {
				if (!is_finite($item)) {
					if ($key === $last) {
						$processed = substr($processed, 0, -strlen($valueSeparator));
					}

					continue;
				}

				$tmp = json_encode($item);
				assert(is_string($tmp));
				$processed .= $tmp . (!str_contains($tmp, '.') ? '.0' : '');
			} elseif (is_bool($item)) {
				$processed .= $driver->convertBoolToSql($item);
			} elseif ($item === null) {
				$processed .= 'NULL';
			} elseif (is_object($item) && method_exists($item, '__toString')) {
				$processed .= $driver->convertStringToSql((string) $item);
			} else {
				$itemType = get_debug_type($item);

				throw InvalidArgument::create()
					->withMessage("Value of type {$itemType} is not supported.");
			}

			if ($key !== $last) {
				$processed .= $valueSeparator;
			}
		}

		return $processed;
	}

}
