<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials;

interface VerifyingCredentialHandler extends CredentialHandler
{

	public function requestVerification(object $credential): void;

}
