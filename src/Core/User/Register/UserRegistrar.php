<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Register;

use Nextras\Orm\Model\IModel;
use OriCMF\Core\User\Credentials\CredentialHandler;
use OriCMF\Core\User\Credentials\CredentialHandlerManager;
use OriCMF\Core\User\Credentials\Credentials;
use OriCMF\Core\User\Credentials\Exception\CredentialAlreadyInUse;
use OriCMF\Core\User\Credentials\VerifyingCredentialHandler;
use OriCMF\Core\User\User;
use OriCMF\Core\User\UserState;
use Orisai\Exceptions\Logic\InvalidState;

final class UserRegistrar
{

	public function __construct(
		private IModel $model,
		private CredentialHandlerManager $credentialHandlerManager,
	)
	{
	}

	/**
	 * @throws CredentialAlreadyInUse
	 */
	public function register(User $user, Credentials $credentials): void
	{
		$credentialsList = $credentials->getCredentials();
		$handlers = $this->credentialHandlerManager->getHandlers();

		try {
			$this->beforeRegistration($credentialsList, $handlers);
		} catch (CredentialAlreadyInUse $exception) {
			throw $exception;
		}

		if (!$credentials->verifyCredentialsOwner) {
			$user->state = UserState::Active();
		}

		$this->model->persistAndFlush($user);

		if ($credentials->verifyCredentialsOwner) {
			$this->requestVerification($credentialsList, $handlers);
		}
	}

	/**
	 * @param array<object>            $credentials
	 * @param array<CredentialHandler> $handlers
	 * @throws CredentialAlreadyInUse
	 */
	private function beforeRegistration(array $credentials, array $handlers): void
	{
		foreach ($credentials as $credential) {
			foreach ($handlers as $handler) {
				$processedCredential = $handler->beforeRegistration($credential);

				if ($processedCredential !== null) {
					continue 2;
				}
			}

			$credentialType = $credential::class;

			throw InvalidState::create()
				->withMessage("None of the credential handlers was able to handle object of type {$credentialType}.");
		}
	}

	/**
	 * @param array<object>            $credentials
	 * @param array<CredentialHandler> $handlers
	 */
	private function requestVerification(array $credentials, array $handlers): void
	{
		foreach ($credentials as $credential) {
			foreach ($handlers as $handler) {
				if (!$handler instanceof VerifyingCredentialHandler) {
					continue;
				}

				$handler->requestVerification($credential);
			}
		}
	}

}
