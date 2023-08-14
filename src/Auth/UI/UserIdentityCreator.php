<?php declare(strict_types = 1);

namespace OriCMF\Auth\UI;

use OriCMF\Auth\Logic\AuthorizationDataCreator;
use OriCMF\User\DB\User;
use Orisai\Auth\Authorization\Authorizer;
use Orisai\Auth\Authorization\IdentityAuthorizationDataBuilder;

final class UserIdentityCreator
{

	public function __construct(private readonly Authorizer $authorizer)
	{
	}

	public function create(User $user, UserIdentity|null $impersonator = null): UserIdentity
	{
		$roles = [];
		foreach ($user->roles as $role) {
			$roles[] = $role->name;
		}

		$identity = new UserIdentity($user->id, $roles, $impersonator);

		$this->setIdentityAuthData($user, $identity);

		return $identity;
	}

	private function setIdentityAuthData(User $user, UserIdentity $identity): void
	{
		if ($user->privileges === []) {
			return;
		}

		$builder = new IdentityAuthorizationDataBuilder($this->authorizer->getData());
		foreach ($user->privileges as $privilege) {
			if ($privilege === AuthorizationDataCreator::RootPrivilege) {
				$builder->addRoot($identity);
			} else {
				$builder->allow($identity, $privilege);
			}
		}

		$identity->setAuthorizationData($builder->build($identity));
	}

}
