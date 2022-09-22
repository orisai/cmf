<?php declare(strict_types = 1);

namespace OriCMF\UI\Template;

use Symfony\Component\Uid\Ulid;

/**
 * @internal
 */
final class UIFilters
{

	public static function urlUid(string $uid): string
	{
		return Ulid::fromRfc4122($uid)->toBase58();
	}

}
