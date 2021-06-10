<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM;

use Nextras\Orm\Collection\Functions\IArrayFunction;
use Nextras\Orm\Collection\Functions\IQueryBuilderFunction;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Exception\NoResultException;
use Nextras\Orm\Repository\Repository;
use OriCMF\Core\ORM\Filter\Filter;
use OriCMF\Core\ORM\Filter\FindFilter;
use OriCMF\Core\ORM\Functions\InsensitiveLikeSearchFunction;
use OriCMF\Core\ORM\Functions\JsonAnyKeyOrValueExistsFunction;
use OriCMF\Core\ORM\Functions\ToManyNotEqualFunction;
use function in_array;

abstract class BaseRepository extends Repository
{

	private const FUNCTIONS = [
		InsensitiveLikeSearchFunction::class,
		JsonAnyKeyOrValueExistsFunction::class,
		ToManyNotEqualFunction::class,
	];

	/**
	 * @todo - make it repository independent
	 */
	protected function createCollectionFunction(string $name): IQueryBuilderFunction|IArrayFunction
	{
		if (in_array($name, self::FUNCTIONS, true)) {
			return new $name();
		}

		return parent::createCollectionFunction($name);
	}

	public function createFilter(): Filter
	{
		return new Filter();
	}

	public function findByFilter(Filter $filter): ICollection
	{
		$conditions = $filter->find()->getConditions();
		$collection = $conditions === [] ? $this->findAll() : $this->findBy($conditions);

		$order = $filter->order()->getOrder();
		foreach ($order as [$expression, $direction]) {
			$collection = $collection->orderBy($expression, $direction);
		}

		[$limitCount, $limitOffset] = $filter->getLimit();
		if ($limitCount !== null) {
			$collection = $collection->limitBy($limitCount, $limitOffset);
		}

		return $collection;
	}

	public function getByFilter(FindFilter $find): IEntity|null
	{
		return $this->getBy($find->getConditions());
	}

	public function getByFilterChecked(FindFilter $find): IEntity
	{
		$entity = $this->getBy($find->getConditions());
		if ($entity === null) {
			throw new NoResultException();
		}

		return $entity;
	}

}
