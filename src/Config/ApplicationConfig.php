<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

class ApplicationConfig
{

	private string|null $name;

	private BuildConfig $buildConfig;

	public function __construct(string|null $name, BuildConfig $buildConfig)
	{
		$this->name = $name;
		$this->buildConfig = $buildConfig;
	}

	public function getName(): string|null
	{
		return $this->name;
	}

	public function getBuildConfig(): BuildConfig
	{
		return $this->buildConfig;
	}

}
