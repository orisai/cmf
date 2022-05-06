<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use OriCMF\Core\Enum\TranslatableEnum;

/**
 * @method static self New()
 * @method static self Active()
 * @method static self Disabled()
 */
final class UserState extends TranslatableEnum
{

	public const New = 'new';

	public const Active = 'active';

	public const Disabled = 'disabled';

	protected static function getTranslationPrefix(): string
	{
		return 'ori.cmf.user.state.';
	}

}
