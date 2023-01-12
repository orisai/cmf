<?php declare(strict_types = 1);

namespace OriCMF\Translation;

use Orisai\TranslationContracts\Translatable;
use function Orisai\TranslationContracts\tm;

trait TranslatableBackedEnum
{

	abstract protected static function getCaseLabel(self $case): Translatable;

	/**
	 * @return array<int|string, string>
	 */
	public static function getLabels(string|null $languageTag = null): array
	{
		$labels = [];
		foreach (self::cases() as $enumerator) {
			$labels[$enumerator->value] = tm(self::getCaseLabel($enumerator), locale: $languageTag);
		}

		return $labels;
	}

	public function getLabel(string|null $languageTag = null): string
	{
		return tm(self::getCaseLabel($this), locale: $languageTag);
	}

}
