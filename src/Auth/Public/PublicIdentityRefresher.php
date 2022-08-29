<?php declare(strict_types = 1);

namespace OriCMF\Auth\Public;

use OriCMF\Auth\UI\BaseUIIdentityRefresher;
use OriCMF\Auth\UI\UserIdentity;
use OriCMF\Auth\UI\UserIdentityCreator;
use OriCMF\User\DB\UserRepository;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authorization\Authorizer;

final class PublicIdentityRefresher extends BaseUIIdentityRefresher
{

	public function __construct(
		UserRepository $userRepository,
		private readonly Authorizer $authorizer,
		private readonly UserIdentityCreator $identityCreator,
	)
	{
		parent::__construct($userRepository);
	}

	public function refresh(Identity $identity): UserIdentity
	{
		$impersonator = $this->refreshImpersonator($identity);

		// Impersonator is no longer allowed to impersonate, return user to own identity
		if ($impersonator !== null && !$this->authorizer->isRoot($impersonator)) {
			return $impersonator;
		}

		return $this->identityCreator->create(
			$this->getUser($identity),
			$impersonator,
		);
	}

}
