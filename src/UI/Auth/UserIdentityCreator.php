<?php declare(strict_types = 1);

namespace OriCMF\UI\Auth;

use OriCMF\Core\Role\Role;
use OriCMF\Core\User\User;
use Orisai\Auth\Authorization\AuthorizationData;
use Orisai\Auth\Authorization\IdentityAuthorizationDataBuilder;
use function array_map;

final class UserIdentityCreator
{

	public function __construct(private readonly AuthorizationData $data)
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

		$builder = new IdentityAuthorizationDataBuilder($this->data);
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
