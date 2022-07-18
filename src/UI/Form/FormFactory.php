<?php declare(strict_types = 1);

namespace OriCMF\UI\Form;

use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Form as NForm;
use Nette\Forms\Validator;
use function Orisai\Localization\t;

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
		Validator::$messages[NForm::EQUAL] = t('ori.cmf.ui.forms.equal');
		Validator::$messages[NForm::NOT_EQUAL] = t('ori.cmf.ui.forms.notEqual');
		Validator::$messages[NForm::FILLED] = t('ori.cmf.ui.forms.filled');
		Validator::$messages[NForm::BLANK] = t('ori.cmf.ui.forms.blank');
		Validator::$messages[NForm::MIN_LENGTH] = t('ori.cmf.ui.forms.minLength');
		Validator::$messages[NForm::MAX_LENGTH] = t('ori.cmf.ui.forms.maxLength');
		Validator::$messages[NForm::LENGTH] = t('ori.cmf.ui.forms.length');
		Validator::$messages[NForm::EMAIL] = t('ori.cmf.ui.forms.email');
		Validator::$messages[NForm::URL] = t('ori.cmf.ui.forms.url');
		Validator::$messages[NForm::INTEGER] = t('ori.cmf.ui.forms.integer');
		Validator::$messages[NForm::FLOAT] = t('ori.cmf.ui.forms.number');
		Validator::$messages[NForm::NUMERIC] = t('ori.cmf.ui.forms.number');
		Validator::$messages[NForm::MIN] = t('ori.cmf.ui.forms.min');
		Validator::$messages[NForm::MAX] = t('ori.cmf.ui.forms.max');
		Validator::$messages[NForm::RANGE] = t('ori.cmf.ui.forms.range');
		Validator::$messages[NForm::MAX_FILE_SIZE] = t('ori.cmf.ui.forms.maxFileSize');
		Validator::$messages[NForm::MAX_POST_SIZE] = t('ori.cmf.ui.forms.maxPostSize');
		Validator::$messages[NForm::MIME_TYPE] = t('ori.cmf.ui.forms.mimeType');
		Validator::$messages[NForm::IMAGE] = t('ori.cmf.ui.forms.image');
		Validator::$messages[SelectBox::VALID] = t('ori.cmf.ui.forms.select');
		Validator::$messages[UploadControl::VALID] = t('ori.cmf.ui.forms.upload');
	}

	public function create(): Form
	{
		$this->initialize();

		return new Form();
	}

}
