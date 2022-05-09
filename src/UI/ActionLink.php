<?php declare(strict_types = 1);

namespace OriCMF\UI;

use Nette\Application\IPresenter;
use Orisai\Exceptions\Logic\InvalidArgument;
use function str_starts_with;

final class ActionLink
{

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
		if (
			!str_starts_with($destination, ':')
			&& !str_starts_with($destination, '//:')
		) {
			throw InvalidArgument::create()
				->withMessage(
					<<<'TXT'
Destination must be an absolute link. Relative links and "this" are forbidden.
Format: [[[module:]presenter:]action | signal!] [#fragment]
TXT,
				);
		}

		return new self($destination, $args);
	}

	public function getDestination(): string
	{
		return $this->destination
			. ($this->anchor !== null ? "#$this->anchor" : '');
	}

	/**
	 * @return array<mixed>
	 */
	public function getArguments(): array
	{
		return $this->args;
	}

	public function setAnchor(string|null $anchor): void
	{
		$this->anchor = $anchor;
	}

	public function addArgument(string $name, mixed $value): void
	{
		$this->args[$name] = $value;
	}

}
