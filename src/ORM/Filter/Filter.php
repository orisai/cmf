<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\Filter;

class Filter
{

	private FindFilter $find;

	private OrderFilter $order;

	private int|null $limitCount = null;

	private int|null $limitOffset = null;

	public function __construct()
	{
		$this->find = new FindFilter();
		$this->order = new OrderFilter();
	}

	public function find(): FindFilter
	{
		return $this->find;
	}

	public function order(): OrderFilter
	{
		return $this->order;
	}

	public function limit(int $count, int|null $offset = null): void
	{
		$this->limitCount = $count;
		$this->limitOffset = $offset;
	}

	/**
	 * @return array{int|null, int|null}
	 */
	public function getLimit(): array
	{
		return [
			$this->limitCount,
			$this->limitOffset,
		];
	}

}
