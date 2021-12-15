<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\NoScript;

use Nette\Utils\Html;
use OriCMF\UI\Control\Base\BaseControlTemplate;

final class NoScriptTemplate extends BaseControlTemplate
{

	public NoScriptControl $control;

	/** @var array<Html> */
	public array $noScripts;

}
