<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Credentials;

interface VerifyingCredentialHandler extends CredentialHandler
{

	public function requestVerification(object $credential): void;

}
