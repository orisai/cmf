<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\DI;

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
use OriCMF\UI\Template\UITemplate;
use OriCMF\UI\Template\UITemplateExtension as LatteExtension;
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

		$extensionDefinition = $builder->addDefinition($this->prefix('extension'))
			->setFactory(LatteExtension::class)
			->setAutowired(false);

		$latteFactoryDefinition->getResultDefinition()
			->addSetup(
				'addExtension',
				[$extensionDefinition],
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

}
