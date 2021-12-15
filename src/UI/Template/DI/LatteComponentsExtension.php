<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\DI;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use OriCMF\UI\Template\LatteComponentMacros;
use function assert;

final class LatteComponentsExtension extends CompilerExtension
{

	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();

		$latteFactoryDefinition = $builder->getDefinitionByType(LatteFactory::class);
		assert($latteFactoryDefinition instanceof FactoryDefinition);

		$latteFactoryDefinition->getResultDefinition()
			->addSetup(
				[self::class, 'installMacros'],
				['@self'],
			);
	}

	public static function installMacros(Engine $engine): void
	{
		$engine->onCompile[] = static function (Engine $engine): void {
			LatteComponentMacros::install($engine->getCompiler());
		};
	}

}
