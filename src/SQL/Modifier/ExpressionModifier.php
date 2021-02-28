<?php declare(strict_types = 1);

namespace OriCMF\Core\SQL\Modifier;

use Nextras\Dbal\Connection;

interface ExpressionModifier
{

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function valueToExpression($value, Connection $connection);

}
