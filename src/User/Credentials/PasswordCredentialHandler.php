<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials;

use Nextras\Orm\Model\IModel;
use OriCMF\Password\DB\Password;
use OriCMF\Password\DB\PasswordRepository;
use Orisai\Exceptions\Logic\InvalidState;

final class PasswordCredentialHandler implements CredentialHandler
{

	public function __construct(private readonly IModel $model, private readonly PasswordRepository $passwordRepository)
	{
	}

	public function beforeRegistration(object $credential): object|null
	{
		if (!$credential instanceof Password) {
			return null;
		}

		$existing = $this->passwordRepository->getBy(['user->id' => $credential->user->id]);

		if ($existing !== null && $existing->id !== $credential->id) {
			throw InvalidState::create()
				->withMessage("User {$credential->user->id} already has a password.");
		}

		$this->model->persist($credential);

		return $credential;
	}

}
