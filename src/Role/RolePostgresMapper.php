<?php declare(strict_types = 1);

namespace OriCMF\Core\Role;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class RolePostgresMapper extends DbalMapper implements RoleMapper
{

	public function getTableName(): string
	{
		return 'ori.roles';
	}

}
