<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials\Exception;

use Orisai\Exceptions\DomainException;
use Orisai\TranslationContracts\Translatable;

final class CredentialAlreadyInUse extends DomainException
{

	private object $credential;

	private Translatable|null $errorMessage;

	public static function create(object $credential, Translatable|null $errorMessage = null): self
	{
		$self = new self();
		$self->credential = $credential;
		$self->errorMessage = $errorMessage;

		return $self;
	}

	public function getCredential(): object
	{
		return $this->credential;
	}

	public function getErrorMessage(): Translatable|null
	{
		return $this->errorMessage;
	}

}
