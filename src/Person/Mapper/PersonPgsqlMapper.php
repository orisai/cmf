<?php declare(strict_types = 1);

namespace OriCMF\Core\Person\Mapper;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class PersonPgsqlMapper extends DbalMapper implements PersonMapper
{

	public function getTableName(): string
	{
		return 'ori_core.people';
	}

}
