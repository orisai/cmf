<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Mapper;

use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Mapper\Dbal\DbalMapper;
use Nextras\Orm\Mapper\IMapper;
use OriCMF\Core\Role\Mapper\RoleMapper;
use function assert;

final class UserPgsqlMapper extends DbalMapper implements UserMapper
{

	public function getTableName(): string
	{
		return 'ori_core.users';
	}

	/**
	 * @return array{string,array{string,string}}
	 */
	public function getManyHasManyParameters(PropertyMetadata $sourceProperty, IMapper $targetMapper): array
	{
		if ($targetMapper instanceof RoleMapper) {
			return ['ori_core.user_roles', ['user_id', 'role_id']];
		}

		assert($targetMapper instanceof DbalMapper);

		return parent::getManyHasManyParameters($sourceProperty, $targetMapper);
	}

}
