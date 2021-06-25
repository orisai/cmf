<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use MabeEnum\Enum;
use Orisai\Localization\Translator;
use function array_map;

/**
 * @method static self NEW()
 * @method static self ACTIVE()
 * @method static self DISABLED()
 * @method string getValue()
 */
final class UserState extends Enum
{

	public const NEW = 'new';

	public const ACTIVE = 'active';

	public const DISABLED = 'disabled';

	private const LABELS = [
		self::NEW => 'ori.ui.user.state.new',
		self::ACTIVE => 'ori.ui.user.state.active',
		self::DISABLED => 'ori.ui.user.state.disabled',
	];

	public function getLabel(): string
	{
		return self::LABELS[$this->getValue()];
	}

	/**
	 * @return array<string, string>
	 */
	public static function getValueLabelPairs(Translator $translator): array
	{
		return array_map(static fn (string $label): string => $translator->translate($label), self::LABELS);
	}

}
