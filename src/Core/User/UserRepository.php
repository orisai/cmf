<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use OriCMF\Core\ORM\BaseRepository;
use OriCMF\Core\ORM\Functions\JsonAnyKeyOrValueExistsFunction;
use Orisai\Auth\Authorization\PrivilegeProcessor;

final class UserRepository extends BaseRepository
{

	public function __construct(UserMapper $mapper)
	{
		parent::__construct($mapper);
	}

	/**
	 * @return array<int, class-string<Entity>>
	 */
	public static function getEntityClassNames(): array
	{
		return [User::class];
	}

	/**
	 * @return ICollection&iterable<User>
	 */
	public function findByPrivilege(string $privilege, bool $includePowerUser = true): ICollection
	{
		return $this->findBy([
			JsonAnyKeyOrValueExistsFunction::class,
			'roles->privileges',
			PrivilegeProcessor::getPrivilegeParents($privilege, $includePowerUser),
		]);
	}

}
