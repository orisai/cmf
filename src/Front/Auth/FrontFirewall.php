<?php declare(strict_types = 1);

namespace OriCMF\Front\Auth;

use OriCMF\UI\Auth\BaseUIFirewall;

final class FrontFirewall extends BaseUIFirewall
{

	protected function getNamespace(): string
	{
		return 'front';
	}

}