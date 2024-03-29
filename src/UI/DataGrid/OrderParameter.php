<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

final class OrderParameter
{

	/**
	 * @phpstan-param DataGrid::Order* $direction
	 */
	public function __construct(private readonly string $column, private readonly string $direction)
	{
	}

	public function getColumn(): string
	{
		return $this->column;
	}

	/**
	 * @phpstan-return DataGrid::Order*
	 */
	public function getDirection(): string
	{
		return $this->direction;
	}

	public function isAsc(): bool
	{
		return $this->direction === DataGrid::OrderAsc;
	}

	public function isDesc(): bool
	{
		return $this->direction === DataGrid::OrderDesc;
	}

}
