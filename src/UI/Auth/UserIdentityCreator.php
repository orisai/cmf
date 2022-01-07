<?php declare(strict_types = 1);

namespace OriCMF\UI\Auth;

use OriCMF\Core\Role\Role;
use OriCMF\Core\User\User;
use Orisai\Auth\Authorization\AuthorizationData;
use Orisai\Auth\Authorization\IdentityAuthorizationDataBuilder;
use function array_map;

final class UserIdentityCreator
{

	public function __construct(private AuthorizationData $data)
	{
	}

	public function create(User $user, UserIdentity|null $puppeteer = null): UserIdentity
	{
		$roles = array_map(
			static fn (Role $role): string => $role->name,
			$user->roles->getIterator()->fetchAll(),
		);

		$identity = new UserIdentity($user->id, $roles, $puppeteer);

		$this->setIdentityAuthData($user, $identity);

		return $identity;
	}

	private function setIdentityAuthData(User $user, UserIdentity $identity): void
	{
		$builder = new IdentityAuthorizationDataBuilder($this->data);
		foreach ($user->privileges as $privilege) {
			$builder->allow($identity, $privilege);
		}

		$identity->setAuthData($builder->build($identity));
	}

}