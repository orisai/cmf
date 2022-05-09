<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Wrapper;

use BackedEnum;
use Orisai\Exceptions\Logic\InvalidArgument;
use function array_key_first;
use function assert;
use function count;
use function is_int;
use function is_string;
use function is_subclass_of;

final class BackedEnumWrapper extends ValuePropertyWrapper
{

	public function convertToRawValue(mixed $value): int|string
	{
		assert($value instanceof BackedEnum);

		return $value->value;
	}

	public function convertFromRawValue(mixed $value): BackedEnum
	{
		assert(is_int($value) || is_string($value));

		$types = $this->propertyMetadata->types;

		if (count($types) !== 1) {
			throw InvalidArgument::create()
				->withMessage('Property must have one and only one type defined.');
		}

		$type = array_key_first($types);
		assert(is_subclass_of($type, BackedEnum::class));

		return $type::from($value);
	}

}
