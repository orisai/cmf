<?php declare(strict_types = 1);

namespace OriCMF\UI\Config;

final class UIConfig
{

	public function __construct(private AdminConfig $adminConfig, private FrontConfig $frontConfig)
	{
	}

	public function getAdmin(): AdminConfig
	{
		return $this->adminConfig;
	}

	public function getFront(): FrontConfig
	{
		return $this->frontConfig;
	}

	/**
	 * @return array<mixed>
	 */
	public function __serialize(): array
	{
		return [
			'admin' => $this->adminConfig,
			'front' => $this->frontConfig,
		];
	}

	/**
	 * @param array<mixed> $data
	 */
	public function __unserialize(array $data): void
	{
		$this->adminConfig = $data['admin'];
		$this->frontConfig = $data['front'];
	}

}
