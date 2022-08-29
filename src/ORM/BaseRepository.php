<?php declare(strict_types = 1);

namespace OriCMF\ORM;

use Nextras\Orm\Collection\Functions\IArrayFunction;
use Nextras\Orm\Collection\Functions\IQueryBuilderFunction;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use Nextras\Orm\Exception\NoResultException;
use Nextras\Orm\Mapper\IMapper;
use Nextras\Orm\Repository\Repository;
use OriCMF\ORM\Filter\Filter;
use OriCMF\ORM\Filter\FindFilter;
use OriCMF\ORM\Functions\InsensitiveLikeSearchFunction;
use OriCMF\ORM\Functions\JsonAnyKeyOrValueExistsFunction;
use OriCMF\ORM\Functions\ToManyNotEqualFunction;
use function in_array;

abstract class BaseRepository extends Repository
{

	private const Functions = [
		InsensitiveLikeSearchFunction::class,
		JsonAnyKeyOrValueExistsFunction::class,
		ToManyNotEqualFunction::class,
	];

	public function __construct(IMapper $mapper)
	{
		parent::__construct($mapper);
	}

	/**
	 * @todo - make it repository independent
	 */
	protected function createCollectionFunction(string $name): IQueryBuilderFunction|IArrayFunction
	{
		if (in_array($name, self::Functions, true)) {
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
