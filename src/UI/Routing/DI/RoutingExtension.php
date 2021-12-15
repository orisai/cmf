<?php declare(strict_types = 1);

namespace OriCMF\UI\Routing\DI;

use Nette\Application\Application;
use Nette\Application\Routers\RouteList;
use Nette\Bridges\ApplicationTracy\RoutingPanel;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use Nette\Routing\Router;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use OriNette\DI\Definitions\DefinitionsLoader;
use stdClass;
use Tracy\Bar;
use function assert;

/**
 * @property-read stdClass $config
 */
final class RoutingExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'debug' => Expect::bool(false),
			'providers' => Expect::arrayOf(
				DefinitionsLoader::schema(),
			),
		]);
	}

	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		$loader = new DefinitionsLoader($this->compiler);
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$this->registerRouter($config, $builder, $loader);
	}

	private function registerRouter(stdClass $config, ContainerBuilder $builder, DefinitionsLoader $loader): void
	{
		$routerDefinition = $builder->addDefinition($this->prefix('router'))
			->setFactory(RouteList::class)
			->setType(Router::class);

		foreach ($config->providers as $providerName => $providerConfig) {
			$providerKey = $this->prefix("provider.{$providerName}");
			$providerDefinition = $loader->loadDefinitionFromConfig(
				$providerConfig,
				$providerKey,
			);

			$routerDefinition->addSetup(
				'?[] = ?->getRouter()',
				[
					'@self',
					$providerDefinition,
				],
			);
		}
	}

	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$this->registerTracyBar($config, $builder);
	}

	private function registerTracyBar(stdClass $config, ContainerBuilder $builder): void
	{
		if (!$config->debug) {
			return;
		}

		$applicationDefinition = $builder->getDefinitionByType(Application::class);
		assert($applicationDefinition instanceof ServiceDefinition);

		$barClass = Bar::class;
		$applicationDefinition->addSetup("@{$barClass}::addPanel", [
			new Statement(RoutingPanel::class),
		]);
	}

}
