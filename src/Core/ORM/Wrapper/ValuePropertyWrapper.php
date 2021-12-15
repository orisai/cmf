<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Wrapper;

use Nextras\Orm\Entity\ImmutableValuePropertyWrapper;

abstract class ValuePropertyWrapper extends ImmutableValuePropertyWrapper
{

	public function setInjectedValue(mixed $value): bool
	{
		if ($this->value !== $value) {
			$this->value = $value;

			return true;
		}

		return false;
	}

}
