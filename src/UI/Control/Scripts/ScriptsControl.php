<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Scripts;

use OriCMF\UI\Control\BaseControl;
use function md5;

/**
 * @property-read ScriptsTemplate $template
 */
final class ScriptsControl extends BaseControl
{

	/**
	 * @var array<array<mixed>>
	 * @phpstan-var array<array{string, bool}>
	 */
	private array $scripts = [];

	public function __construct(private string $build)
	{
	}

	public function addScript(string $src, bool $defer = false): self
	{
		$src .= '?v=' . md5($this->build);
		$this->scripts[] = [$src, $defer];

		return $this;
	}

	public function render(): void
	{
		$this->template->scripts = $this->scripts;
		$this->template->render();
	}

}
