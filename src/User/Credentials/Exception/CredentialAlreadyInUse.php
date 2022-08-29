<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials\Exception;

use Orisai\Exceptions\DomainException;
use Orisai\Localization\TranslatableMessage;

final class CredentialAlreadyInUse extends DomainException
{

	private object $credential;

	private TranslatableMessage|null $errorMessage;

	public static function create(object $credential, TranslatableMessage|null $errorMessage = null): self
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

	public function getErrorMessage(): TranslatableMessage|null
	{
		return $this->errorMessage;
	}

}
