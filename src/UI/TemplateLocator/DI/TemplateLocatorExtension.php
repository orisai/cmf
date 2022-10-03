<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\DI;

use Nette\Application\UI\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Container;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use OriCMF\UI\TemplateLocator\Locator\ControlTemplateLocator;
use OriCMF\UI\TemplateLocator\Locator\PresenterTemplateLocator;
use OriCMF\UI\TemplateLocator\Template\FlexibleControlTemplate;
use stdClass;
use function assert;

/**
 * @property-read stdClass $config
 */
final class TemplateLocatorExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'rootDir' => Expect::string(),
		]);
	}

	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$builder->addDefinition($this->prefix('locator.control'))
			->setFactory(ControlTemplateLocator::class, [
				'rootDir' => $config->rootDir,
			]);

		$builder->addDefinition($this->prefix('locator.presenter'))
			->setFactory(PresenterTemplateLocator::class, [
				'rootDir' => $config->rootDir,
			]);
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
				$builder->getByType(ControlTemplateLocator::class),
			],
		);
	}

	public static function prepareTemplate(
		TemplateFactory $templateFactory,
		Container $container,
		string $locatorServiceName,
	): void
	{
		$templateFactory->onCreate[] = static function (Template $template) use ($container, $locatorServiceName): void {
			if (!$template instanceof FlexibleControlTemplate) {
				return;
			}

			static $locator;

			if ($locator === null) {
				$locator = $container->getService($locatorServiceName);
				assert($locator instanceof ControlTemplateLocator);
			}

			$template->setTemplateLocator($locator);
		};
	}

}
