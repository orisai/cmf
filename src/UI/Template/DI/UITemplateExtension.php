<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\DI;

use Latte\Engine;
use Nette\Application\UI\Template;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Container;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\ServiceDefinition;
use OriCMF\UI\Control\BaseControlTemplate;
use OriCMF\UI\Template\Locator\ControlTemplateLocator;
use OriCMF\UI\Template\UIMacros;
use function assert;

final class UITemplateExtension extends CompilerExtension
{

	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();

		$templateFactoryDefinition = $builder->getDefinition('latte.templateFactory');
		assert($templateFactoryDefinition instanceof ServiceDefinition);

		$templateFactoryDefinition->addSetup(
			[self::class, 'prepareTemplate'],
			[$templateFactoryDefinition, '@' . Container::class],
		);

		$latteFactoryDefinition = $builder->getDefinitionByType(LatteFactory::class);
		assert($latteFactoryDefinition instanceof FactoryDefinition);

		$latteFactoryDefinition->getResultDefinition()
			->addSetup(
				[self::class, 'installMacros'],
				['@self'],
			);
	}

	public static function prepareTemplate(TemplateFactory $templateFactory, Container $container): void
	{
		$templateFactory->onCreate[] = static function (Template $template) use ($container): void {
			if ($template instanceof BaseControlTemplate) {
				$controlTemplateLocator = $container->getByName('ori.ui.template.locator.control');
				assert($controlTemplateLocator instanceof ControlTemplateLocator);
				$template->setTemplateLocator($controlTemplateLocator);
			}
		};
	}

	public static function installMacros(Engine $engine): void
	{
		$engine->onCompile[] = static function (Engine $engine): void {
			UIMacros::install($engine->getCompiler());
		};
	}

}
