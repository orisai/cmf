<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Links;

use OriCMF\UI\Control\BaseControl;
use function asort;

/**
 * @property-read LinksTemplate $template
 */
class LinksControl extends BaseControl
{

	/** @var array<string> */
	private array $links = [];

	/** @var array<string> */
	private array $alternateLanguages = [];

	/** @var array<mixed> */
	private array $alternateFeeds = [];

	public function addLink(string $href, string $rel): self
	{
		$this->links[$href] = $rel;

		return $this;
	}

	/**
	 * Adds alternate language
	 * <link rel="alternate" href="$href" hreflang="$hreflang">
	 *
	 * @return $this
	 */
	public function addAlternateLanguage(string $href, string $hreflang): self
	{
		$this->alternateLanguages[$href] = $hreflang;

		return $this;
	}

	/**
	 * Adds alternate feed
	 * <link rel="alternate" href="$href" type="$type" title="$title">
	 * <link rel="alternate" href="https://feeds.feedburner.com/example" type="application/rss+xml" title="RSS">
	 *
	 * @return $this
	 */
	public function addAlternateFeed(string $href, string $type, string $title): self
	{
		$this->alternateFeeds[$href] = [
			'type' => $type,
			'title' => $title,
		];

		return $this;
	}

	public function render(): void
	{
		$this->template->links = $this->links;
		$this->template->alternateFeeds = $this->alternateFeeds;
		asort($this->alternateLanguages);
		$this->template->alternateLanguages = $this->alternateLanguages;

		$this->template->render();
	}

}
