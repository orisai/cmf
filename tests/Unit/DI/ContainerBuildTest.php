<?php declare(strict_types = 1);

namespace Tests\OriCMF\Unit\DI;

use Nette\DI\Container;
use OriNette\DI\Boot\AutomaticConfigurator;
use Orisai\Installer\Schema\ModuleSchema;
use Orisai\Installer\Tester\InstallerTester;
use PHPUnit\Framework\TestCase;
use function dirname;
use function mkdir;
use const PHP_VERSION_ID;

final class ContainerBuildTest extends TestCase
{

	private string $rootDir;

	private InstallerTester $tester;

	protected function setUp(): void
	{
		parent::setUp();

		$this->rootDir = dirname(__DIR__, 3);
		if (PHP_VERSION_ID < 8_01_00) {
			@mkdir("$this->rootDir/var/build");
		}

		$this->tester = new InstallerTester();
	}

	public function testBuild(): void
	{
		$schema = new ModuleSchema();
		$schema->addConfigFile(__DIR__ . '/wiring.neon');

		$loader = $this->tester->generateLoader($schema);
		$configurator = new AutomaticConfigurator($this->rootDir, $loader);
		$configurator->setForceReloadContainer();

		$container = $configurator->createContainer();

		self::assertInstanceOf(Container::class, $container);
	}

}
