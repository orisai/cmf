<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Repository\IDependencyProvider;
use OriCMF\Core\ORM\BaseRepository;
use OriCMF\Core\ORM\Functions\JsonAnyKeyOrValueExistsFunction;
use OriCMF\Core\User\Mapper\UserMapper;
use Orisai\Auth\Authorization\Authorizer;
use function explode;

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

	/**
	 * @return ICollection&iterable<User>
	 */
	public function findByPrivilege(string $privilege, bool $includePowerUser = true): ICollection
	{
		return $this->findBy([
			JsonAnyKeyOrValueExistsFunction::class,
			'roles->privileges',
			$this->getPrivilegeParents($privilege, $includePowerUser),
		]);
	}

	/**
	 * @return array<string>
	 */
	private function getPrivilegeParents(string $privilege, bool $includePowerUser): array
	{
		$all = [];

		if ($includePowerUser) {
			$all[] = Authorizer::ALL_PRIVILEGES;
		}

		$parts = explode('.', $privilege);
		$current = null;
		foreach ($parts as $part) {
			$current = $current === null ? $part : "{$current}.{$part}";
			$all[] = $current;
		}

		return $all;
	}

}
