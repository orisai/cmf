<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Repository\IDependencyProvider;
use OriCMF\Core\ORM\BaseRepository;
use OriCMF\Core\User\Mapper\UserMapper;

final class UserRepository extends BaseRepository
{

	public function __construct(UserMapper $mapper, ?IDependencyProvider $dependencyProvider = null)
	{
		parent::__construct($mapper, $dependencyProvider);
	}

	/**
	 * @return array<int, class-string<Entity>>
	 */
	public static function getEntityClassNames(): array
	{
		return [User::class];
	}

}
