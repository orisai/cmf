<?php declare(strict_types = 1);

namespace OriCMF\Core\Role;

use Nextras\Orm\Entity\Entity;
use OriCMF\Core\ORM\BaseRepository;

final class RoleRepository extends BaseRepository
{

	public function __construct(RoleMapper $mapper)
	{
		parent::__construct($mapper);
	}

	/**
	 * @return array<int, class-string<Entity>>
	 */
	public static function getEntityClassNames(): array
	{
		return [Role::class];
	}

}
