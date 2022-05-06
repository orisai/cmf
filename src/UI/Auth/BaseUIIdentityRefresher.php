<?php declare(strict_types = 1);

namespace OriCMF\UI\Auth;

use OriCMF\Core\User\User;
use OriCMF\Core\User\UserRepository;
use OriCMF\Core\User\UserState;
use Orisai\Auth\Authentication\Exception\IdentityExpired;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authentication\IdentityRefresher;

/**
 * @phpstan-implements IdentityRefresher<UserIdentity>
 *
 * @internal
 */
abstract class BaseUIIdentityRefresher implements IdentityRefresher
{

	public function __construct(private UserRepository $userRepository)
	{
	}

	/**
	 * @throws IdentityExpired
	 */
	protected function getUser(Identity $identity): User
	{
		$user = $this->userRepository->getBy([
			'id' => $identity->getId(),
			'state' => UserState::Active(),
		]);

		return $user ?? throw IdentityExpired::create();
	}

	/**
	 * WARNING: Null has to be returned for no impersonator only.
	 *          Returning null for missing impersonator privileges would
	 *          cause impersonator to stay logged in current identity and
	 *          forget about their original (own) identity.
	 *
	 * @throws IdentityExpired
	 */
	protected function refreshImpersonator(Identity $identity): UserIdentity|null
	{
		if (!$identity instanceof UserIdentity) {
			return null;
		}

		$impersonator = $identity->getImpersonator();
		if ($impersonator === null) {
			return null;
		}

		// If impersonator is not available anymore, exception is thrown
		return $this->refresh($impersonator);
	}

}
