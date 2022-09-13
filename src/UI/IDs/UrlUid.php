<?php declare(strict_types = 1);

namespace OriCMF\UI\IDs;

use InvalidArgumentException;
use Nette\Application\BadRequestException;
use Symfony\Component\Uid\UuidV7;

/**
 * Convert between url-friendly (base58) and storage-friendly (rfc4122) version of UID
 */
final class UrlUid
{

	private function __construct(private readonly UuidV7 $uid)
	{
	}

	/**
	 * @throws BadRequestException
	 */
	public static function fromUri(string $parameter): self
	{
		try {
			return new self(UuidV7::fromBase58($parameter));
		} catch (InvalidArgumentException) {
			throw new BadRequestException();
		}
	}

	public static function fromStorage(string $parameter): self
	{
		return new self(UuidV7::fromRfc4122($parameter));
	}

	public function toUri(): string
	{
		return $this->uid->toBase58();
	}

	public function toStorage(): string
	{
		return $this->uid->toRfc4122();
	}

}
