<?php declare(strict_types = 1);

namespace OriCMF\UI\Template;

use Latte\CompileException;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;
use OriCMF\UI\Form\Latte\FormErrorsTemplate;
use OriCMF\UI\Form\Latte\FormInputsTemplate;
use function str_replace;

final class LatteComponentMacros extends MacroSet
{

	public static function install(Compiler $compiler): void
	{
		$macros = new self($compiler);
		$macros->addMacro('c:formField', [$macros, 'macroFormField']);
		$macros->addMacro('c:formErrors', [$macros, 'macroFormErrors']);
		$macros->addMacro('formContext', [$macros, 'macroFormContext']);
	}

	public function macroFormField(MacroNode $node, PhpWriter $writer): string
	{
		$expr = <<<'LATTE_EXPR'
@templateVar = $this->global->uiPresenter->getTemplateFactory()->createTemplate(null, @templateClass::class);
@templateVar->input = @args;
@templateVar->setFile('@file');
echo @templateVar->renderToString();@line
LATTE_EXPR;

		$line = $node->startLine
			? " /* line $node->startLine */"
			: '';

		$expr = str_replace(
			['@templateVar', '@templateClass', '@args', '@file', '@line'],
			['$_ori_formInput_template', FormInputsTemplate::class, $node->args, FormInputsTemplate::PATH, $line],
			$expr,
		);

		return $writer->write($expr);
	}

	public function macroFormErrors(MacroNode $node, PhpWriter $writer): string
	{
		$expr = <<<'LATTE_EXPR'
@templateVar = $this->global->uiPresenter->getTemplateFactory()->createTemplate(null, @templateClass::class);
@templateVar->form = @args;
@templateVar->setFile('@file');
echo @templateVar->renderToString();@line
LATTE_EXPR;

		$line = $node->startLine
			? " /* line $node->startLine */"
			: '';

		$expr = str_replace(
			['@templateVar', '@templateClass', '@args', '@file', '@line'],
			['$_ori_formInput_template', FormErrorsTemplate::class, $node->args, FormErrorsTemplate::PATH, $line],
			$expr,
		);

		return $writer->write($expr);
	}

	public function macroFormContext(MacroNode $node, PhpWriter $writer): string
	{
		$name = $node->tokenizer->fetchWord();
		if ($name === null) {
			throw new CompileException('Missing name in ' . $node->getNotation());
		}

		$line = "/* line $node->startLine */";

		$expr = <<<'LATTE_EXPR'
$this->global->formsStack[] = @name@line;
LATTE_EXPR;

		$expr = str_replace(
			['@name', '@line'],
			[$name, $line],
			$expr,
		);

		return $writer->write($expr);
	}

}
