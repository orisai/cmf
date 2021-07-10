<?php declare(strict_types = 1);

namespace OriCMF\Core\Enum;

use MabeEnum\Enum;
use Orisai\Localization\Translator;
use function array_map;

abstract class TranslatableEnum extends Enum
{

	abstract protected static function getTranslationPrefix(): string;

	/**
	 * @return array<string, string>
	 */
	protected static function getTranslatableLabels(): array
	{
		$prefix = static::getTranslationPrefix();
		$labels = [];

		foreach (static::getEnumerators() as $enumerator) {
			$labels[$enumerator->getName()] = $prefix . $enumerator->getValue();
		}

		return $labels;
	}

	public function getLabel(Translator|null $translator = null): string
	{
		$label = static::getTranslatableLabels()[$this->getName()];

		return $translator === null
			? $label
			: $translator->translate($label);
	}

	/**
	 * @return array<string, string>
	 */
	public static function getLabels(Translator|null $translator = null): array
	{
		$labels = static::getTranslatableLabels();

		return $translator === null
			? $labels
			: array_map(
				static fn (string $label): string => $translator->translate($label),
				$labels,
			);
	}

}
