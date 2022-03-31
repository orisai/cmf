<?php declare(strict_types = 1);

namespace OriCMF\Admin\Auth;

use OriCMF\Core\User\UserRepository;
use OriCMF\UI\Auth\BaseUIIdentityRefresher;
use OriCMF\UI\Auth\UserIdentity;
use OriCMF\UI\Auth\UserIdentityCreator;
use Orisai\Auth\Authentication\Exception\IdentityExpired;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authorization\Authorizer;

final class AdminIdentityRefresher extends BaseUIIdentityRefresher
{

	public function __construct(
		UserRepository $userRepository,
		private Authorizer $authorizer,
		private UserIdentityCreator $identityCreator,
	)
	{
		parent::__construct($userRepository);
	}

	public function refresh(Identity $identity): UserIdentity
	{
		$impersonator = $this->refreshImpersonator($identity);

		// Impersonator is no longer allowed to impersonate, return user to own identity
		if ($impersonator !== null && !$this->authorizer->isAllowed($impersonator, Authorizer::ROOT_PRIVILEGE)) {
			return $impersonator;
		}

		$newIdentity = $this->identityCreator->create(
			$this->getUser($identity),
			$impersonator,
		);

		if (!$this->authorizer->isAllowed($newIdentity, 'ori.cmf.admin.entry')) {
			// Controlled user is not allowed to entry administration, return impersonator to their identity
			if ($impersonator !== null) {
				return $impersonator;
			}

			throw IdentityExpired::create();
		}

		return $newIdentity;
	}

}
