<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

final class Column
{

	private bool $sort = false;

	public function __construct(public string $name, public string $label, private DataGrid $grid)
	{
	}

	/**
	 * @return $this
	 */
	public function enableSort(ColumnOrder|null $defaultOrder = null): self
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

	public function getNewState(): ColumnOrder
	{
		if ($this->isAsc()) {
			return ColumnOrder::Desc;
		}

		if ($this->isDesc()) {
			return ColumnOrder::Undefined;
		}

		return ColumnOrder::Asc;
	}

	public function isAsc(): bool
	{
		return $this->grid->orderColumn === $this->name && $this->grid->orderType === ColumnOrder::Asc;
	}

	public function isDesc(): bool
	{
		return $this->grid->orderColumn === $this->name && $this->grid->orderType === ColumnOrder::Desc;
	}

}
