<?php declare(strict_types = 1);

namespace OriCMF\Core\Password;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\Core\User\User;
use Symfony\Component\Uid\Ulid;

/**
 * @property-read string            $id {primary}
 * @property-read DateTimeImmutable $createdAt {default now}
 * @property string                 $encodedPassword
 * @property-read User              $user {1:1 User, isMain=true, oneSided=true, cascade=[persist]}
 */
final class Password extends Entity
{

	public function __construct(string $encodedPassword, User $user)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->encodedPassword = $encodedPassword;
		$this->setReadOnlyValue('user', $user);
	}

}
