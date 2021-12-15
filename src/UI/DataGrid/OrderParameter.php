<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

final class OrderParameter
{

	/** @phpstan-var DataGrid::ORDER_* */
	private string $direction;

	/**
	 * @phpstan-param DataGrid::ORDER_* $direction
	 */
	public function __construct(private string $column, string $direction)
	{
		$this->direction = $direction;
	}

	public function getColumn(): string
	{
		return $this->column;
	}

	/**
	 * @phpstan-return DataGrid::ORDER_*
	 */
	public function getDirection(): string
	{
		return $this->direction;
	}

	public function isAsc(): bool
	{
		return $this->direction === DataGrid::ORDER_ASC;
	}

	public function isDesc(): bool
	{
		return $this->direction === DataGrid::ORDER_DESC;
	}

}
