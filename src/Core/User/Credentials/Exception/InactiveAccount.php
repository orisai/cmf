<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Credentials\Exception;

use OriCMF\Core\User\User;
use Orisai\Exceptions\DomainException;
use Orisai\Localization\TranslatableMessage;

final class InactiveAccount extends DomainException
{

	private User $user;

	private TranslatableMessage $errorMessage;

	public static function create(User $user, TranslatableMessage $errorMessage): self
	{
		$self = new self();
		$self->user = $user;
		$self->errorMessage = $errorMessage;

		return $self;
	}

	public function getErrorMessage(): TranslatableMessage
	{
		return $this->errorMessage;
	}

	public function getUser(): User
	{
		return $this->user;
	}

}
