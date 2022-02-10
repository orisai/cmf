<?php declare(strict_types = 1);

namespace OriCMF\Core\Config;

use OriNette\DI\Services\ServiceManager;

final class ConfigProvider extends ServiceManager
{

	/**
	 * @template T of ConfigItemProvider
	 * @param class-string<T> $class
	 * @return T
	 */
	public function get(string $class): ConfigItemProvider
	{
		return $this->getTypedServiceOrThrow($class, $class);
	}

}
