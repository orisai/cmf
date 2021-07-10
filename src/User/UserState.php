<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use OriCMF\Core\Enum\TranslatableEnum;

/**
 * @method static self NEW()
 * @method static self ACTIVE()
 * @method static self DISABLED()
 */
final class UserState extends TranslatableEnum
{

	public const NEW = 'new';

	public const ACTIVE = 'active';

	public const DISABLED = 'disabled';

	protected static function getTranslationPrefix(): string
	{
		return 'ori.ui.user.state.';
	}

}
