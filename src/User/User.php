<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use OriCMF\Core\Person\Person;
use OriCMF\Core\Role\Role;
use Symfony\Component\Uid\Ulid;

/**
 * @property-read string                  $id {primary}
 * @property-read DateTimeImmutable       $createdAt {default now}
 * @property-read Person                  $person {1:1 Person::$user, isMain=true, cascade=[persist]}
 * @property-read ManyHasMany&array<Role> $roles {m:m Role, isMain=true, oneSided=true, cascade=[persist]}
 */
final class User extends Entity
{

	public function __construct(Person $person)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->setReadOnlyValue('person', $person);
	}

}
