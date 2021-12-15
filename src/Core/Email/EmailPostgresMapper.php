<?php declare(strict_types = 1);

namespace OriCMF\Core\Email;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class EmailPostgresMapper extends DbalMapper implements EmailMapper
{

	public function getTableName(): string
	{
		return 'ori.emails';
	}

}
