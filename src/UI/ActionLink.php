<?php declare(strict_types = 1);

namespace OriCMF\UI;

use Nette\Application\IPresenter;
use Orisai\Exceptions\Logic\InvalidArgument;
use function str_starts_with;
use function substr;

final class ActionLink
{

	private bool $absolute = false;

	private string|null $anchor = null;

	/**
	 * @param array<string, mixed> $args
	 */
	private function __construct(private readonly string $destination, private array $args = [])
	{
	}

	/**
	 * @param class-string<IPresenter> $presenter
	 * @param array<string, mixed>     $args
	 */
	public static function fromClass(string $presenter, array $args = [], string $action = 'default'): self
	{
		return new self(":$presenter:$action", $args);
	}

	/**
	 * @param array<string, mixed> $args
	 */
	public static function fromMapping(string $destination, array $args = []): self
	{
		if (str_starts_with($destination, '//')) {
			$destination = substr($destination, 2);
			$absolute = true;
		} else {
			$absolute = false;
		}

		if (!str_starts_with($destination, ':')) {
			throw InvalidArgument::create()
				->withMessage(
					<<<'TXT'
Destination must be an absolute link. Relative links and "this" are forbidden.
Format: [[[module:]presenter:]action | signal!] [#fragment]
TXT,
				);
		}

		$self = new self($destination, $args);
		$self->absolute = $absolute;

		return $self;
	}

	public function getDestination(): string
	{
		return ($this->absolute ? '//' : '')
			. $this->destination
			. ($this->anchor !== null ? "#$this->anchor" : '');
	}

	/**
	 * @return array<mixed>
	 */
	public function getArguments(): array
	{
		return $this->args;
	}

	public function addArgument(string $name, mixed $value): void
	{
		$this->args[$name] = $value;
	}

	public function setAbsolute(bool $absolute = true): void
	{
		$this->absolute = $absolute;
	}

	public function setAnchor(string|null $anchor): void
	{
		$this->anchor = $anchor;
	}

}
