<?php declare(strict_types = 1);

namespace OriCMF\Core\Person;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Repository\IDependencyProvider;
use OriCMF\Core\ORM\BaseRepository;
use OriCMF\Core\Person\Mapper\PersonMapper;

final class PersonRepository extends BaseRepository
{

	public function __construct(PersonMapper $mapper, ?IDependencyProvider $dependencyProvider = null)
	{
		parent::__construct($mapper, $dependencyProvider);
	}

	/**
	 * @return array<int, class-string<Entity>>
	 */
	public static function getEntityClassNames(): array
	{
		return [Person::class];
	}

}
