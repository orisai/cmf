<?php declare(strict_types = 1);

namespace OriCMF\UI\Template;

use Symfony\Component\Uid\UuidV7;

/**
 * @internal
 */
final class UIFilters
{

	public static function urlUid(string $uid): string
	{
		return UuidV7::fromRfc4122($uid)->toBase58();
	}

}
