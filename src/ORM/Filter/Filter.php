<?php declare(strict_types = 1);

namespace OriCMF\ORM\Filter;

class Filter
{

	private FindFilter $find;

	private OrderFilter $order;

	/** @var int<1, max>|null */
	private int|null $limitCount = null;

	/** @var int<0, max>|null */
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

	/**
	 * @param int<1, max> $count
	 * @param int<0, max>|null $offset
	 */
	public function limit(int $count, int|null $offset = null): void
	{
		$this->limitCount = $count;
		$this->limitOffset = $offset;
	}

	/**
	 * @return array{int<1, max>|null, int<0, max>|null}
	 */
	public function getLimit(): array
	{
		return [
			$this->limitCount,
			$this->limitOffset,
		];
	}

	public function isLimitEmpty(): bool
	{
		return $this->limitCount === null;
	}

	public function isEmpty(): bool
	{
		return $this->find->isEmpty()
			&& $this->order->isEmpty()
			&& $this->isLimitEmpty();
	}

}
