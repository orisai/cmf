<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM;

use Nextras\Orm\Entity\Entity;

abstract class BaseEntity extends Entity
{

	/**
	 * @return $this
	 */
	public function setModifiedValue(string $name, mixed $value): static
	{
		if (!$this->hasValue($name) || $this->getValue($name) !== $value) {
			$this->setValue($name, $value);
		}

		return $this;
	}

	public function __set(string $name, mixed $value): void
	{
		$this->setModifiedValue($name, $value);
	}

}
