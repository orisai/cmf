<?php declare(strict_types = 1);

namespace OriCMF\UI\DataGrid;

use Nette\Utils\Paginator;

final class SearchParameters
{

	/**
	 * @param array<FindParameter>  $find
	 * @param array<OrderParameter> $order
	 */
	public function __construct(
		private readonly array $find,
		private readonly array $order,
		private readonly Paginator|null $paginator,
	)
	{
	}

	/**
	 * @return array<FindParameter>
	 */
	public function getFind(): array
	{
		return $this->find;
	}

	/**
	 * @return array<OrderParameter>
	 */
	public function getOrder(): array
	{
		return $this->order;
	}

	public function getPaginator(): Paginator|null
	{
		return $this->paginator;
	}

}
