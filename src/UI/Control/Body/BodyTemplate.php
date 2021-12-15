<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Body;

use Nette\Utils\Html;
use OriCMF\UI\Control\Base\BaseControlTemplate;

final class BodyTemplate extends BaseControlTemplate
{

	public BodyControl $control;

	public Html $bodyStart;

	public Html $bodyEnd;

}
