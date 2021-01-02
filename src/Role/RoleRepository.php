<?php declare(strict_types = 1);

namespace OriCMF\Core\Role;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Repository\IDependencyProvider;
use Nextras\Orm\Repository\Repository;
use OriCMF\Core\Role\Mapper\RoleMapper;

final class RoleRepository extends Repository
{

	public function __construct(RoleMapper $mapper, ?IDependencyProvider $dependencyProvider = null)
	{
		parent::__construct($mapper, $dependencyProvider);
	}

	/**
	 * @return array<int, class-string<Entity>>
	 */
	public static function getEntityClassNames(): array
	{
		return [Role::class];
	}

}
