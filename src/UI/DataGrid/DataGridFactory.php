<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

use Closure;

interface DataGridFactory
{

	/**
	 * @param Closure(SearchParameters): array<mixed> $dataSource
	 */
	public function create(string $rowPrimaryKey, Closure $dataSource): DataGrid;

}
