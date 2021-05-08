<?php declare(strict_types = 1);

namespace OriCMF\Core\SQL\Modifier;

use Nextras\Dbal\Connection;

interface ExpressionModifier
{

	public function valueToExpression(mixed $value, Connection $connection): mixed;

}
