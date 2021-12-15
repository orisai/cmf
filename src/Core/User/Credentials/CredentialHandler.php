<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Credentials;

use OriCMF\Core\User\Credentials\Exception\CredentialAlreadyInUse;

interface CredentialHandler
{

	/**
	 * @throws CredentialAlreadyInUse
	 */
	public function beforeRegistration(object $credential): object|null;

}
