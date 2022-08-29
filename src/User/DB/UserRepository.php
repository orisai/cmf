<?php declare(strict_types = 1);

namespace OriCMF\User\DB;

use Nextras\Orm\Collection\ICollection;
use OriCMF\ORM\BaseRepository;
use OriCMF\ORM\Functions\JsonAnyKeyOrValueExistsFunction;
use Orisai\Auth\Authorization\PrivilegeProcessor;

final class UserRepository extends BaseRepository
{

	public function __construct(UserMapper $mapper)
	{
		parent::__construct($mapper);
	}

	public static function getEntityClassNames(): array
	{
		return [User::class];
	}

	/**
	 * @return ICollection&iterable<User>
	 */
	public function findByPrivilege(string $privilege, bool $includePowerUser = true): ICollection
	{
		$parents = PrivilegeProcessor::getPrivilegeParents($privilege);

		return $this->findBy([
			JsonAnyKeyOrValueExistsFunction::class,
			'roles->privileges',
			$includePowerUser ? ['*'] + $parents : $parents,
		]);
	}

}
