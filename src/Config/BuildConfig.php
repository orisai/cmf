<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

class BuildConfig
{

	private string|null $name;

	private string|null $version;

	private bool $stable;

	public function __construct(string|null $name, string|null $version, bool $stable)
	{
		$this->name = $name;
		$this->version = $version;
		$this->stable = $stable;
	}

	public function getName(): string|null
	{
		return $this->name;
	}

	public function getVersion(): string|null
	{
		return $this->version;
	}

	public function isStable(): bool
	{
		return $this->stable;
	}

}
