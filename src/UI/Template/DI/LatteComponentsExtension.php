<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\DI;

use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use OriCMF\UI\Template\Latte\LatteComponentsExtension as LatteExtension;
use function assert;

final class LatteComponentsExtension extends CompilerExtension
{

	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();

		$latteFactoryDefinition = $builder->getDefinitionByType(LatteFactory::class);
		assert($latteFactoryDefinition instanceof FactoryDefinition);

		$extensionDefinition = $builder->addDefinition($this->prefix('extension'))
			->setFactory(LatteExtension::class)
			->setAutowired(false);

		$latteFactoryDefinition->getResultDefinition()
			->addSetup(
				'addExtension',
				[$extensionDefinition],
			);
	}

}
