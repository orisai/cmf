<?php declare(strict_types = 1);

namespace OriCMF\Core\ORM\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

final class OrmInitializerExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([]);
	}

	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		// Prevents error caused by working with entities before MetadataStorage is created
		$this->initialization->addBody('$this->getService(?);', ['orm.metadataStorage']);
	}

}
