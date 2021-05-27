<?php declare(strict_types = 1);

namespace OriCMF\Core\SQL;

use Nextras\Dbal\Drivers\Exception\DriverException;
use Nextras\Dbal\ILogger;
use Nextras\Dbal\Result\Result;
use Psr\Log\LoggerInterface;

final class SqlLogger implements ILogger
{

	public function __construct(private LoggerInterface $logger)
	{
	}

	public function onConnect(): void
	{
		$this->logger->debug('Connected to database');
	}

	public function onDisconnect(): void
	{
		$this->logger->debug('Disconnected from database');
	}

	public function onQuery(string $sqlQuery, float $timeTaken, Result|null $result): void
	{
		$this->logger->info("Query: {$sqlQuery}", [
			'time' => $timeTaken,
		]);
	}

	public function onQueryException(string $sqlQuery, float $timeTaken, DriverException|null $exception): void
	{
		$context = [
			'time' => $timeTaken,
		];

		if ($exception !== null) {
			$context['exception'] = $exception;
		}

		$this->logger->error("Query failed: {$sqlQuery}", $context);
	}

}
