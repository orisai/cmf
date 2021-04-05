<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Wrapper;

use Brick\DateTime\ZonedDateTime;
use DateTimeInterface;
use function assert;

final class ZonedDateTimeWrapper extends ValuePropertyWrapper
{

	/**
	 * @param mixed $value
	 */
	public function convertToRawValue($value): string
	{
		assert($value instanceof ZonedDateTime);

		return $value->toDateTime()->format('c');
	}

	/**
	 * @param mixed $value
	 */
	public function convertFromRawValue($value): ZonedDateTime
	{
		assert($value instanceof DateTimeInterface);

		return ZonedDateTime::fromDateTime($value);
	}

}
