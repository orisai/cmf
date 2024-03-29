<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials;

use OriCMF\User\Credentials\Exception\CredentialAlreadyInUse;

interface CredentialHandler
{

	/**
	 * @throws CredentialAlreadyInUse
	 */
	public function beforeRegistration(object $credential): object|null;

}
