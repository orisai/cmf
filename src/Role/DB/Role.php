<?php declare(strict_types = 1);

namespace OriCMF\Role\DB;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\Orm\Wrapper\JsonWrapper;
use Symfony\Component\Uid\UuidV7;
use function Orisai\Clock\now;

/**
 * @uses JsonWrapper
 * @property-read string            $id         {primary}
 * @property-read DateTimeImmutable $createdAt
 * @property string                 $name
 * @property-read bool              $isImmutable
 * @property array<string>          $privileges {wrapper JsonWrapper}
 */
final class Role extends Entity
{

	public function __construct(string $name, bool $isImmutable = false)
	{
		parent::__construct();
		$this->setReadOnlyValue('id', (new UuidV7())->toRfc4122());
		$this->setReadOnlyValue('createdAt', now());

		$this->name = $name;
		$this->setReadOnlyValue('isImmutable', $isImmutable);
		$this->privileges = [];
	}

}
