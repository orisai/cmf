<?php declare(strict_types = 1);

namespace OriCMF\Core\Email;

use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Repository\IDependencyProvider;
use OriCMF\Core\Email\Mapper\EmailMapper;
use OriCMF\Core\ORM\BaseRepository;

final class EmailRepository extends BaseRepository
{

	public function __construct(EmailMapper $mapper, IDependencyProvider|null $dependencyProvider = null)
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
