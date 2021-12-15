<?php declare(strict_types = 1);

namespace OriCMF\UI\Front\Auth;

use OriCMF\Core\User\UserRepository;
use OriCMF\Core\User\UserState;
use OriCMF\UI\Auth\UserIdentity;
use Orisai\Auth\Authentication\Exception\IdentityExpired;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authentication\IdentityRenewer;
use function assert;

/**
 * @phpstan-implements IdentityRenewer<UserIdentity>
 */
final class FrontIdentityRenewer implements IdentityRenewer
{

	public function __construct(private UserRepository $userRepository)
	{
	}

	public function renewIdentity(Identity $identity): UserIdentity
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
			? $this->renewIdentity($puppeteer)
			: null;

		// User was controlled by puppeteer which is not available anymore
		if ($puppeteer !== null && $newPuppeteer === null) {
			throw IdentityExpired::create();
		}

		return UserIdentity::fromUser($user, $newPuppeteer);
	}

}
