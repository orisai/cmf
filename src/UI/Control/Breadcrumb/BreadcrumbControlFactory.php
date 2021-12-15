<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Breadcrumb;

interface BreadcrumbControlFactory
{

	public function create(): BreadcrumbControl;

}
