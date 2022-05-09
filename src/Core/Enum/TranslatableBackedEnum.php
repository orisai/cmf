<?php declare(strict_types = 1);

namespace OriCMF\Core\Enum;

use Orisai\Localization\Translator;
use function array_map;

trait TranslatableBackedEnum
{

	abstract private static function getTranslationPrefix(): string;

	/**
	 * @return array<int|string, string>
	 */
	private static function getTranslatableLabels(): array
	{
		$prefix = self::getTranslationPrefix();
		$labels = [];

		foreach (self::cases() as $enumerator) {
			$value = $enumerator->value;
			$labels[$value] = $prefix . $value;
		}

		return $labels;
	}

	public function getLabel(Translator|null $translator = null): string
	{
		$label = static::getTranslationPrefix() . $this->value;

		return $translator === null
			? $label
			: $translator->translate($label);
	}

	/**
	 * @return array<int|string, string>
	 */
	public static function getLabels(Translator|null $translator = null): array
	{
		$labels = self::getTranslatableLabels();

		return $translator === null
			? $labels
			: array_map(
				static fn (string $label): string => $translator->translate($label),
				$labels,
			);
	}

}
