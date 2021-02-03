<?php declare(strict_types = 1);

namespace OriCMF\Core\Role;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use OriCMF\Core\ORM\JsonWrapper;
use Symfony\Component\Uid\Ulid;

/**
 * @property-read string            $id {primary}
 * @property-read DateTimeImmutable $createdAt {default now}
 * @property string                 $name
 * @property array<string>          $privileges {wrapper JsonWrapper}
 * @uses JsonWrapper
 */
final class Role extends Entity
{

	public function __construct(string $name)
	{
		parent::__construct();

		$this->setReadOnlyValue('id', (new Ulid())->toRfc4122());
		$this->name = $name;
		$this->privileges = [];
	}

}
