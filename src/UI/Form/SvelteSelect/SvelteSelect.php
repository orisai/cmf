<?php declare(strict_types = 1);

namespace OriCMF\UI\Form\SvelteSelect;

use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Helpers;
use Nette\Utils\Html;
use Nette\Utils\Json;
use Orisai\Exceptions\Logic\InvalidState;
use function get_debug_type;
use function is_object;

final class SvelteSelect
{

	private SelectBox|MultiSelectBox $select;

	private bool $multiple;

	private function __construct()
	{
		// Static constructor is used instead
	}

	public static function fromSelect(SelectBox $select): self
	{
		$self = new self();
		$self->select = $select;
		$self->multiple = false;

		return $self;
	}

	public static function fromMultiSelect(MultiSelectBox $multiSelect): self
	{
		$self = new self();
		$self->select = $multiSelect;
		$self->multiple = true;

		return $self;
	}

	public function toHtml(): Html
	{
		return Html::el('div')
			->addAttributes([
				'svelte-component' => 'Select',
				'svelte-config' => $this->getJsonConfig(),
			]);
	}

	private function getJsonConfig(): string
	{
		$rules = Helpers::exportRules($this->select->getRules());
		$prompt = $this->select instanceof SelectBox ? $this->select->getPrompt() : false;

		if (is_object($prompt)) {
			$promptType = get_debug_type($prompt);

			throw InvalidState::create()
				->withMessage("Prompt object (of type '$promptType') is currently not supported.");
		}

		return Json::encode([
			'netteItems' => $this->select->getItems(),
			'netteValue' => $this->select->getValue(),
			'hasErrors' => $this->select->hasErrors(),
			'multiple' => $this->multiple,
			'id' => $this->select->getHtmlId(),
			'name' => $this->select->getHtmlName(),
			'required' => $this->select->isRequired(),
			'disabled' => $this->select->isDisabled(),
			'placeholder' => $prompt === false ? '' : $prompt,
			'rules' => Json::encode($rules),
		]);
	}

}
