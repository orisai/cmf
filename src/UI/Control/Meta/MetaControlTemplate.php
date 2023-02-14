<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Meta;

use OriCMF\UI\Control\BaseControlTemplate;

final class MetaControlTemplate extends BaseControlTemplate
{

	public MetaControl $control;

	/** @var array<string> */
	public array $httpEquivs;

	/** @var array<string> */
	public array $metasWithName;

	/** @var array<string> */
	public array $metasWithProperty;

}
