<?php declare(strict_types = 1);

namespace OriCMF\Registration\Logic;

interface UserRegistrarGetter
{

	public function get(): UserRegistrar;

}
