<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

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
