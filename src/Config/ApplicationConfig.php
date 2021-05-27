<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

class ApplicationConfig
{

	public function __construct(private string|null $name, private BuildConfig $buildConfig)
	{
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
