<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

final class FindParameter
{

	public function __construct(private readonly string $column, private readonly mixed $value)
	{
	}

	public function getColumn(): string
	{
		return $this->column;
	}

	public function getValue(): mixed
	{
		return $this->value;
	}

}
