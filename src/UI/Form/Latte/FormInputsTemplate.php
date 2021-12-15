<?php declare(strict_types = 1);

namespace OriCMF\UI\Form\Latte;

use Nette\Bridges\ApplicationLatte\Template;
use Nette\Forms\Control;

final class FormInputsTemplate extends Template
{

	public const PATH = __DIR__ . '/FormInputs.latte';

	public Control $input;

}
