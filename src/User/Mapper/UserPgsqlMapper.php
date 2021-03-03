<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Mapper;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class UserPgsqlMapper extends DbalMapper implements UserMapper
{

	public function getTableName(): string
	{
		return 'ori.users';
	}

}
