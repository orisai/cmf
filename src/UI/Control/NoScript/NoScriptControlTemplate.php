<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\NoScript;

use Nette\Utils\Html;
use OriCMF\UI\Control\BaseControlTemplate;

final class NoScriptControlTemplate extends BaseControlTemplate
{

	public NoScriptControl $control;

	/** @var array<Html> */
	public array $noScripts;

}
