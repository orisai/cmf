<?php declare(strict_types = 1);

namespace OriCMF\UI\Template;

use Symfony\Component\Uid\Ulid;

/**
 * @internal
 */
final class UIFilters
{

	public static function urlUlid(string $ulid): string
	{
		return Ulid::fromRfc4122($ulid)->toBase58();
	}

}
