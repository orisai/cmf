<?php declare(strict_types = 1);

namespace OriCMF\Core\Role;

use OriCMF\Core\ORM\BaseRepository;

final class RoleRepository extends BaseRepository
{

	public function __construct(RoleMapper $mapper)
	{
		parent::__construct($mapper);
	}

	public static function getEntityClassNames(): array
	{
		return [Role::class];
	}

}
