<?php declare(strict_types = 1);

namespace OriCMF\Core\Person;

use DateTimeImmutable;
use Nette\Utils\Strings;
use Nextras\Orm\Entity\Entity;
use OriCMF\Core\User\User;
use Symfony\Component\Uid\Ulid;
use function random_int;

/**
 * @property-read string            $id {primary}
 * @property-read DateTimeImmutable $createdAt {default now}
 * @property User|null              $account {1:1 Account::$person, cascade=[persist, remove]}
 * @property string                 $firstName
 * @property string                 $lastName
 * @property string                 $nickName
 */
final class Person extends Entity
{

	public function __construct(string $firstName, string $lastName)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->nickName = Strings::webalize("$firstName.$lastName." . random_int(100, 9_999));
	}

}
