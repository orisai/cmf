<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use MabeEnum\Enum;

/**
 * @method static self NEW()
 * @method static self ACTIVE()
 * @method static self DISABLED()
 */
final class UserState extends Enum
{

	public const NEW = 'new';

	public const ACTIVE = 'active';

	public const DISABLED = 'disabled';

}
