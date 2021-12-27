<?php declare(strict_types = 1);

namespace OriCMF\Front\Auth;

use OriCMF\Core\User\UserRepository;
use OriCMF\Core\User\UserState;
use OriCMF\UI\Auth\UserIdentity;
use Orisai\Auth\Authentication\Exception\IdentityExpired;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authentication\IdentityRefresher;
use function assert;

/**
 * @phpstan-implements IdentityRefresher<UserIdentity>
 */
final class FrontIdentityRefresher implements IdentityRefresher
{

	public function __construct(private UserRepository $userRepository)
	{
	}

	public function refresh(Identity $identity): UserIdentity
	{
		assert($identity instanceof UserIdentity);

		$user = $this->userRepository->getBy([
			'id' => $identity->getId(),
			'state' => UserState::ACTIVE(),
		]);

		if ($user === null) {
			throw IdentityExpired::create();
		}

		$puppeteer = $identity->getPuppeteer();
		$newPuppeteer = $puppeteer !== null
			? $this->refresh($puppeteer)
			: null;

		// User was controlled by puppeteer which is not available anymore
		if ($puppeteer !== null && $newPuppeteer === null) {
			throw IdentityExpired::create();
		}

		return UserIdentity::fromUser($user, $newPuppeteer);
	}

}
