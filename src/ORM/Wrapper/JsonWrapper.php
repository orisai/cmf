<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Wrapper;

use Nette\Utils\Json;
use function assert;
use function is_string;

final class JsonWrapper extends ValuePropertyWrapper
{

	public function convertToRawValue(mixed $value): string
	{
		return Json::encode($value);
	}

	public function convertFromRawValue(mixed $value): mixed
	{
		assert(is_string($value));

		return Json::decode($value);
	}

}
