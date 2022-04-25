<?php declare(strict_types = 1);

namespace OriCMF\Core\Email;

use OriCMF\Core\ORM\BaseRepository;

final class EmailRepository extends BaseRepository
{

	public function __construct(EmailMapper $mapper)
	{
		parent::__construct($mapper);
	}

	public static function getEntityClassNames(): array
	{
		return [Email::class];
	}

}
