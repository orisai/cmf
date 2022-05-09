<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use OriCMF\Core\Enum\TranslatableBackedEnum;

enum UserState: string
{

	use TranslatableBackedEnum;

	case New = 'new';

	case Active = 'active';

	case Disabled = 'disabled';

	private static function getTranslationPrefix(): string
	{
		return 'ori.cmf.user.state.';
	}

}
