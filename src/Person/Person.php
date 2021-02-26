<?php declare(strict_types = 1);

namespace OriCMF\Core\Person;

use DateTimeImmutable;
use Nette\Utils\Strings;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\OneHasMany;
use OriCMF\Core\Email\Email;
use OriCMF\Core\User\User;
use Symfony\Component\Uid\Ulid;
use function random_int;

/**
 * @property-read string             $id {primary}
 * @property-read DateTimeImmutable  $createdAt {default now}
 * @property User|null               $user {1:1 User::$person, cascade=[persist, remove]}
 * @property string                  $fullName
 * @property string                  $userName
 * @property OneHasMany&array<Email> $emails {1:m Email::$person, cascade=[persist, remove]}
 */
final class Person extends Entity
{

	public function __construct(string $fullName)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->fullName = $fullName;
		$this->userName = Strings::webalize("$fullName." . random_int(100, 9_999));
	}

	public function getPrimaryEmail(): ?Email
	{
		return $this->emails->toCollection()->getBy(['type' => Email::TYPE_PRIMARY]);
	}

}
