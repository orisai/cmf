<?php declare(strict_types = 1);

namespace OriCMF\UI\Control\Base;

use OriCMF\UI\Template\Locator\ControlTemplateLocator;
use OriCMF\UI\Template\UITemplate;

abstract class BaseControlTemplate extends UITemplate
{

	private ControlTemplateLocator $templateLocator;

	private string $view = ControlTemplateLocator::DEFAULT_VIEW_NAME;

	private string|null $file = null;

	public function setView(string $view): void
	{
		$this->view = $view;
		$this->file = null;
	}

	/**
	 * @return $this
	 */
	public function setFile(string $file): static
	{
		$this->file = $file;

		return $this;
	}

	/**
	 * @param array<mixed> $params
	 */
	public function render(string|null $file = null, array $params = []): void
	{
		parent::render($this->getFilePath($file), $params);
	}

	/**
	 * @param array<mixed> $params
	 */
	public function renderToString(string|null $file = null, array $params = []): string
	{
		return parent::renderToString($this->getFilePath($file), $params);
	}

	/**
	 * @internal
	 */
	public function setTemplateLocator(ControlTemplateLocator $templateLocator): void
	{
		$this->templateLocator = $templateLocator;
	}

	private function getFilePath(string|null $file): string
	{
		if ($file === null && ($file = $this->file) === null) {
			return $this->templateLocator->getTemplatePath($this->control, $this->view);
		}

		return $file;
	}

	public function __toString(): string
	{
		return $this->renderToString();
	}

}
