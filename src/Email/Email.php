<?php declare(strict_types = 1);

namespace OriCMF\Core\Email;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\Core\User\User;
use Symfony\Component\Uid\Ulid;

/**
 * @property-read string            $id {primary}
 * @property-read DateTimeImmutable $createdAt {default now}
 * @property string                 $emailAddress
 * @property string                 $type
 * @property-read User              $user {m:1 User::$emails, cascade=[persist]}
 */
final class Email extends Entity
{

	// Not all possible are listed here, just the common ones
	public const TYPE_PRIMARY = 'primary';
	public const TYPE_BILLING = 'billing';

	public function __construct(string $emailAddress, string $type, User $user)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->emailAddress = $emailAddress;
		$this->type = $type;
		$this->setReadOnlyValue('user', $user);
	}

}
