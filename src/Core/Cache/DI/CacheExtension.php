<?php declare(strict_types = 1);

namespace OriCMF\Core\Cache\DI;

use Nette\Caching\Storage;
use Nette\Caching\Storages\FileStorage;
use Nette\Caching\Storages\Journal;
use Nette\Caching\Storages\SQLiteJournal;
use Nette\DI\CompilerExtension;
use Nette\Utils\FileSystem;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Utils\Dependencies\Dependencies;
use function is_writable;

final class CacheExtension extends CompilerExtension
{

	public function __construct(private string $tempDir)
	{
	}

	public function loadConfiguration(): void
	{
		$dir = $this->tempDir;
		FileSystem::createDir($dir);
		if (!is_writable($dir)) {
			throw InvalidState::create()
				->withMessage("Make directory '$dir' writable.");
		}

		$builder = $this->getContainerBuilder();

		if (Dependencies::isExtensionLoaded('pdo_sqlite')) {
			$builder->addDefinition($this->prefix('journal'))
				->setFactory(SQLiteJournal::class, [
					"$dir/journal.s3db",
				])
				->setType(Journal::class);
		}

		$builder->addDefinition($this->prefix('storage'))
			->setFactory(FileStorage::class, [
				$dir,
			])
			->setType(Storage::class);
	}

}
