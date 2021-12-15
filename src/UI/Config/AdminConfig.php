<?php declare(strict_types = 1);

namespace OriCMF\UI\Config;

final class AdminConfig
{

	public function __construct(private LoginConfig $loginConfig)
	{
	}

	public function getLogin(): LoginConfig
	{
		return $this->loginConfig;
	}

	/**
	 * @return array<mixed>
	 */
	public function __serialize(): array
	{
		return [
			'login' => $this->loginConfig,
		];
	}

	/**
	 * @param array<mixed> $data
	 */
	public function __unserialize(array $data): void
	{
		$this->loginConfig = $data['login'];
	}

}
