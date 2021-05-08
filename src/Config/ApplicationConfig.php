<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

class ApplicationConfig
{

	private ?string $name;

	private BuildConfig $buildConfig;

	public function __construct(?string $name, BuildConfig $buildConfig)
	{
		$this->name = $name;
		$this->buildConfig = $buildConfig;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function getBuildConfig(): BuildConfig
	{
		return $this->buildConfig;
	}

}
