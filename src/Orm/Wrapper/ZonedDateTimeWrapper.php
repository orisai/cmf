<?php declare(strict_types = 1);

namespace OriCMF\Orm\Wrapper;

use Brick\DateTime\ZonedDateTime;
use DateTimeInterface;
use function assert;

final class ZonedDateTimeWrapper extends ValuePropertyWrapper
{

	public function convertToRawValue(mixed $value): string|null
	{
		if ($value === null) {
			return null;
		}

		assert($value instanceof ZonedDateTime);

		return $value->toNativeDateTimeImmutable()->format('c');
	}

	public function convertFromRawValue(mixed $value): ZonedDateTime|null
	{
		if ($value === null) {
			return null;
		}

		assert($value instanceof DateTimeInterface);

		return ZonedDateTime::fromNativeDateTime($value);
	}

}
