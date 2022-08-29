<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials;

final class Credentials
{

	public bool $verifyCredentialsOwner = true;

	/** @var array<object> */
	private array $credentials = [];

	public function addCredential(object $credential): void
	{
		$this->credentials[] = $credential;
	}

	/**
	 * @return array<object>
	 */
	public function getCredentials(): array
	{
		return $this->credentials;
	}

}
