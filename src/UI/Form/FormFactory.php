<?php declare(strict_types = 1);

namespace OriCMF\UI\Form;

use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Validator;
use Orisai\Localization\Translator;

final class FormFactory
{

	private bool $initialized = false;

	public function __construct(private Translator $translator)
	{
	}

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
		Validator::$messages[Form::EQUAL] = $this->translator->translate('ori.ui.forms.equal');
		Validator::$messages[Form::NOT_EQUAL] = $this->translator->translate('ori.ui.forms.notEqual');
		Validator::$messages[Form::FILLED] = $this->translator->translate('ori.ui.forms.filled');
		Validator::$messages[Form::BLANK] = $this->translator->translate('ori.ui.forms.blank');
		Validator::$messages[Form::MIN_LENGTH] = $this->translator->translate('ori.ui.forms.minLength');
		Validator::$messages[Form::MAX_LENGTH] = $this->translator->translate('ori.ui.forms.maxLength');
		Validator::$messages[Form::LENGTH] = $this->translator->translate('ori.ui.forms.length');
		Validator::$messages[Form::EMAIL] = $this->translator->translate('ori.ui.forms.email');
		Validator::$messages[Form::URL] = $this->translator->translate('ori.ui.forms.url');
		Validator::$messages[Form::INTEGER] = $this->translator->translate('ori.ui.forms.integer');
		Validator::$messages[Form::FLOAT] = $this->translator->translate('ori.ui.forms.number');
		Validator::$messages[Form::NUMERIC] = $this->translator->translate('ori.ui.forms.number');
		Validator::$messages[Form::MIN] = $this->translator->translate('ori.ui.forms.min');
		Validator::$messages[Form::MAX] = $this->translator->translate('ori.ui.forms.max');
		Validator::$messages[Form::RANGE] = $this->translator->translate('ori.ui.forms.range');
		Validator::$messages[Form::MAX_FILE_SIZE] = $this->translator->translate('ori.ui.forms.maxFileSize');
		Validator::$messages[Form::MAX_POST_SIZE] = $this->translator->translate('ori.ui.forms.maxPostSize');
		Validator::$messages[Form::MIME_TYPE] = $this->translator->translate('ori.ui.forms.mimeType');
		Validator::$messages[Form::IMAGE] = $this->translator->translate('ori.ui.forms.image');
		Validator::$messages[SelectBox::VALID] = $this->translator->translate('ori.ui.forms.select');
		Validator::$messages[UploadControl::VALID] = $this->translator->translate('ori.ui.forms.upload');
	}

	public function create(): Form
	{
		$this->initialize();

		return new Form();
	}

}
