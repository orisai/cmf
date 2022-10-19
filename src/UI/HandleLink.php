<?php declare(strict_types = 1);

namespace OriCMF\UI;

use Nette\Application\UI\Component;

final class HandleLink
{

	private bool $absolute = false;

	private string|null $anchor = null;

	/**
	 * @param array<string, mixed> $args
	 */
	public function __construct(
		private Component $component,
		private string $handle,
		private array $args = [],
	)
	{
	}

	public function get(): string
	{
		$destination = ($this->absolute ? '//' : '')
			. "$this->handle!"
			. ($this->anchor !== null ? "#$this->anchor" : '');

		return $this->component->link($destination, $this->args);
	}

	public function addArgument(string $name, mixed $value): self
	{
		$this->args[$name] = $value;

		return $this;
	}

	public function setAbsolute(bool $absolute = true): self
	{
		$this->absolute = $absolute;

		return $this;
	}

	public function setAnchor(string|null $anchor): self
	{
		$this->anchor = $anchor;

		return $this;
	}

}
