<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Locator;

use OriCMF\UI\TemplateLocator\Exception\NoTemplateFound;
use Orisai\Utils\Reflection\Classes;
use function in_array;
use function is_file;
use function preg_replace;

/**
 * @internal
 */
abstract class BaseComponentTemplateLocator
{

	public function __construct(private readonly string $rootDir)
	{
	}

	/**
	 * @param list<class-string> $breakClasses
	 * @throws NoTemplateFound
	 */
	protected function getPath(
		object $templatedClass,
		string $viewName,
		string $baseClassSuffix,
		array $breakClasses,
		string $defaultName,
	): string
	{
		$baseFileName = Classes::getShortName($templatedClass::class);

		if ($baseClassSuffix !== '') {
			$baseFileName = preg_replace(
				"#$baseClassSuffix$#",
				'',
				$baseFileName,
			);
		}

		$triedPaths = [];
		foreach (Classes::getClassList($templatedClass::class) as $class) {
			$fileName = $viewName === $defaultName
				? $baseFileName
				: "$baseFileName.$viewName";

			$dir = Classes::getClassDir($class);
			$templatePath = "$dir/$fileName.latte";

			if (is_file($templatePath)) {
				return $templatePath;
			}

			if (in_array($class, $breakClasses, true)) {
				break;
			}

			$triedPaths[] = $templatePath;
		}

		throw NoTemplateFound::create($triedPaths, $templatedClass, $this->rootDir);
	}

}
