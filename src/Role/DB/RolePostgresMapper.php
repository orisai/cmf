<?php declare(strict_types = 1);

namespace OriCMF\Role\DB;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class RolePostgresMapper extends DbalMapper implements RoleMapper
{

	public function getTableName(): string
	{
		return 'ori_cmf.roles';
	}

}
