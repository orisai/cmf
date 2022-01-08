<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\Locator;

use Nette\Application\UI\Presenter;
use OriCMF\UI\Presenter\BasePresenter;
use OriCMF\UI\Template\Exception\NoTemplateFound;
use Orisai\Utils\Reflection\Classes;
use function is_file;
use function preg_replace;

final class PresenterTemplateLocator
{

	public function __construct(private string $rootDir)
	{
	}

	/**
	 * @throws NoTemplateFound
	 */
	public function getLayoutTemplatePath(Presenter $presenter, string $layoutName): string
	{
		return $this->getPath($presenter, "@{$layoutName}");
	}

	/**
	 * @throws NoTemplateFound
	 */
	public function getActionTemplatePath(Presenter $presenter, string $viewName): string
	{
		return $this->getPath($presenter, $viewName);
	}

	/**
	 * @throws NoTemplateFound
	 */
	private function getPath(Presenter $presenter, string $viewName): string
	{
		$classes = Classes::getClassList($presenter::class);
		$triedPaths = [];

		$baseFileName = preg_replace('#Presenter$#', '', Classes::getShortName($presenter::class));

		foreach ($classes as $class) {
			if ($class === Presenter::class || $class === BasePresenter::class) {
				break;
			}

			$fileName = $viewName === Presenter::DEFAULT_ACTION
				? $baseFileName
				: "$baseFileName.$viewName";

			$dir = Classes::getClassDir($class);
			$templatePath = "$dir/$fileName.latte";

			if (is_file($templatePath)) {
				return $templatePath;
			}

			$triedPaths[] = $templatePath;
		}

		throw NoTemplateFound::create($triedPaths, $presenter, $this->rootDir);
	}

}
