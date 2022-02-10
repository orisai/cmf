<?php declare(strict_types = 1);

namespace OriCMF\Core\Config\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use OriCMF\Core\Config\ConfigItemProvider;
use OriCMF\Core\Config\ConfigProvider;

final class ConfigExtension extends CompilerExtension
{

	private ServiceDefinition $providerDefinition;

	public function getConfigSchema(): Schema
	{
		return Expect::structure([]);
	}

	public function loadConfiguration(): void
	{
		parent::loadConfiguration();
		$builder = $this->getContainerBuilder();

		$this->providerDefinition = $builder->addDefinition($this->prefix('provider'))
			->setFactory(ConfigProvider::class);
	}

	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();

		$itemProviders = [];
		foreach ($builder->findByType(ConfigItemProvider::class) as $definition) {
			$itemProviders[$definition->getType()] = $definition->getName();
		}

		$this->providerDefinition->setArguments([
			$itemProviders,
		]);
	}

}
