<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM;

use Nextras\Orm\Collection\Functions\IArrayFunction;
use Nextras\Orm\Collection\Functions\IQueryBuilderFunction;
use Nextras\Orm\Repository\Repository;
use OriCMF\Core\ORM\Functions\ArrayAggregateFunction;
use OriCMF\Core\ORM\Functions\InsensitiveLikeSearchFunction;
use OriCMF\Core\ORM\Functions\JsonAnyKeyOrValueExistsFunction;

abstract class BaseRepository extends Repository
{

	/**
	 * @return IQueryBuilderFunction|IArrayFunction
	 * @todo - make it repository independent
	 */
	protected function createCollectionFunction(string $name)
	{
		if ($name === ArrayAggregateFunction::class) {
			return new ArrayAggregateFunction();
		}

		if ($name === InsensitiveLikeSearchFunction::class) {
			return new InsensitiveLikeSearchFunction();
		}

		if ($name === JsonAnyKeyOrValueExistsFunction::class) {
			return new JsonAnyKeyOrValueExistsFunction();
		}

		return parent::createCollectionFunction($name);
	}

}
