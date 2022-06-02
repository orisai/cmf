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
use OriCMF\Core\Config\ConfigProvider;
use OriCMF\UI\Control\BaseControlTemplate;
use OriCMF\UI\Template\Locator\ControlTemplateLocator;
use OriCMF\UI\Template\UIFilters;
use OriCMF\UI\Template\UIMacros;
use OriCMF\UI\Template\UITemplate;
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
			[
				$templateFactoryDefinition,
				'@' . Container::class,
				$builder->getByType(ConfigProvider::class),
				$builder->getByType(ControlTemplateLocator::class),
			],
		);

		$latteFactoryDefinition = $builder->getDefinitionByType(LatteFactory::class);
		assert($latteFactoryDefinition instanceof FactoryDefinition);

		$latteFactoryDefinition->getResultDefinition()
			->addSetup(
				[self::class, 'installExtension'],
				['@self'],
			);
	}

	public static function prepareTemplate(
		TemplateFactory $templateFactory,
		Container $container,
		string $configProvider,
		string $templateLocator,
	): void
	{
		$templateFactory->onCreate[] = static function (Template $template) use ($container, $configProvider, $templateLocator): void {
			if ($template instanceof UITemplate) {
				$config = $container->getService($configProvider);
				assert($config instanceof ConfigProvider);
				$template->config = $config;
			}

			if ($template instanceof BaseControlTemplate) {
				$controlTemplateLocator = $container->getService($templateLocator);
				assert($controlTemplateLocator instanceof ControlTemplateLocator);
				$template->setTemplateLocator($controlTemplateLocator);
			}
		};
	}

	public static function installExtension(Engine $engine): void
	{
		$engine->addFilter('urlUlid', UIFilters::urlUlid(...));
		$engine->onCompile[] = static function (Engine $engine): void {
			UIMacros::install($engine->getCompiler());
		};
	}

}
