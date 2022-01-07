<?php declare(strict_types = 1);

namespace OriCMF\Admin\Auth;

use OriCMF\Core\User\UserRepository;
use OriCMF\UI\Auth\BaseUIIdentityRefresher;
use OriCMF\UI\Auth\UserIdentity;
use OriCMF\UI\Auth\UserIdentityCreator;
use Orisai\Auth\Authentication\Exception\IdentityExpired;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authorization\PrivilegeAuthorizer;

final class AdminIdentityRefresher extends BaseUIIdentityRefresher
{

	public function __construct(
		UserRepository $userRepository,
		private PrivilegeAuthorizer $authorizer,
		private UserIdentityCreator $identityCreator,
	)
	{
		parent::__construct($userRepository);
	}

	public function refresh(Identity $identity): UserIdentity
	{
		$newIdentity = $this->identityCreator->create(
			$this->getUser($identity),
			$this->refreshPuppeteer($identity),
		);

		if (!$this->authorizer->isAllowed($newIdentity, 'ori.administration.entry')) {
			throw IdentityExpired::create();
		}

		return $newIdentity;
	}

}
