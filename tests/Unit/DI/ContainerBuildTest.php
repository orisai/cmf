<?php declare(strict_types = 1);

namespace Tests\OriCMF\Unit\DI;

use Nette\DI\Container;
use OriNette\DI\Boot\ManualConfigurator;
use PHPUnit\Framework\TestCase;
use function dirname;
use function mkdir;
use const PHP_VERSION_ID;

final class ContainerBuildTest extends TestCase
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

	public function testBuild(): void
	{
		$configurator = new ManualConfigurator($this->rootDir);
		$configurator->setForceReloadContainer();
		$configurator->addConfig(__DIR__ . '/../../../src/wiring.neon');
		$configurator->addConfig(__DIR__ . '/wiring.neon');

		$container = $configurator->createContainer();

		self::assertInstanceOf(Container::class, $container);
	}

}
