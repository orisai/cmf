<?php declare(strict_types = 1);

namespace OriCMF\Core\Person\Mapper;

use Nextras\Orm\Entity\Reflection\PropertyMetadata;
use Nextras\Orm\Mapper\Dbal\DbalMapper;

final class PersonPostgresMapper extends DbalMapper implements PersonMapper
{

	public function getTableName(): string
	{
		return 'ori.people';
	}

	/**
	 * @return array{string,array{string,string}}
	 */
	public function getManyHasManyParameters(PropertyMetadata $sourceProperty, DbalMapper $targetMapper): array
	{
		if ($sourceProperty->name === 'roles') {
			return ['ori.person_roles', ['person_id', 'role_id']];
		}

		return parent::getManyHasManyParameters($sourceProperty, $targetMapper);
	}

}
