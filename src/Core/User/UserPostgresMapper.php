<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class UserPostgresMapper extends DbalMapper implements UserMapper
{

	public function getTableName(): string
	{
		return 'ori.users';
	}

	/**
	 * @return array{string,array{string,string}}
	 */
	public function getManyHasManyParameters(PropertyMetadata $sourceProperty, DbalMapper $targetMapper): array
	{
		if ($sourceProperty->name === 'roles') {
			return ['ori.user_roles', ['user_id', 'role_id']];
		}

		return parent::getManyHasManyParameters($sourceProperty, $targetMapper);
	}

}
