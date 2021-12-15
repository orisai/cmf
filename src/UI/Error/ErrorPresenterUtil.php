<?php declare(strict_types = 1);

namespace OriCMF\UI\Error;

use Nette\Application\BadRequestException;
use Nette\Http\IResponse;
use Throwable;
use function in_array;

/**
 * @internal
 */
trait ErrorPresenterUtil
{

	private bool $is4xx;

	private int $code;

	public function action(Throwable|null $throwable = null): void
	{
		if ($throwable === null) {
			// Direct access, act as user error
			$code = IResponse::S404_NOT_FOUND;
			$is4xx = true;
		} elseif ($throwable instanceof BadRequestException) {
			// User error
			$code = $throwable->getCode();
			$is4xx = $code >= 400 && $code <= 499;

			if (!in_array($code, ErrorForwardPresenter::MESSAGE_SUPPORTED_CODES, true)) {
				$code = $is4xx
					? 400
					: 500;
			}
		} else {
			// Real error
			$code = IResponse::S500_INTERNAL_SERVER_ERROR;
			$is4xx = false;
		}

		$this->is4xx = $is4xx;
		$this->code = $code;

		$this->getHttpResponse()->setCode($code);

		// Note error in ajax request
		if ($this->isAjax()) {
			$this->payload->error = true;
			$this->sendPayload();
		}
	}

	public function render(Throwable|null $throwable = null): void
	{
		$t = $this->translator->toFunction();

		$this->setView($this->is4xx ? '4xx' : '5xx');

		$this->template->title = $title = $t("ori.ui.httpError.$this->code.title");
		$this->template->message = $t("ori.ui.httpError.$this->code.message");

		$this['document']->setTitle($title);
	}

	protected function configureCanonicalUrl(): void
	{
		// Error presenter has no canonical url
	}

}
