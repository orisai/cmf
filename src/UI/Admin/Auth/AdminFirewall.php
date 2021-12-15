<?php declare(strict_types = 1);

namespace OriCMF\UI\Admin\Auth;

use OriCMF\UI\Auth\BaseUIFirewall;

final class AdminFirewall extends BaseUIFirewall
{

	protected function getNamespace(): string
	{
		return 'admin';
	}

}
