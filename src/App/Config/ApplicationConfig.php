<?php declare(strict_types = 1);

namespace OriCMF\App\Config;

use OriCMF\Config\ConfigItemProvider;

final class ApplicationConfig implements ConfigItemProvider
{

	public function __construct(private readonly string|null $name)
	{
	}

	public function getName(): string|null
	{
		return $this->name;
	}

}
