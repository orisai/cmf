<?php declare(strict_types = 1);

namespace OriCMF\UI\Routing;

use Nette\Application\IPresenter;
use Nette\Application\Routers\Route as OriginalRoute;
use Nette\Routing\Route;

final class ClassRoute extends Route
{

	private const PRESENTER_KEY = 'presenter';

	private const UI_META = [
		'action' => [
			self::PATTERN => '[a-z][a-z0-9-]*',
			self::FILTER_IN => [OriginalRoute::class, 'path2action'],
			self::FILTER_OUT => [OriginalRoute::class, 'action2path'],
		],
	];

	/**
	 * @param string                   $mask     e.g. 'path/<action>/<id \d{1,3}>'
	 * @param class-string<IPresenter> $class
	 * @param array<mixed>             $metadata default values or metadata
	 */
	public function __construct(string $mask, string $class, array $metadata = [])
	{
		$metadata[self::PRESENTER_KEY] = $class;

		if (!isset($metadata['action'])) {
			$metadata['action'] = 'default';
		}

		$this->defaultMeta += self::UI_META;
		parent::__construct($mask, $metadata);
	}

}
