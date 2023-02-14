<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\Latte\Nodes;

use Generator;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Essential\RawPhpExtension;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Forms\Form;
use OriCMF\UI\Form\Latte\FormErrorsTemplate;
use function assert;

final class FormErrorsNode extends StatementNode
{

	private function __construct(public readonly ExpressionNode $formNode)
	{
	}

	public static function create(Tag $tag): self
	{
		$tag->outputMode = $tag::OutputKeepIndentation;
		$tag->expectArguments();

		$formNode = $tag->parser->parseExpression();

		return new self($formNode);
	}

	/**
	 * @uses self::renderErrors()
	 */
	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo %raw::renderErrors(%node, $this->global->uiPresenter)%line;',
			self::class,
			$this->formNode,
			$this->position,
		);
	}

	public static function renderErrors(Form $form, Presenter $presenter): string
	{
		$factory = $presenter->getTemplateFactory();
		assert($factory instanceof TemplateFactory);

		$template = $factory->createTemplate(null, FormErrorsTemplate::class);
		$template->getLatte()->addExtension(new RawPhpExtension());

		$template->setFile(FormErrorsTemplate::Path);
		$template->form = $form;

		return $template->renderToString();
	}

	public function &getIterator(): Generator
	{
		yield $this->formNode;
	}

}
