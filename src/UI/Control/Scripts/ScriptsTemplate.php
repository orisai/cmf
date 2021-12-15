<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Scripts;

use OriCMF\UI\Control\Base\BaseControlTemplate;

final class ScriptsTemplate extends BaseControlTemplate
{

	public ScriptsControl $control;

	/**
	 * @var array<array<mixed>>
	 * @phpstan-var array<array{string, bool}>
	 */
	public array $scripts = [];

}
