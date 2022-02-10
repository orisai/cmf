<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nextras\Orm\Model\MetadataStorage;

final class OrmInitializerExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([]);
	}

	public function beforeCompile(): void
	{
		parent::beforeCompile();
		$builder = $this->getContainerBuilder();

		// Prevents error caused by working with entities before MetadataStorage is instantiated
		$this->initialization->addBody('$this->getService(?);', [
			$builder->getDefinitionByType(MetadataStorage::class)->getName(),
		]);
	}

}
