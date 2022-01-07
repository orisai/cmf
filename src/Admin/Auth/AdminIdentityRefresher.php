<?php declare(strict_types = 1);

namespace OriCMF\Admin\Auth;

use OriCMF\Core\User\UserRepository;
use OriCMF\Core\User\UserState;
use OriCMF\UI\Auth\UserIdentity;
use OriCMF\UI\Auth\UserIdentityCreator;
use Orisai\Auth\Authentication\Exception\IdentityExpired;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authentication\IdentityRefresher;
use Orisai\Auth\Authorization\PrivilegeAuthorizer;
use function assert;

/**
 * @phpstan-implements IdentityRefresher<UserIdentity>
 */
final class AdminIdentityRefresher implements IdentityRefresher
{

	public function __construct(
		private UserRepository $userRepository,
		private PrivilegeAuthorizer $authorizer,
		private UserIdentityCreator $identityCreator,
	)
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

		$newIdentity = $this->identityCreator->create($user, $newPuppeteer);

		if (!$this->authorizer->isAllowed($newIdentity, 'ori.administration.entry')) {
			throw IdentityExpired::create();
		}

		return $newIdentity;
	}

}
