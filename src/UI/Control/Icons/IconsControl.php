<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Icons;

use OriCMF\UI\Control\BaseControl;
use function implode;
use function strcmp;
use function uasort;

/**
 * @property-read IconsTemplate $template
 */
final class IconsControl extends BaseControl
{

	private string|null $favicon = null;

	/** @var array<mixed> */
	private array $icons = [];

	/**
	 * @return $this
	 */
	public function setFavicon(string $favicon): self
	{
		$this->favicon = $favicon;

		return $this;
	}

	/**
	 * @param array<string> $sizes
	 * @return $this
	 */
	public function addIcon(string $href, array $sizes = [], string|null $type = null, string $rel = 'icon'): self
	{
		$this->icons[] = [
			'href' => $href,
			'rel' => $rel,
			'sizes' => implode(' ', $sizes),
			'type' => $type,
		];

		return $this;
	}

	/**
	 * @param array<string> $sizes
	 * @return $this
	 */
	public function addApple(string $href, array $sizes = []): self
	{
		$this->addIcon($href, $sizes, null, 'apple-touch-icon');

		return $this;
	}

	/**
	 * @param array<string> $sizes
	 * @return $this
	 */
	public function addApplePrecomposed(string $href, array $sizes = []): self
	{
		$this->addIcon($href, $sizes, null, 'apple-touch-icon-precomposed');

		return $this;
	}

	public function render(): void
	{
		// Sort icons by rel
		uasort($this->icons, static fn ($a, $b): int => strcmp($a['rel'], $b['rel']));

		$this->template->favicon = $this->favicon;
		$this->template->icons = $this->icons;

		$this->template->render();
	}

}
