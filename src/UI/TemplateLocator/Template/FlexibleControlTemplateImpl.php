<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Template;

use OriCMF\UI\TemplateLocator\Locator\ControlTemplateLocator;

trait FlexibleControlTemplateImpl
{

	private ControlTemplateLocator $templateLocator;

	private string $view = ControlTemplateLocator::DefaultViewName;

	private string|null $file = null;

	final public function setTemplateLocator(ControlTemplateLocator $templateLocator): void
	{
		$this->templateLocator = $templateLocator;
	}

	final public function setView(string $view): void
	{
		$this->view = $view;
		$this->file = null;
	}

	/**
	 * @return $this
	 */
	final public function setFile(string $file): static
	{
		$this->file = $file;

		return $this;
	}

	/**
	 * @param array<mixed> $params
	 */
	final public function render(string|null $file = null, array $params = []): void
	{
		parent::render($this->getFilePath($file), $params);
	}

	/**
	 * @param array<mixed> $params
	 */
	final public function renderToString(string|null $file = null, array $params = []): string
	{
		return parent::renderToString($this->getFilePath($file), $params);
	}

	private function getFilePath(string|null $file): string
	{
		if ($file === null && ($file = $this->file) === null) {
			return $this->templateLocator->getTemplatePath($this->control, $this->view);
		}

		return $file;
	}

	final public function __toString(): string
	{
		return $this->renderToString();
	}

}
