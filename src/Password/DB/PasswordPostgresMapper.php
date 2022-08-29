<?php declare(strict_types = 1);

namespace OriCMF\Password\DB;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class PasswordPostgresMapper extends DbalMapper implements PasswordMapper
{

	public function getTableName(): string
	{
		return 'ori_cmf.passwords';
	}

}
