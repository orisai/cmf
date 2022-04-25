<?php declare(strict_types = 1);

namespace OriCMF\Core\Enum;

use MabeEnum\Enum;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Localization\Translator;
use function array_map;
use function is_scalar;

abstract class TranslatableEnum extends Enum
{

	abstract protected static function getTranslationPrefix(): string;

	/**
	 * @return array<string, string>
	 */
	private static function getTranslatableLabels(): array
	{
		$prefix = static::getTranslationPrefix();
		$labels = [];

		foreach (static::getEnumerators() as $enumerator) {
			$value = $enumerator->getValue();

			if (!is_scalar($value)) {
				throw InvalidArgument::create()
					->withMessage('Only enums with scalar values can be translated.');
			}

			$labels[$enumerator->getName()] = $prefix . $value;
		}

		return $labels;
	}

	public function getLabel(Translator|null $translator = null): string
	{
		$label = self::getTranslatableLabels()[$this->getName()];

		return $translator === null
			? $label
			: $translator->translate($label);
	}

	/**
	 * @return array<string, string>
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
