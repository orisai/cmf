<?php declare(strict_types = 1);

namespace OriCMF\Core\Email;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\Core\Person\Person;
use Symfony\Component\Uid\Ulid;

/**
 * @property-read string            $id {primary}
 * @property-read DateTimeImmutable $createdAt {default now}
 * @property string                 $email
 * @property bool                   $isPrimary
 * @property-read Person            $person {1:1 Person, isMain=true, oneSided=true, cascade=[persist]}
 */
final class Email extends Entity
{

	public function __construct(string $email, Person $person)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->email = $email;
		$this->isPrimary = false;
		$this->setReadOnlyValue('person', $person);
	}

}
