<?php declare(strict_types = 1);

namespace OriCMF\UI\Form\Latte;

use Nette\Bridges\ApplicationLatte\Template;
use Nette\Forms\Form;

final class FormErrorsTemplate extends Template
{

	public const PATH = __DIR__ . '/FormErrors.latte';

	public Form $form;

}
