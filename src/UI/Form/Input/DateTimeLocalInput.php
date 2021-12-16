<?php declare(strict_types = 1);

namespace OriCMF\UI\Form\Input;

use Brick\DateTime\Instant;
use Brick\DateTime\LocalDateTime;
use Brick\DateTime\Parser\DateTimeParseException;
use Brick\DateTime\TimeZone;
use Brick\DateTime\ZonedDateTime;
use DateTimeInterface;
use Nette\Forms\Controls\TextInput;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use function assert;
use function get_debug_type;
use function implode;
use function is_string;

/**
 * @todo - validate min, max
 *       - Instant (setMin/Max) to ZonedDateTime
 *       - should be independent of specific timezone
 */
final class DateTimeLocalInput extends TextInput
{

	public function __construct(string $label)
	{
		parent::__construct($label);
		$this->setHtmlType('datetime-local');
	}

	public function setMax(Instant $max): self
	{
		$this->setHtmlAttribute('max', $this->fromInstant($max));

		return $this;
	}

	public function setMin(Instant $min): self
	{
		$this->setHtmlAttribute('min', $this->fromInstant($min));

		return $this;
	}

	public function setValue(mixed $value): self
	{
		if ($value instanceof Instant) {
			$value = $this->fromInstant($value);
		} elseif ($value instanceof ZonedDateTime) {
			$value = $this->fromZonedDateTime($value);
		} elseif ($value instanceof DateTimeInterface) {
			$value = $this->fromDateTimeInterface($value);
		} elseif (is_string($value)) {
			$value = $value === ''
				? null
				: $this->fromString($value);
		} elseif ($value !== null) {
			$class = self::class;
			$given = get_debug_type($value);
			$expected = implode(
				', ',
				[Instant::class, ZonedDateTime::class, DateTimeInterface::class, 'string', 'null'],
			);
			$message = Message::create()
				->withContext("Trying to set value of $class.")
				->withProblem("Unexpected value of type $given given.")
				->withSolution("Pass one of $expected instead.");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		return parent::setValue($value);
	}

	public function getValue(): ZonedDateTime|null
	{
		$value = parent::getValue();

		if ($value === null) {
			return null;
		}

		assert(is_string($value));

		try {
			return $this->toZonedDateTime($value);
		} catch (DateTimeParseException) {
			return null;
		}
	}

	private function fromString(string $string): string
	{
		return $this->fromZonedDateTime(
			$this->toZonedDateTime($string),
		);
	}

	private function fromInstant(Instant $instant): string
	{
		return $this->fromZonedDateTime(
			$instant->atTimeZone(TimeZone::utc()),
		);
	}

	private function fromZonedDateTime(ZonedDateTime $dateTime): string
	{
		return $this->fromDateTimeInterface(
			$dateTime
				->withTimeZoneSameInstant(TimeZone::parse('Europe/Prague'))
				->minusSeconds($dateTime->getSecond())
				->toDateTime(),
		);
	}

	private function fromDateTimeInterface(DateTimeInterface $dateTime): string
	{
		return $dateTime->format('Y-m-d\TH:i');
	}

	private function toZonedDateTime(string $localDateTime): ZonedDateTime
	{
		return LocalDateTime::parse($localDateTime)
			->atTimeZone(TimeZone::parse('Europe/Prague'))
			->withTimeZoneSameInstant(TimeZone::utc());
	}

}