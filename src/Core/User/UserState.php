<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use OriCMF\Core\Enum\TranslatableBackedEnum;
use Orisai\Localization\TranslatableMessage;

enum UserState: string
{

	use TranslatableBackedEnum;

	case New = 'new';

	case Active = 'active';

	case Disabled = 'disabled';

	private static function getCaseLabel(self $case): TranslatableMessage
	{
		return match ($case) {
			self::New => new TranslatableMessage('ori.cmf.user.state.new'),
			self::Active => new TranslatableMessage('ori.cmf.user.state.active'),
			self::Disabled => new TranslatableMessage('ori.cmf.user.state.disabled'),
		};
	}

}
