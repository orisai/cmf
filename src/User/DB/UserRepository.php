<?php declare(strict_types = 1);

namespace OriCMF\User\DB;

use Nextras\Orm\Collection\ICollection;
use OriCMF\Auth\Logic\AuthorizationDataCreator;
use OriCMF\Orm\BaseRepository;
use OriCMF\Orm\Functions\JsonAnyKeyOrValueExistsFunction;
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
	public function findByPrivilege(string $privilege, bool $includeRoot = true): ICollection
	{
		$parents = PrivilegeProcessor::getPrivilegeParents($privilege);

		return $this->findBy([
			JsonAnyKeyOrValueExistsFunction::class,
			'roles->privileges',
			$includeRoot ? [AuthorizationDataCreator::RootPrivilege] + $parents : $parents,
		]);
	}

}
