<?php declare(strict_types = 1);

namespace OriCMF\UI\Control;

use OriCMF\UI\Template\UITemplate;
use OriCMF\UI\TemplateLocator\Template\FlexibleControlTemplate;
use OriCMF\UI\TemplateLocator\Template\FlexibleControlTemplateImpl;

abstract class BaseControlTemplate extends UITemplate implements FlexibleControlTemplate
{

	use FlexibleControlTemplateImpl;

}
