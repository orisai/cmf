<?php declare(strict_types = 1);

namespace OriCMF\Core\Enum;

use Orisai\Localization\TranslatableMessage;
use function Orisai\Localization\tm;

trait TranslatableBackedEnum
{

	abstract protected static function getCaseLabel(self $case): TranslatableMessage;

	/**
	 * @return array<int|string, string>
	 */
	public static function getLabels(string|null $languageTag = null): array
	{
		$labels = [];
		foreach (self::cases() as $enumerator) {
			$labels[$enumerator->value] = tm(self::getCaseLabel($enumerator), languageTag: $languageTag);
		}

		return $labels;
	}

	public function getLabel(string|null $languageTag = null): string
	{
		return tm(self::getCaseLabel($this), languageTag: $languageTag);
	}

}
