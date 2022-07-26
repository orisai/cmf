<?php declare(strict_types = 1);

namespace OriCMF\Core\Auth;

use Nette\Caching\Cache;
use OriCMF\Core\Role\RoleRepository;
use Orisai\Auth\Authorization\AuthorizationData;
use Orisai\Auth\Authorization\AuthorizationDataBuilder;
use Orisai\Auth\Authorization\AuthorizationDataCreator as AuthorizationDataCreatorInterface;

final class AuthorizationDataCreator implements AuthorizationDataCreatorInterface
{

	/**
	 * @param array<string> $privileges
	 */
	public function __construct(
		private readonly array $privileges,
		private readonly RoleRepository $roleRepository,
		private Cache $cache,
		private readonly string $containerName,
		private readonly bool $debugMode,
	)
	{
		$this->cache = $cache->derive('ori_cmf.auth');
		$this->roleRepository->onFlush[] = $this->rebuild(...);
	}

	public function create(): AuthorizationData
	{
		$data = $this->cache->load($this->getCacheKey());
		if ($data instanceof AuthorizationData) {
			return $data;
		}

		$data = $this->buildData();

		$this->cache->save($this->getCacheKey(), $data, [
			Cache::Expire => $this->debugMode ? '1 day' : null,
		]);

		return $data;
	}

	private function rebuild(): void
	{
		$data = $this->buildData();
		$this->cache->save($this->getCacheKey(), $data);
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

	private function getCacheKey(): string
	{
		return 'data.' . $this->containerName;
	}

}
