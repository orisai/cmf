<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

class BuildConfig
{

	private ?string $name;
	private ?string $version;
	private bool $stable;

	public function __construct(?string $name, ?string $version, bool $stable)
	{
		$this->name = $name;
		$this->version = $version;
		$this->stable = $stable;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getVersion(): ?string
	{
		return $this->version;
	}

	public function isStable(): bool
	{
		return $this->stable;
	}

}
