<?php declare(strict_types = 1);

namespace OriCMF\Dbal;

use Nextras\Dbal\Connection;
use Nextras\Dbal\ISqlProcessorFactory;
use Nextras\Dbal\SqlProcessor;
use OriCMF\Dbal\Modifier\ExpressionModifier;

final class SqlProcessorFactory implements ISqlProcessorFactory
{

	/** @var array<string, ExpressionModifier> */
	private array $modifiers = [];

	public function addModifier(string $modifier, ExpressionModifier $expressionModifier): void
	{
		$this->modifiers[$modifier] = $expressionModifier;
	}

	public function create(Connection $connection): SqlProcessor
	{
		$sqlProcessor = new SqlProcessor($connection->getDriver(), $connection->getPlatform());

		foreach ($this->modifiers as $name => $expressionModifier) {
			$sqlProcessor->setCustomModifier(
				$name,
				static fn ($value, string $modifier) => $expressionModifier->valueToExpression($value, $connection),
			);
		}

		return $sqlProcessor;
	}

}
