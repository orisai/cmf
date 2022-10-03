<?php declare(strict_types = 1);

namespace OriCMF\UI\TemplateLocator\Template;

use Nette\Application\AbortException;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Template;
use Nette\FileNotFoundException;
use OriCMF\UI\TemplateLocator\Exception\NoTemplateFound;
use OriCMF\UI\TemplateLocator\Locator\PresenterTemplateLocator;
use Orisai\Exceptions\Logic\NotImplemented;
use function implode;
use function is_string;
use function preg_match;
use function sprintf;

trait FlexibleTemplatePresenter
{

	private PresenterTemplateLocator $templateLocator;

	final public function injectTemplatable(PresenterTemplateLocator $templateLocator): void
	{
		$this->templateLocator = $templateLocator;
	}

	/**
	 * @return array<string>
	 * @throws NotImplemented
	 *
	 * @internal
	 */
	final public function formatLayoutTemplateFiles(): array
	{
		throw NotImplemented::create()
			->withMessage(sprintf(
				'Implementation of \'%s\' is in findLayoutTemplateFiles(), do not call method directly',
				__METHOD__,
			));
	}

	/**
	 * @internal
	 */
	final public function findLayoutTemplateFile(): string|null
	{
		$layout = $this->getLayout();

		if ($layout === false) {
			return null;
		}

		if (is_string($layout) && preg_match('#/|\\\\#', $layout) === 1) {
			return $layout;
		}

		if ($layout === true || $layout === null) {
			$layout = 'layout';
		}

		try {
			return $this->templateLocator->getLayoutTemplatePath($this, $layout);
		} catch (NoTemplateFound $exception) {
			$inlinePaths = implode("\n", $exception->getShortTriedPaths());

			throw new FileNotFoundException(
				"Layout not found. None of the following templates exists:\n$inlinePaths",
				0,
				$exception,
			);
		}
	}

	/**
	 * @return array<string>
	 * @throws NotImplemented
	 *
	 * @internal
	 */
	final public function formatTemplateFiles(): array
	{
		throw NotImplemented::create()
			->withMessage(sprintf(
				'Implementation of \'%s\' is in sendTemplates(), do not call method directly',
				__METHOD__,
			));
	}

	/**
	 * @throws BadRequestException
	 * @throws AbortException
	 */
	final public function sendTemplate(Template|null $template = null): void
	{
		$template ??= $this->getTemplate();

		if ($template->getFile() === null) {
			try {
				$file = $this->templateLocator->getActionTemplatePath($this, $this->getView());
			} catch (NoTemplateFound $exception) {
				$inlinePaths = implode("\n", $exception->getShortTriedPaths());

				throw new BadRequestException(
					"Page not found. None of the following templates exists:\n$inlinePaths",
					0,
					$exception,
				);
			}

			$template->setFile($file);
		}

		$this->sendResponse(new TextResponse($template));
	}

}
