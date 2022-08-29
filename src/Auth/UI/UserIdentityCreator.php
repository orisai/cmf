<?php declare(strict_types = 1);

namespace OriCMF\Auth\UI;

use OriCMF\Role\DB\Role;
use OriCMF\User\DB\User;
use Orisai\Auth\Authorization\Authorizer;
use Orisai\Auth\Authorization\IdentityAuthorizationDataBuilder;
use function array_map;

final class UserIdentityCreator
{

	public function __construct(private readonly Authorizer $authorizer)
	{
	}

	public function create(User $user, UserIdentity|null $impersonator = null): UserIdentity
	{
		$roles = array_map(
			static fn (Role $role): string => $role->name,
			$user->roles->getIterator()->fetchAll(),
		);

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
			if ($privilege === '*') {
				$builder->addRoot($identity);
			} else {
				$builder->allow($identity, $privilege);
			}
		}

		$identity->setAuthorizationData($builder->build($identity));
	}

}
