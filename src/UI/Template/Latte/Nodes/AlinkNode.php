<?php declare(strict_types = 1);

namespace OriCMF\UI\Template\Latte\Nodes;

use Generator;
use Latte\Compiler\Nodes\Php\ExpressionNode;
use Latte\Compiler\Nodes\Php\ModifierNode;
use Latte\Compiler\Nodes\StatementNode;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Nette\Application\UI\Presenter;
use OriCMF\UI\ActionLink;
use OriCMF\UI\HandleLink;

final class AlinkNode extends StatementNode
{

	private function __construct(
		public readonly ExpressionNode $destinationNode,
		public readonly ModifierNode $modifierNode,
	)
	{
	}

	public static function create(Tag $tag): self
	{
		$tag->outputMode = $tag::OutputKeepIndentation;
		$tag->expectArguments();

		$destinationNode = $tag->parser->parseExpression();

		$modifierNode = $tag->parser->parseModifier();
		$modifierNode->escape = true;
		$modifierNode->check = false;

		return new self($destinationNode, $modifierNode);
	}

	/**
	 * @uses self::generateLink()
	 */
	public function print(PrintContext $context): string
	{
		return $context->format(
			'echo %modify(%raw::generateLink(%node, $this->global->uiPresenter))%line;',
			$this->modifierNode,
			self::class,
			$this->destinationNode,
			$this->position,
		);
	}

	public static function generateLink(ActionLink|HandleLink $link, Presenter $presenter): string
	{
		if ($link instanceof HandleLink) {
			return $link->get();
		}

		return $presenter->link($link->getDestination(), $link->getArguments());
	}

	public function &getIterator(): Generator
	{
		yield $this->destinationNode;
		yield $this->modifierNode;
	}

}
