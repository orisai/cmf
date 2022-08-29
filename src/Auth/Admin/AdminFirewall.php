<?php declare(strict_types = 1);

namespace OriCMF\Auth\Admin;

use OriCMF\Auth\UI\BaseUIFirewall;

final class AdminFirewall extends BaseUIFirewall
{

	public function getNamespace(): string
	{
		return 'admin';
	}

}
