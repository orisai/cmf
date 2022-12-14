<?php declare(strict_types = 1);

namespace OriCMF\Clock\DI;

use Brick\DateTime\Clock;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use OriCMF\Clock\NativeClock;
use Orisai\Exceptions\Logic\InvalidArgument;
use stdClass;
use function date_default_timezone_get;
use function date_default_timezone_set;

/**
 * @property-read stdClass $config
 */
final class ClockExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'timezone' => Expect::string('UTC'),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$this->registerClock($builder);
		$this->configureTimezone($config->timezone);
	}

	private function registerClock(ContainerBuilder $builder): void
	{
		$builder->addDefinition($this->prefix('clock'))
			->setFactory(NativeClock::class)
			->setType(Clock::class);
	}

	private function configureTimezone(string $timezone): void
	{
		$currentTz = date_default_timezone_get();
		if (!@date_default_timezone_set($timezone)) {
			throw InvalidArgument::create()
				->withMessage("Timezone {$timezone} is invalid.");
		}

		date_default_timezone_set($currentTz);

		$initialization = $this->getInitialization();
		$initialization->addBody(
			<<<'PHP'
date_default_timezone_set(?);
ini_set('date.timezone', ?);
PHP,
			[
				$timezone,
				$timezone,
			],
		);
	}

}
