<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

final class OrderParameter
{

	public function __construct(
		private readonly string $column,
		private readonly ColumnOrder $order,
	)
	{
	}

	public function getColumn(): string
	{
		return $this->column;
	}

	public function getOrder(): ColumnOrder
	{
		return $this->order;
	}

	public function isAsc(): bool
	{
		return $this->order === ColumnOrder::Asc;
	}

	public function isDesc(): bool
	{
		return $this->order === ColumnOrder::Desc;
	}

}
