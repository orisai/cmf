<?php declare(strict_types = 1);

namespace OriCMF\UI\Form\SvelteSelect;

use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Helpers;
use Nette\Utils\Html;
use Nette\Utils\Json;

final class SvelteSelect
{

	private SelectBox|MultiSelectBox $select;

	private bool $isMulti;

	private function __construct()
	{
		// Static constructor is used instead
	}

	public static function fromSelect(SelectBox $select): self
	{
		$self = new self();
		$self->select = $select;
		$self->isMulti = false;

		return $self;
	}

	public static function fromMultiSelect(MultiSelectBox $multiSelect): self
	{
		$self = new self();
		$self->select = $multiSelect;
		$self->isMulti = true;

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

		return Json::encode([
			'items' => $this->select->getItems(),
			'isMulti' => $this->isMulti,
			'id' => $this->select->getHtmlId(),
			'name' => $this->select->getHtmlName(),
			'required' => $this->select->isRequired(),
			'disabled' => $this->select->isDisabled(),
			'placeholder' => $prompt === false ? '' : $prompt,
			'rules' => $rules === [] ? null : Json::encode($rules),
			'selectedValue' => $this->select->getValue(),
		]);
	}

}
