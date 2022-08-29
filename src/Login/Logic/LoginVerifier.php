<?php declare(strict_types = 1);

namespace OriCMF\Login\Logic;

use Nextras\Orm\Model\IModel;
use OriCMF\Email\DB\EmailRepository;
use OriCMF\Password\DB\PasswordRepository;
use OriCMF\User\Credentials\Exception\InactiveAccount;
use OriCMF\User\Credentials\Exception\InvalidCredentials;
use OriCMF\User\DB\User;
use OriCMF\User\DB\UserState;
use Orisai\Auth\Passwords\PasswordHasher;
use Orisai\Localization\TranslatableMessage;

final class LoginVerifier
{

	public function __construct(
		private readonly IModel $model,
		private readonly EmailRepository $emailRepository,
		private readonly PasswordRepository $passwordRepository,
		private readonly PasswordHasher $passwordHasher,
	)
	{
	}

	/**
	 * @throws InvalidCredentials
	 * @throws InactiveAccount
	 *
	 * @todo - specific + general message
	 * @todo - interface for getting user and interface for verification
	 *       - or verifier for each usecase
	 */
	public function verify(string $emailAddress, string $rawPassword): User
	{
		$user = $this->verifyCredentials($emailAddress, $rawPassword);
		$this->verifyActiveAccount($user);

		return $user;
	}

	/**
	 * @throws InvalidCredentials
	 */
	private function verifyCredentials(string $emailAddress, string $rawPassword): User
	{
		$email = $this->emailRepository->getBy(['emailAddress' => $emailAddress]);
		if ($email === null) {
			$this->throwInvalidCredentials();
		}

		$user = $email->user;

		$password = $this->passwordRepository->getBy(['user' => $user]);
		if ($password === null) {
			// User have no password, shouldn't be able to use this login method
			//TODO - log incorrect login method usage?
			$this->throwInvalidCredentials();
		}

		if (!$this->passwordHasher->isValid($rawPassword, $password->encodedPassword)) {
			$this->throwInvalidCredentials();
		}

		// TODO - update also in re-verifier
		if ($this->passwordHasher->needsRehash($password->encodedPassword)) {
			$password->encodedPassword = $this->passwordHasher->hash($rawPassword);
			$this->model->persistAndFlush($password);
		}

		return $user;
	}

	/**
	 * @throws InvalidCredentials
	 */
	private function throwInvalidCredentials(): never
	{
		throw InvalidCredentials::create(new TranslatableMessage('ori.cmf.login.invalidCredentials'));
	}

	/**
	 * @throws InactiveAccount
	 */
	private function verifyActiveAccount(User $user): void
	{
		if ($user->state === UserState::Active) {
			return;
		}

		if ($user->state === UserState::New) {
			throw InactiveAccount::create($user, new TranslatableMessage('ori.cmf.login.inactive.newAccount'));
		}

		throw InactiveAccount::create($user, new TranslatableMessage('ori.cmf.login.inactive.deactivatedAccount'));
	}

}
