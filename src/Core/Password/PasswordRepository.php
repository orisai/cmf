<?php declare(strict_types = 1);

namespace OriCMF\Core\Password;

use Nextras\Orm\Entity\Entity;
use OriCMF\Core\ORM\BaseRepository;

final class PasswordRepository extends BaseRepository
{

	public function __construct(PasswordMapper $mapper)
	{
		parent::__construct($mapper);
	}

	/**
	 * @return array<int, class-string<Entity>>
	 */
	public static function getEntityClassNames(): array
	{
		return [Password::class];
	}

}
