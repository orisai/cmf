<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\Exception;

use Orisai\Exceptions\LogicalException;
use Webmozart\PathUtil\Path;
use function implode;

final class NoTemplateFound extends LogicalException
{

	/** @var array<string> */
	public array $triedPaths;

	/** @var array<string> */
	public array $shortTriedPaths;

	/**
	 * @param array<string> $triedPaths
	 */
	public static function create(array $triedPaths, object $templatedObject, string $rootDir): self
	{
		$templatedClass = $templatedObject::class;

		$shortTriedPaths = self::getShortPaths($triedPaths, $rootDir);
		$inlinePaths = implode("\n", $shortTriedPaths);

		$message = "Template of {$templatedClass} not found. None of the following templates exists:\n{$inlinePaths}";

		$self = new self();
		$self->triedPaths = $triedPaths;
		$self->shortTriedPaths = $shortTriedPaths;
		$self->withMessage($message);

		return $self;
	}

	/**
	 * @return array<string>
	 */
	public function getTriedPaths(): array
	{
		return $this->triedPaths;
	}

	/**
	 * @return array<string>
	 */
	public function getShortTriedPaths(): array
	{
		return $this->shortTriedPaths;
	}

	/**
	 * @param array<string>  $paths
	 * @return array<string>
	 */
	private static function getShortPaths(array $paths, string $basePath): array
	{
		$short = [];
		foreach ($paths as $path) {
			$short[] = Path::makeRelative($path, $basePath);
		}

		return $short;
	}

}
