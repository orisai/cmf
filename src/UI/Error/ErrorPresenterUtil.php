<?php declare(strict_types = 1);

namespace OriCMF\UI\Error;

use Nette\Application\BadRequestException;
use Nette\Http\IResponse;
use OriCMF\UI\Control\Document\DocumentControl;
use Orisai\Localization\TranslatableMessage;
use Throwable;
use function Orisai\Localization\tm;

/**
 * @internal
 */
trait ErrorPresenterUtil
{

	public function action(Throwable|null $throwable = null): void
	{
		$this->getHttpResponse()->setCode($this->getHttpCode($throwable));

		// Note error in ajax request
		if ($this->isAjax()) {
			$this->payload->error = true;
			$this->sendPayload();
		}
	}

	public function render(Throwable|null $throwable = null): void
	{
		$this->setView(($throwable?->getCode() ?? 400) < 500 ? '4xx' : '5xx');

		[$title, $message] = $this->getTranslations($throwable);
		$this->template->title = $title = tm($title);
		$this->template->message = tm($message);

		$this['document']->setTitle($title);
	}

	protected function configureCanonicalUrl(DocumentControl $document): void
	{
		// Error presenter has no canonical url
	}

	private function getHttpCode(Throwable|null $throwable): mixed
	{
		// User error
		if ($throwable instanceof BadRequestException) {
			$code = $throwable->getCode();

			if ($code >= 500) {
				$code = IResponse::S500_INTERNAL_SERVER_ERROR;
			} elseif ($code < 400) {
				$code = IResponse::S404_NOT_FOUND;
			}

			return $code;
		}

		// Direct access, act as user error
		if ($throwable === null) {
			return IResponse::S404_NOT_FOUND;
		}

		// Real error
		return IResponse::S500_INTERNAL_SERVER_ERROR;
	}

	/**
	 * @return array{TranslatableMessage, TranslatableMessage}
	 */
	private function getTranslations(Throwable|null $throwable): array
	{
		$code = $throwable?->getCode() ?? 400;

		if ($code >= 500) {
			return [
				new TranslatableMessage('ori.cmf.httpError.500.title'),
				new TranslatableMessage('ori.cmf.httpError.500.message'),
			];
		}

		if ($code === 403) {
			return [
				new TranslatableMessage('ori.cmf.httpError.403.title'),
				new TranslatableMessage('ori.cmf.httpError.403.message'),
			];
		}

		if ($code === 404) {
			return [
				new TranslatableMessage('ori.cmf.httpError.404.title'),
				new TranslatableMessage('ori.cmf.httpError.404.message'),
			];
		}

		if ($code === 410) {
			return [
				new TranslatableMessage('ori.cmf.httpError.410.title'),
				new TranslatableMessage('ori.cmf.httpError.410.message'),
			];
		}

		return [
			new TranslatableMessage('ori.cmf.httpError.400.title'),
			new TranslatableMessage('ori.cmf.httpError.400.message'),
		];
	}

}
