<?php declare(strict_types = 1);

namespace OriCMF\UI\Template;

use Nette\Application\LinkGenerator;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use function assert;

final class TemplateCreator
{

	public function __construct(
		private TemplateFactory $templateFactory,
		private LinkGenerator $linkGenerator,
	)
	{
	}

	/**
	 * @template T of Template
	 * @param class-string<T> $templateClass
	 * @return T
	 */
	public function createTemplate(string $templateClass): Template
	{
		$template = $this->templateFactory->createTemplate(class: $templateClass);
		assert($template instanceof $templateClass);

		$latte = $template->getLatte();
		$latte->addProvider('uiControl', $this->linkGenerator);

		return $template;
	}

}
