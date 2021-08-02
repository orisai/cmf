<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Register;

interface UserRegistrarGetter
{

	public function get(): UserRegistrar;

}
