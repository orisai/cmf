<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Breadcrumb;

use OriCMF\UI\Control\BaseControl;

/**
 * @property-read BreadcrumbTemplate $template
 */
class BreadcrumbControl extends BaseControl
{

	/**
	 * @var array<array<mixed>>
	 * @phpstan-var array<array{title: string, uri: string|null}>
	 */
	private array $links = [];

	public function addLink(string $title, string|null $uri = null): self
	{
		$this->links[] = [
			'title' => $title,
			'uri' => $uri,
		];

		return $this;
	}

	public function render(): void
	{
		$this->template->links = $this->links;

		$this->template->render();
	}

}
