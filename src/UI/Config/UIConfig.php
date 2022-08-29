<?php declare(strict_types = 1);

namespace OriCMF\UI\Config;

final class UIConfig
{

	public function __construct(private AdminConfig $adminConfig, private PublicConfig $publicConfig)
	{
	}

	public function getAdmin(): AdminConfig
	{
		return $this->adminConfig;
	}

	public function getPublic(): PublicConfig
	{
		return $this->publicConfig;
	}

	/**
	 * @return array<mixed>
	 */
	public function __serialize(): array
	{
		return [
			'admin' => $this->adminConfig,
			'public' => $this->publicConfig,
		];
	}

	/**
	 * @param array<mixed> $data
	 */
	public function __unserialize(array $data): void
	{
		$this->adminConfig = $data['admin'];
		$this->publicConfig = $data['public'];
	}

}
