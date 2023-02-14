<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Scripts;

use OriCMF\UI\Control\BaseControlTemplate;

final class ScriptsControlTemplate extends BaseControlTemplate
{

	public ScriptsControl $control;

	/**
	 * @var array<array<mixed>>
	 * @phpstan-var array<array{string, bool}>
	 */
	public array $scripts = [];

}
