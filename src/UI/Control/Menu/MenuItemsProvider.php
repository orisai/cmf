<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Menu;

interface MenuItemsProvider
{

	/**
	 * @return array<MenuItem>
	 */
	public function getItems(): array;

}
