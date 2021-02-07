<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Functions;

use Nextras\Orm\Collection\Functions\BaseAggregateFunction;

final class ArrayAggregateFunction extends BaseAggregateFunction
{

	public function __construct()
	{
		parent::__construct('ARRAY_AGG');
	}

	/**
	 * @param array<mixed> $values
	 */
	protected function calculateAggregation(array $values): string
	{
		$string = '';

		foreach ($values as $value) {
			$string .= $value;
		}

		return $string;
	}

}
