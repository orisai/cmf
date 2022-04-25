<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use Nextras\Orm\Collection\ICollection;
use OriCMF\Core\ORM\BaseRepository;
use OriCMF\Core\ORM\Functions\JsonAnyKeyOrValueExistsFunction;
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
