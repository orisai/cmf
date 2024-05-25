<?php declare(strict_types = 1);

namespace OriCMF\UI\Form;

use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Form as NForm;
use Nette\Forms\Validator;
use function Orisai\TranslationContracts\t;

final class FormFactory
{

	private bool $initialized = false;

	private function initialize(): void
	{
		if ($this->initialized) {
			return;
		}

		$this->configureDefaultMessages();

		$this->initialized = true;
	}

	private function configureDefaultMessages(): void
	{
		Validator::$messages[NForm::Equal] = t('ori.cmf.ui.forms.equal');
		Validator::$messages[NForm::NotEqual] = t('ori.cmf.ui.forms.notEqual');
		Validator::$messages[NForm::Filled] = t('ori.cmf.ui.forms.filled');
		Validator::$messages[NForm::Blank] = t('ori.cmf.ui.forms.blank');
		Validator::$messages[NForm::MinLength] = t('ori.cmf.ui.forms.minLength');
		Validator::$messages[NForm::MaxLength] = t('ori.cmf.ui.forms.maxLength');
		Validator::$messages[NForm::Length] = t('ori.cmf.ui.forms.length');
		Validator::$messages[NForm::Email] = t('ori.cmf.ui.forms.email');
		Validator::$messages[NForm::URL] = t('ori.cmf.ui.forms.url');
		Validator::$messages[NForm::Integer] = t('ori.cmf.ui.forms.integer');
		Validator::$messages[NForm::Float] = t('ori.cmf.ui.forms.number');
		Validator::$messages[NForm::Numeric] = t('ori.cmf.ui.forms.number');
		Validator::$messages[NForm::Min] = t('ori.cmf.ui.forms.min');
		Validator::$messages[NForm::Max] = t('ori.cmf.ui.forms.max');
		Validator::$messages[NForm::Range] = t('ori.cmf.ui.forms.range');
		Validator::$messages[NForm::MaxFileSize] = t('ori.cmf.ui.forms.maxFileSize');
		Validator::$messages[NForm::MaxPostSize] = t('ori.cmf.ui.forms.maxPostSize');
		Validator::$messages[NForm::MimeType] = t('ori.cmf.ui.forms.mimeType');
		Validator::$messages[NForm::Image] = t('ori.cmf.ui.forms.image');
		Validator::$messages[SelectBox::Valid] = t('ori.cmf.ui.forms.select');
		Validator::$messages[UploadControl::Valid] = t('ori.cmf.ui.forms.upload');
	}

	public function create(): Form
	{
		$this->initialize();

		return new Form();
	}

}
