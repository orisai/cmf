<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Breadcrumb;

use OriCMF\UI\Control\BaseControlTemplate;

final class BreadcrumbControlTemplate extends BaseControlTemplate
{

	public BreadcrumbControl $control;

	/**
	 * @var array<array<mixed>>
	 * @phpstan-var array<array{title: string, uri: string|null}>
	 */
	public array $links;

}
