<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Locator;

use OriCMF\UI\TemplateLocator\Exception\NoTemplateFound;
use Orisai\Utils\Reflection\Classes;
use function is_file;
use function preg_replace;

/**
 * @internal
 */
abstract class BaseTemplateLocator
{

	public function __construct(private readonly string $rootDir)
	{
	}

	/**
	 * @param class-string $breakClass
	 * @throws NoTemplateFound
	 */
	protected function getPath(
		object $templatedClass,
		string $viewName,
		string $baseClassSuffix,
		string $breakClass,
		string $defaultName,
	): string
	{
		$classes = Classes::getClassList($templatedClass::class);
		$triedPaths = [];

		$baseFileName = preg_replace(
			"#$baseClassSuffix$#",
			'',
			Classes::getShortName($templatedClass::class),
		);

		foreach ($classes as $class) {
			if ($class === $breakClass) {
				break;
			}

			$fileName = $viewName === $defaultName
				? $baseFileName
				: "$baseFileName.$viewName";

			$dir = Classes::getClassDir($class);
			$templatePath = "$dir/$fileName.latte";

			if (is_file($templatePath)) {
				return $templatePath;
			}

			$triedPaths[] = $templatePath;
		}

		throw NoTemplateFound::create($triedPaths, $templatedClass, $this->rootDir);
	}

}
