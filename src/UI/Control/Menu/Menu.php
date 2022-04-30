<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Menu;

use function array_merge;

final class Menu
{

	/** @var array<MenuItem> */
	private array $items;

	/**
	 * @param array<MenuItemsProvider> $providers
	 */
	public function __construct(array $providers = [])
	{
		$itemsByProvider = [];

		foreach ($providers as $provider) {
			$itemsByProvider[] = $provider->getItems();
		}

		$this->items = array_merge(...$itemsByProvider);
	}

	/**
	 * @return array<MenuItem>
	 */
	public function getItems(): array
	{
		return $this->items;
	}

}
