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
		$id = $info->getId();
		$name = $info->getName();

		$this->logger->error("Job [$id] $name failed", [
			'exception' => $throwable,
			'id' => $id,
			'name' => $name,
			'expression' => $info->getExtendedExpression(),
			'runSecond' => $info->getRunSecond(),
			'start' => $info->getStart()->format(DateTimeInterface::ATOM),
			'end' => $result->getEnd()->format(DateTimeInterface::ATOM),
			'forcedRun' => $info->isForcedRun(),
		]);
	}

}
