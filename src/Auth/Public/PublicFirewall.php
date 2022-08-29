<?php declare(strict_types = 1);

namespace OriCMF\Auth\Public;

use OriCMF\Auth\UI\BaseUIFirewall;

final class PublicFirewall extends BaseUIFirewall
{

	public function getNamespace(): string
	{
		return 'public';
	}

}
