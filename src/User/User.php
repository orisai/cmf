<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use DateTimeImmutable;
use Nette\Utils\Strings;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;
use OriCMF\Core\Email\Email;
use OriCMF\Core\Role\Role;
use Symfony\Component\Uid\Ulid;
use function random_int;

/**
 * @property-read string                  $id {primary}
 * @property-read DateTimeImmutable       $createdAt {default now}
 * @property string                       $fullName
 * @property string                       $userName
 * @property OneHasMany&array<Email>      $emails {1:m Email::$user, cascade=[persist, remove]}
 * @property-read ManyHasMany&array<Role> $roles {m:m Role, isMain=true, oneSided=true, cascade=[persist]}
 */
final class User extends Entity
{

	public function __construct(string $fullName)
	{
		parent::__construct();
		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());

		$this->fullName = $fullName;
		$this->userName = Strings::webalize("$fullName-" . random_int(100, 9_999));
	}

	public function getPrimaryEmail(): ?Email
	{
		return $this->emails->toCollection()->getBy(['type' => Email::TYPE_PRIMARY]);
	}

}
