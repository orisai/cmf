<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Wrapper;

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

		return $value->toDateTime()->format('c');
	}

	public function convertFromRawValue(mixed $value): ZonedDateTime
	{
		assert($value instanceof DateTimeInterface);

		return ZonedDateTime::fromDateTime($value);
	}

}
