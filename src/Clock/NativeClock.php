<?php declare(strict_types = 1);

namespace OriCMF\Clock;

use Brick\DateTime\Clock;
use Brick\DateTime\Instant;
use Brick\DateTime\ZonedDateTime;
use Psr\Clock\ClockInterface;

final class NativeClock implements Clock
{

	public function __construct(private readonly ClockInterface $clock)
	{
	}

	public function getTime(): Instant
	{
		return ZonedDateTime::fromNativeDateTime($this->clock->now())->getInstant();
	}

}
