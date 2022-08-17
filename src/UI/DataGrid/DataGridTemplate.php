<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
use OriCMF\UI\Control\BaseControlTemplate;

/**
 * @property-read DataGrid $control
 */
final class DataGridTemplate extends BaseControlTemplate
{

	public DataGrid $control;

	public Form $form;

	/** @var array<Column> */
	public array $columns;

	public string|null $rowPrimaryKey;

	public Paginator|null $paginator;

	public bool $sendOnlyRowParentSnippet;

	public bool $showFilterCancel;

	/** @var array<mixed> */
	public array $data;

}
