<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\Nodes;

use Generator;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Essential\RawPhpExtension;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Forms\Control;
use OriCMF\UI\Form\Latte\FormInputsTemplate;
use function assert;

final class FormFieldNode extends StatementNode
{

	private function __construct(public readonly ExpressionNode $inputNode)
	{
	}

	public static function create(Tag $tag): self
	{
		$tag->outputMode = $tag::OutputKeepIndentation;
		$tag->expectArguments();

		$inputNode = $tag->parser->parseExpression();

		return new self($inputNode);
	}

	/**
	 * @uses self::renderField()
	 */
	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo %raw::renderField(%node, $this->global->uiPresenter)%line;',
			self::class,
			$this->inputNode,
			$this->position,
		);
	}

	public static function renderField(Control $input, Presenter $presenter): string
	{
		$factory = $presenter->getTemplateFactory();
		assert($factory instanceof TemplateFactory);

		$template = $factory->createTemplate(null, FormInputsTemplate::class);
		$template->getLatte()->addExtension(new RawPhpExtension());

		$template->setFile(FormInputsTemplate::Path);
		$template->input = $input;

		return $template->renderToString();
	}

	public function &getIterator(): Generator
	{
		yield $this->inputNode;
	}

}
