<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

class BuildConfig
{

	public function __construct(private string|null $name, private string|null $version, private bool $stable)
	{
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
