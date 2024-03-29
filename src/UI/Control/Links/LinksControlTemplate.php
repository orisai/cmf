<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Links;

use OriCMF\UI\Control\BaseControlTemplate;

final class LinksControlTemplate extends BaseControlTemplate
{

	public LinksControl $control;

	/** @var array<string> */
	public array $links;

	/** @var array<array<string>> */
	public array $alternateFeeds;

	/** @var array<string> */
	public array $alternateLanguages;

}
