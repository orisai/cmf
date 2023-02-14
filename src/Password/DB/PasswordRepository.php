<?php declare(strict_types = 1);

namespace OriCMF\Password\DB;

use OriCMF\Orm\BaseRepository;

final class PasswordRepository extends BaseRepository
{

	public function __construct(PasswordMapper $mapper)
	{
		parent::__construct($mapper);
	}

	public static function getEntityClassNames(): array
	{
		return [Password::class];
	}

}
