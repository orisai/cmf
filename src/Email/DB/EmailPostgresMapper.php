<?php declare(strict_types = 1);

namespace OriCMF\Email\DB;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class EmailPostgresMapper extends DbalMapper implements EmailMapper
{

	public function getTableName(): string
	{
		return 'ori_cmf.emails';
	}

}
