<?php declare(strict_types = 1);

namespace OriCMF\UI\Template;

use Latte\Engine;
use Nette\Bridges\ApplicationLatte\Template;
use OriCMF\Auth\UI\BaseUIFirewall;
use OriCMF\Config\ConfigProvider;

/**
 * @method bool isLinkCurrent(string $destination = null, array $args = [])
 * @method bool isModuleCurrent(string $module)
 */
abstract class UITemplate extends Template
{

	public BaseUIFirewall $firewall;

	public string $baseUrl;

	/** @var array<mixed> */
	public array $flashes;

	public ConfigProvider $config;

	final public function __construct(Engine $latte)
	{
		parent::__construct($latte);
	}

}
