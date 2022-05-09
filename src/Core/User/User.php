<?php declare(strict_types = 1);

namespace OriCMF\Core\User;

use DateTimeImmutable;
use Nette\Utils\Strings;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\HasMany;
use OriCMF\Core\Email\Email;
use OriCMF\Core\ORM\Wrapper\BackedEnumWrapper;
use OriCMF\Core\Role\Role;
use Symfony\Component\Uid\Ulid;
use function random_int;

/**
 * @uses BackedEnumWrapper
 * @property-read string                  $id         {primary}
 * @property-read DateTimeImmutable       $createdAt  {default now}
 * @property string                       $fullName
 * @property string                       $userName
 * @property-read iterable<Email>&HasMany $emails     {1:m Email::$user, cascade=[persist, remove]}
 * @property-read iterable<Role>&HasMany  $roles      {m:m Role, isMain=true, oneSided=true, cascade=[persist]}
 * @property-read array<string>           $privileges {virtual}
 * @property-read string|null             $type       Distinguish between real users and automatic ones (system, APIs)
 * @property UserState                    $state      {wrapper BackedEnumWrapper}
 */
final class User extends Entity
{

	// Not all possible are listed here, just the common ones
	public const
		TypeSystem = 'system',
		TypeReal = null;

	public function __construct(string $fullName, string|null $type = self::TypeReal)
	{
		parent::__construct();
		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());

		$this->fullName = $fullName;
		$this->userName = Strings::webalize("$fullName-" . random_int(100, 9_999));
		$this->setReadOnlyValue('type', $type);
		$this->state = UserState::New;
	}

	public function getPrimaryEmail(): Email|null
	{
		return $this->emails->toCollection()->getBy(['type' => Email::TypePrimary]);
	}

	/**
	 * @return array<string>
	 *
	 * @todo - user privileges
	 */
	protected function getterPrivileges(): array
	{
		return [];
	}

}
