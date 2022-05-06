<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

final class Column
{

	private bool $sort = false;

	public function __construct(public string $name, public string $label, private DataGrid $grid)
	{
	}

	/**
	 * @phpstan-param DataGrid::Order*|null $defaultOrder
	 * @return $this
	 */
	public function enableSort(string|null $defaultOrder = null): self
	{
		$this->sort = true;
		if ($defaultOrder !== null) {
			$this->grid->orderColumn = $this->name;
			$this->grid->orderType = $defaultOrder;
		}

		return $this;
	}

	public function canSort(): bool
	{
		return $this->sort;
	}

	public function getNewState(): string
	{
		if ($this->isAsc()) {
			return DataGrid::OrderDesc;
		}

		if ($this->isDesc()) {
			return '';
		}

		return DataGrid::OrderAsc;
	}

	public function isAsc(): bool
	{
		return $this->grid->orderColumn === $this->name && $this->grid->orderType === DataGrid::OrderAsc;
	}

	public function isDesc(): bool
	{
		return $this->grid->orderColumn === $this->name && $this->grid->orderType === DataGrid::OrderDesc;
	}

}
