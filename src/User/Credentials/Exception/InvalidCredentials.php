<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials\Exception;

use Orisai\Exceptions\DomainException;
use Orisai\TranslationContracts\Translatable;

final class InvalidCredentials extends DomainException
{

	private Translatable $errorMessage;

	public static function create(Translatable $errorMessage): self
	{
		$self = new self();
		$self->errorMessage = $errorMessage;

		return $self;
	}

	public function getErrorMessage(): Translatable
	{
		return $this->errorMessage;
	}

}
