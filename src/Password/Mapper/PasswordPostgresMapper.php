<?php declare(strict_types = 1);

namespace OriCMF\Core\Password\Mapper;

use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class PasswordPostgresMapper extends DbalMapper implements PasswordMapper
{

	public function getTableName(): string
	{
		return 'ori.passwords';
	}

}
