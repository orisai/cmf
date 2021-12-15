<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

class Column
{

	protected bool $sort = false;

	public function __construct(public string $name, public string $label, protected DataGrid $grid)
	{
	}

	/**
	 * @phpstan-param DataGrid::ORDER_*|null $defaultOrder
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
			return DataGrid::ORDER_DESC;
		}

		if ($this->isDesc()) {
			return '';
		}

		return DataGrid::ORDER_ASC;
	}

	public function isAsc(): bool
	{
		return $this->grid->orderColumn === $this->name && $this->grid->orderType === DataGrid::ORDER_ASC;
	}

	public function isDesc(): bool
	{
		return $this->grid->orderColumn === $this->name && $this->grid->orderType === DataGrid::ORDER_DESC;
	}

}
