<?php declare(strict_types = 1);

namespace OriCMF\User\DB;

use OriCMF\Translation\TranslatableBackedEnum;
use Orisai\TranslationContracts\Translatable;
use Orisai\TranslationContracts\TranslatableMessage;

enum UserState: string
{

	use TranslatableBackedEnum;

	case New = 'new';

	case Active = 'active';

	case Disabled = 'disabled';

	private static function getCaseLabel(self $case): Translatable
	{
		return match ($case) {
			self::New => new TranslatableMessage('ori.cmf.user.state.new'),
			self::Active => new TranslatableMessage('ori.cmf.user.state.active'),
			self::Disabled => new TranslatableMessage('ori.cmf.user.state.disabled'),
		};
	}

}
