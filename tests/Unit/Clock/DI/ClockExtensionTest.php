<?php declare(strict_types = 1);

namespace Tests\OriCMF\Unit\Clock\DI;

use Brick\DateTime\Clock;
use Brick\DateTime\Clock\SystemClock;
use OriCMF\Clock\ClockGetter;
use OriNette\DI\Boot\ManualConfigurator;
use Orisai\Exceptions\Logic\InvalidArgument;
use PHPUnit\Framework\TestCase;
use function date_default_timezone_get;
use function date_default_timezone_set;
use function dirname;
use function ini_get;
use function mkdir;
use const PHP_VERSION_ID;

/**
 * @runInSeparateProcess
 */
final class ClockExtensionTest extends TestCase
{

	private string $rootDir;

	protected function setUp(): void
	{
		parent::setUp();

		$this->rootDir = dirname(__DIR__, 4);
		if (PHP_VERSION_ID < 8_01_00) {
			@mkdir("$this->rootDir/var/build");
		}
	}

	public function testBasic(): void
	{
		$configurator = new ManualConfigurator($this->rootDir);
		$configurator->setForceReloadContainer();
		$configurator->addConfig(__DIR__ . '/config.neon');

		date_default_timezone_set('Europe/Prague');
		self::assertSame('Europe/Prague', date_default_timezone_get());

		$container = $configurator->createContainer();

		$clock = $container->getService('ori.cmf.clock.clock');
		self::assertInstanceOf(SystemClock::class, $clock);
		self::assertSame($clock, $container->getByType(Clock::class));
		self::assertSame($clock, ClockGetter::get());

		self::assertSame('UTC', date_default_timezone_get());
		self::assertSame('UTC', ini_get('date.timezone'));
	}

	public function testUnknownTimezone(): void
	{
		$configurator = new ManualConfigurator($this->rootDir);
		$configurator->setForceReloadContainer();
		$configurator->addConfig(__DIR__ . '/config.invalid.neon');

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage('Timezone unknown/timezone is invalid.');

		$configurator->createContainer();
	}

}
