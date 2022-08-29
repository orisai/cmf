<?php declare(strict_types = 1);

namespace OriCMF\ORM\Wrapper;

use Brick\DateTime\LocalDate;
use DateTimeImmutable;
use DateTimeInterface;
use function assert;

final class LocalDateWrapper extends ValuePropertyWrapper
{

	public function convertToRawValue(mixed $value): DateTimeImmutable|null
	{
		if ($value === null) {
			return null;
		}

		assert($value instanceof LocalDate);

		return $value->toNativeDateTimeImmutable();
	}

	public function convertFromRawValue(mixed $value): LocalDate|null
	{
		if ($value === null) {
			return null;
		}

		assert($value instanceof DateTimeInterface);

		return LocalDate::fromNativeDateTime($value);
	}

}
