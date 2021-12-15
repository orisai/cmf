<?php declare(strict_types = 1);

namespace OriCMF\Core\Auth;

use OriCMF\Core\Role\RoleRepository;
use Orisai\Auth\Authorization\AuthorizationData;
use Orisai\Auth\Authorization\AuthorizationDataBuilder;

final class AuthorizationDataCreator
{

	/**
	 * @param array<string>  $privileges
	 */
	public function __construct(private array $privileges, private RoleRepository $roleRepository)
	{
	}

	public function create(): AuthorizationData
	{
		$dataBuilder = new AuthorizationDataBuilder();

		foreach ($this->privileges as $privilege) {
			$dataBuilder->addPrivilege($privilege);
		}

		$roles = $this->roleRepository->findAll();

		foreach ($roles as $role) {
			$dataBuilder->addRole($role->name);

			foreach ($role->privileges as $privilege) {
				$dataBuilder->allow($role->name, $privilege);
			}
		}

		return $dataBuilder->build();
	}

}
