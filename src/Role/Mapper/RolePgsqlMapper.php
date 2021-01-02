<?php declare(strict_types = 1);

namespace OriCMF\Core\Role\Mapper;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class RolePgsqlMapper extends DbalMapper implements RoleMapper
{

	public function getTableName(): string
	{
		return 'ori.core.roles';
	}

}
