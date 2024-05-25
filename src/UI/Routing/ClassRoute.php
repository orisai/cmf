<?php declare(strict_types = 1);

namespace OriCMF\UI\Routing;

use Nette\Application\IPresenter;
use Nette\Application\Routers\Route as OriginalRoute;
use Nette\Routing\Route;

final class ClassRoute extends Route
{

	private const PresenterKey = 'presenter';

	private const UIMeta = [
		'action' => [
			self::Pattern => '[a-z][a-z0-9-]*',
			self::FilterIn => [OriginalRoute::class, 'path2action'],
			self::FilterOut => [OriginalRoute::class, 'action2path'],
		],
	];

	/**
	 * @param string                   $mask     e.g. 'path/<action>/<id \d{1,3}>'
	 * @param class-string<IPresenter> $class
	 * @param array<mixed>             $metadata default values or metadata
	 */
	public function __construct(string $mask, string $class, array $metadata = [])
	{
		$metadata[self::PresenterKey] = $class;

		if (!isset($metadata['action'])) {
			$metadata['action'] = 'default';
		}

		$this->defaultMeta += self::UIMeta;
		parent::__construct($mask, $metadata);
	}

}
