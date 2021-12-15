<?php declare(strict_types = 1);

namespace OriCMF\UI\Config;

use Brick\DateTime\Duration;

final class LoginConfig
{

	private Duration|null $expiration;

	public function __construct(int|null $expiration)
	{
		$this->expiration = $expiration === null ? null : Duration::ofSeconds($expiration);
	}

	public function getExpiration(): Duration|null
	{
		return $this->expiration;
	}

	/**
	 * @return array<mixed>
	 */
	public function __serialize(): array
	{
		return [
			'expiration' => $this->expiration?->getSeconds(),
		];
	}

	/**
	 * @param array<mixed> $data
	 */
	public function __unserialize(array $data): void
	{
		$this->expiration = $data['expiration'] === null ? null : Duration::ofSeconds($data['expiration']);
	}

}
