<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Menu;

use Closure;
use OriCMF\UI\ActionLink;

/**
 * @todo - external url
 */
final class MenuItem
{

	/**
	 * @param Closure(): ActionLink $destination
	 */
	public function __construct(
		private string $title,
		private Closure $destination,
		private string|null $icon = null,
		private string|null $privilege = null,
	)
	{
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDestination(): ActionLink
	{
		return ($this->destination)();
	}

	public function getIcon(): string|null
	{
		return $this->icon;
	}

	public function getPrivilege(): string|null
	{
		return $this->privilege;
	}

}
