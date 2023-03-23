<?php declare(strict_types = 1);

namespace OriCMF\Logging;

use DateTimeInterface;
use Orisai\Scheduler\Status\JobInfo;
use Orisai\Scheduler\Status\JobResult;
use Psr\Log\LoggerInterface;
use Throwable;

final class SchedulerLogger
{

	public function __construct(private readonly LoggerInterface $logger)
	{
	}

	public function log(Throwable $throwable, JobInfo $info, JobResult $result): void
	{
		$name = $info->getName();
		$this->logger->error("Job $name failed", [
			'exception' => $throwable,
			'name' => $name,
			'expression' => $info->getExpression(),
			'start' => $info->getStart()->format(DateTimeInterface::ATOM),
			'end' => $result->getEnd()->format(DateTimeInterface::ATOM),
		]);
	}

}
