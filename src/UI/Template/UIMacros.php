<?php declare(strict_types = 1);

namespace OriCMF\UI\Template;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use function str_replace;

final class UIMacros extends MacroSet
{

	public static function install(Compiler $compiler): void
	{
		$macros = new static($compiler);
		$macros->addMacro('alink', [$macros, 'macroAlink']);
	}

	public function macroAlink(MacroNode $node, PhpWriter $writer): string
	{
		$expr = <<<'LATTE_EXPR'
$_ori_link = @args;
echo %escape(%modify(
	$this->global->uiPresenter->link($_ori_link->getDestination(), $_ori_link->getArguments())
))@line;
LATTE_EXPR;
		$expr = str_replace('@args', $node->args, $expr);

		$line = $node->startLine
			? " /* line $node->startLine */"
			: '';
		$expr = str_replace('@line', $line, $expr);

		return $writer::using($node, $this->getCompiler())
			->write($expr);
	}

}
