<?php declare(strict_types = 1);

namespace OriCMF\Password\DB;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\User\DB\User;
use Symfony\Component\Uid\UuidV7;
use function Orisai\Clock\now;

/**
 * @property-read string            $id        {primary}
 * @property-read DateTimeImmutable $createdAt
 * @property string                 $encodedPassword
 * @property-read User              $user      {1:1 User, isMain=true, oneSided=true, cascade=[persist]}
 */
final class Password extends Entity
{

	public function __construct(string $encodedPassword, User $user)
	{
		parent::__construct();
		$this->setReadOnlyValue('id', (new UuidV7())->toRfc4122());
		$this->setReadOnlyValue('createdAt', now());

		$this->encodedPassword = $encodedPassword;
		$this->setReadOnlyValue('user', $user);
	}

}
