<?php declare(strict_types = 1);

namespace OriCMF\Core\Email;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\Core\Person\Person;
use Symfony\Component\Uid\Ulid;

/**
 * @property-read string            $id {primary}
 * @property-read DateTimeImmutable $createdAt {default now}
 * @property string                 $emailAddress
 * @property bool                   $isPrimary
 * @property-read Person            $person {m:1 Person::$emails, cascade=[persist]}
 */
final class Email extends Entity
{

	public function __construct(string $emailAddress, Person $person)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->emailAddress = $emailAddress;
		$this->isPrimary = false;
		$this->setReadOnlyValue('person', $person);
	}

}
