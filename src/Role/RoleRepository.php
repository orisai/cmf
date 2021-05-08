<?php declare(strict_types = 1);

namespace OriCMF\Core\Role;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Repository\IDependencyProvider;
use OriCMF\Core\ORM\BaseRepository;
use OriCMF\Core\Role\Mapper\RoleMapper;

final class RoleRepository extends BaseRepository
{

	public function __construct(RoleMapper $mapper, IDependencyProvider|null $dependencyProvider = null)
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
