<?php declare(strict_types = 1);

namespace OriCMF\Core\Auth;

use Nette\Caching\Cache;
use OriCMF\Core\Role\RoleRepository;
use Orisai\Auth\Authorization\AuthorizationData;
use Orisai\Auth\Authorization\AuthorizationDataBuilder;

final class AuthorizationDataCreator
{

	private const CacheKey = 'data';

	/**
	 * @param array<string> $privileges
	 */
	public function __construct(
		private readonly array $privileges,
		private readonly RoleRepository $roleRepository,
		private Cache $cache,
	)
	{
		$this->cache = $cache->derive('ori_cmf.auth');
		$this->roleRepository->onFlush[] = $this->rebuild(...);
	}

	public function create(): AuthorizationData
	{
		$data = $this->cache->load(self::CacheKey);
		if ($data instanceof AuthorizationData) {
			return $data;
		}

		$data = $this->buildData();

		$this->cache->save(self::CacheKey, $data);

		return $data;
	}

	private function rebuild(): void
	{
		$data = $this->buildData();
		$this->cache->save(self::CacheKey, $data);
	}

	private function buildData(): AuthorizationData
	{
		$dataBuilder = new AuthorizationDataBuilder();
		$dataBuilder->throwOnUnknownPrivilege = false;

		foreach ($this->privileges as $privilege) {
			$dataBuilder->addPrivilege($privilege);
		}

		$roles = $this->roleRepository->findAll();

		foreach ($roles as $role) {
			$dataBuilder->addRole($role->name);

			foreach ($role->privileges as $privilege) {
				if ($privilege === '*') {
					$dataBuilder->addRoot($role->name);
				} else {
					$dataBuilder->allow($role->name, $privilege);
				}
			}
		}

		return $dataBuilder->build();
	}

}
