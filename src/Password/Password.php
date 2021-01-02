<?php declare(strict_types = 1);

namespace OriCMF\Core\Password;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\Core\User\User;
use Symfony\Component\Uid\Ulid;

/**
 * @property-read string            $id {primary}
 * @property-read DateTimeImmutable $createdAt {default now}
 * @property string                 $passwordHash
 * @property-read User              $user {1:1 User, oneSided=true, cascade=[persist]}
 */
final class Password extends Entity
{

	public function __construct(string $passwordHash, User $user)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->passwordHash = $passwordHash;
		$this->setReadOnlyValue('user', $user);
	}

}
