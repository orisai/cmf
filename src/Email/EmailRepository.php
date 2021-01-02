<?php declare(strict_types = 1);

namespace OriCMF\Core\Email;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Repository\IDependencyProvider;
use Nextras\Orm\Repository\Repository;
use OriCMF\Core\Email\Mapper\EmailMapper;

final class EmailRepository extends Repository
{

	public function __construct(EmailMapper $mapper, ?IDependencyProvider $dependencyProvider = null)
	{
		parent::__construct($mapper, $dependencyProvider);
	}

	/**
	 * @return array<int, class-string<Entity>>
	 */
	public static function getEntityClassNames(): array
	{
		return [Email::class];
	}

}
