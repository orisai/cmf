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
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use OriCMF\Config\ConfigProvider;
use OriCMF\UI\Template\UIFilters;
use OriCMF\UI\Template\UIMacros;
use OriCMF\UI\Template\UITemplate;
use stdClass;
use function assert;

/**
 * @property-read stdClass $config
 */
final class UITemplateExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([]);
	}

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
	): void
	{
		$templateFactory->onCreate[] = static function (Template $template) use ($container, $configProvider): void {
			if ($template instanceof UITemplate) {
				$config = $container->getService($configProvider);
				assert($config instanceof ConfigProvider);
				$template->config = $config;
			}
		};
	}

	public static function installExtension(Engine $engine): void
	{
		$engine->addFilter('urlUid', UIFilters::urlUid(...));
		$engine->onCompile[] = static function (Engine $engine): void {
			UIMacros::install($engine->getCompiler());
		};
	}

}
