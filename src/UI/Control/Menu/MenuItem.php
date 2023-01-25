<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Menu;

use Closure;
use OriCMF\UI\ActionLink;
use Orisai\TranslationContracts\Translatable;

final class MenuItem
{

	/**
	 * @param Closure(): ActionLink|string $destination
	 */
	public function __construct(
		private readonly Translatable|string $title,
		private readonly Closure|string $destination,
		private readonly string|null $icon = null,
		private readonly string|null $requiredPrivilege = null,
		private readonly bool $requiresRoot = false,
	)
	{
	}

	public function getTitle(): Translatable|string
	{
		return $this->title;
	}

	public function getDestination(): ActionLink|string
	{
		if ($this->destination instanceof Closure) {
			return ($this->destination)();
		}

		return $this->destination;
	}

	public function getIcon(): string|null
	{
		return $this->icon;
	}

	public function getRequiredPrivilege(): string|null
	{
		return $this->requiredPrivilege;
	}

	public function isRootRequired(): bool
	{
		return $this->requiresRoot;
	}

}
