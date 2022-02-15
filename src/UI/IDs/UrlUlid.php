<?php declare(strict_types = 1);

namespace OriCMF\UI\IDs;

use InvalidArgumentException;
use Nette\Application\BadRequestException;
use Symfony\Component\Uid\Ulid;

/**
 * Convert between url-friendly (base58) and storage-friendly (rfc4122) version of ulid
 */
final class UrlUlid
{

	private function __construct(private Ulid $ulid)
	{
	}

	/**
	 * @throws BadRequestException
	 */
	public static function fromUri(string $parameter): self
	{
		try {
			return new self(Ulid::fromBase58($parameter));
		} catch (InvalidArgumentException) {
			throw new BadRequestException();
		}
	}

	public static function fromStorage(string $parameter): self
	{
		return new self(Ulid::fromRfc4122($parameter));
	}

	public function toBase58(): string
	{
		return $this->ulid->toBase58();
	}

	public function toUri(): string
	{
		return $this->toBase58();
	}

	public function toRfc4122(): string
	{
		return $this->ulid->toRfc4122();
	}

	public function toStorage(): string
	{
		return $this->toRfc4122();
	}

}
