<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Login;

use Nextras\Orm\Model\IModel;
use OriCMF\Core\Email\EmailRepository;
use OriCMF\Core\Password\PasswordRepository;
use OriCMF\Core\User\Credentials\Exception\InactiveAccount;
use OriCMF\Core\User\Credentials\Exception\InvalidCredentials;
use OriCMF\Core\User\User;
use OriCMF\Core\User\UserState;
use Orisai\Auth\Passwords\PasswordEncoder;
use Orisai\Localization\TranslatableMessage;

final class LoginVerifier
{

	public function __construct(
		private IModel $model,
		private EmailRepository $emailRepository,
		private PasswordRepository $passwordRepository,
		private PasswordEncoder $passwordEncoder,
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

		if (!$this->passwordEncoder->isValid($rawPassword, $password->encodedPassword)) {
			$this->throwInvalidCredentials();
		}

		// TODO - update also in re-verifier
		if ($this->passwordEncoder->needsReEncode($password->encodedPassword)) {
			$password->encodedPassword = $this->passwordEncoder->encode($rawPassword);
			$this->model->persistAndFlush($password);
		}

		return $user;
	}

	/**
	 * @return never
	 * @throws InvalidCredentials
	 */
	private function throwInvalidCredentials(): void
	{
		throw InvalidCredentials::create(new TranslatableMessage('ori.login.invalidCredentials'));
	}

	/**
	 * @throws InactiveAccount
	 */
	private function verifyActiveAccount(User $user): void
	{
		if ($user->state->is(UserState::ACTIVE())) {
			return;
		}

		if ($user->state->is(UserState::NEW())) {
			throw InactiveAccount::create($user, new TranslatableMessage('ori.login.inactive.newAccount'));
		}

		throw InactiveAccount::create($user, new TranslatableMessage('ori.login.inactive.deactivatedAccount'));
	}

}
