<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

final class BuildConfig implements ConfigItemProvider
{

	public function __construct(
		private readonly string|null $name,
		private readonly string|null $version,
		private readonly bool $stable,
	)
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
