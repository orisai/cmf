<?php declare(strict_types = 1);

namespace OriCMF\Front\Auth;

use OriCMF\Core\User\UserRepository;
use OriCMF\UI\Auth\BaseUIIdentityRefresher;
use OriCMF\UI\Auth\UserIdentity;
use OriCMF\UI\Auth\UserIdentityCreator;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authorization\Authorizer;

final class FrontIdentityRefresher extends BaseUIIdentityRefresher
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

		return $this->identityCreator->create(
			$this->getUser($identity),
			$impersonator,
		);
	}

}
