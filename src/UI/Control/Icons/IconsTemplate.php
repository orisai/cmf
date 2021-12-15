<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Icons;

use OriCMF\UI\Control\Base\BaseControlTemplate;

final class IconsTemplate extends BaseControlTemplate
{

	public IconsControl $control;

	public string|null $favicon;

	/** @var array<mixed> */
	public array $icons;

}
