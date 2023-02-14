<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Body;

use Nette\Utils\Html;
use OriCMF\UI\Control\BaseControlTemplate;

final class BodyControlTemplate extends BaseControlTemplate
{

	public BodyControl $control;

	public Html $bodyStart;

	public Html $bodyEnd;

}
