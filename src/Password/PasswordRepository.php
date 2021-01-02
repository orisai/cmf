<?php declare(strict_types = 1);

namespace OriCMF\Core\Password;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Repository\IDependencyProvider;
use Nextras\Orm\Repository\Repository;
use OriCMF\Core\Password\Mapper\PasswordMapper;

final class PasswordRepository extends Repository
{

	public function __construct(PasswordMapper $mapper, ?IDependencyProvider $dependencyProvider = null)
	{
		parent::__construct($mapper, $dependencyProvider);
	}

	/**
	 * @return array<int, class-string<Entity>>
	 */
	public static function getEntityClassNames(): array
	{
		return [Password::class];
	}

}
