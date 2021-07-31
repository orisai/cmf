<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Wrapper;

use Brick\DateTime\LocalDate;
use DateTimeInterface;
use function assert;

final class LocalDateWrapper extends ValuePropertyWrapper
{

	public function convertToRawValue(mixed $value): string|null
	{
		if ($value === null) {
			return null;
		}

		assert($value instanceof LocalDate);

		return $value->toDateTime()->format('c');
	}

	public function convertFromRawValue(mixed $value): LocalDate|null
	{
		if ($value === null) {
			return null;
		}

		assert($value instanceof DateTimeInterface);

		return LocalDate::fromDateTime($value);
	}

}
