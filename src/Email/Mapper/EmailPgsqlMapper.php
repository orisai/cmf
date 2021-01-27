<?php declare(strict_types = 1);

namespace OriCMF\Core\Email\Mapper;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class EmailPgsqlMapper extends DbalMapper implements EmailMapper
{

	public function getTableName(): string
	{
		return 'ori_core.emails';
	}

}
