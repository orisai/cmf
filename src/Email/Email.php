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
 * @property string                 $type
 * @property-read Person            $person {m:1 Person::$emails, cascade=[persist]}
 */
final class Email extends Entity
{

	// Not all possible are listed here, just the common ones
	public const TYPE_PRIMARY = 'primary';
	public const TYPE_BILLING = 'billing';

	public function __construct(string $emailAddress, string $type, Person $person)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->emailAddress = $emailAddress;
		$this->type = $type;
		$this->setReadOnlyValue('person', $person);
	}

}
