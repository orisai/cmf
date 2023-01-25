<?php declare(strict_types = 1);

namespace OriCMF\Email\DB;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\User\DB\User;
use Symfony\Component\Uid\UuidV7;
use function Orisai\Clock\now;

/**
 * @property-read string            $id        {primary}
 * @property-read DateTimeImmutable $createdAt
 * @property string                 $emailAddress
 * @property string                 $type
 * @property-read User              $user      {m:1 User::$emails, cascade=[persist]}
 */
final class Email extends Entity
{

	// Not all possible are listed here, just the common ones
	public const TypePrimary = 'primary';

	public const TypeBilling = 'billing';

	public function __construct(string $emailAddress, string $type, User $user)
	{
		parent::__construct();
		$this->setReadOnlyValue('id', (new UuidV7())->toRfc4122());
		$this->setReadOnlyValue('createdAt', now());

		$this->emailAddress = $emailAddress;
		$this->type = $type;
		$this->setReadOnlyValue('user', $user);
	}

}
