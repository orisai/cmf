<?php declare(strict_types = 1);

namespace Tests\OriCMF\Unit\Clock;

use Brick\DateTime\Clock\FixedClock;
use Brick\DateTime\Clock\SystemClock;
use Brick\DateTime\Instant;
use OriCMF\Clock\ClockGetter;
use PHPUnit\Framework\TestCase;

final class ClockGetterTest extends TestCase
{

	public function test(): void
	{
		self::assertInstanceOf(SystemClock::class, ClockGetter::get());

		$clock = new FixedClock(Instant::of(1));
		ClockGetter::set($clock);
		self::assertSame($clock, ClockGetter::get());
	}

}
