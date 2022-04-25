<?php declare(strict_types = 1);

namespace OriCMF\UI\Form\Input;

use Brick\DateTime\Instant;
use Brick\DateTime\LocalDate;
use Brick\DateTime\Parser\DateTimeParseException;
use Brick\DateTime\TimeZoneRegion;
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
 *       - Instant (setMin/Max) to LocalDate
 */
final class DateInput extends TextInput
{

	public function __construct(string $label)
	{
		parent::__construct($label);
		$this->setHtmlType('date');
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
		} elseif ($value instanceof LocalDate) {
			$value = $this->fromLocalDate($value);
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
				[Instant::class, LocalDate::class, DateTimeInterface::class, 'string', 'null'],
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

	public function getValue(): LocalDate|null
	{
		$value = parent::getValue();

		if ($value === null) {
			return null;
		}

		assert(is_string($value));

		try {
			return $this->toLocalDate($value);
		} catch (DateTimeParseException) {
			return null;
		}
	}

	private function fromString(string $string): string
	{
		return $this->fromLocalDate(
			LocalDate::parse($string),
		);
	}

	private function fromInstant(Instant $instant): string
	{
		return $this->fromLocalDate(
			$instant->atTimeZone(TimeZoneRegion::utc())->getDate(),
		);
	}

	private function fromLocalDate(LocalDate $localDate): string
	{
		return $this->fromDateTimeInterface(
			$localDate->toDateTime(),
		);
	}

	private function fromDateTimeInterface(DateTimeInterface $dateTime): string
	{
		return $dateTime->format('Y-m-d');
	}

	private function toLocalDate(string $value): LocalDate
	{
		return LocalDate::parse($value);
	}

}
