<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM;

use Nette\Utils\Json;
use Nextras\Orm\Entity\ImmutableValuePropertyWrapper;
use function assert;
use function is_string;

final class JsonWrapper extends ImmutableValuePropertyWrapper
{

	/**
	 * @param mixed $value
	 */
	public function convertToRawValue($value): string
	{
		return Json::encode($value);
	}

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function convertFromRawValue($value)
	{
		assert(is_string($value));

		return Json::decode($value);
	}

}
