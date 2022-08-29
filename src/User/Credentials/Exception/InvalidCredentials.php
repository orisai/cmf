<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials\Exception;

use Orisai\Exceptions\DomainException;
use Orisai\Localization\TranslatableMessage;

final class InvalidCredentials extends DomainException
{

	private TranslatableMessage $errorMessage;

	public static function create(TranslatableMessage $errorMessage): self
	{
		$self = new self();
		$self->errorMessage = $errorMessage;

		return $self;
	}

	public function getErrorMessage(): TranslatableMessage
	{
		return $this->errorMessage;
	}

}
