<?php declare(strict_types = 1);

namespace OriCMF\UI\Front\Error;

use OriCMF\UI\Error\ErrorPresenterUtil;
use OriCMF\UI\Front\Base\Presenter\BaseFrontPresenter;
use OriCMF\UI\Presenter\NoLogin;
use Throwable;

/**
 * @property-read ErrorTemplate $template
 */
final class ErrorPresenter extends BaseFrontPresenter
{

	use NoLogin;
	use ErrorPresenterUtil {
		render as utilRender;
	}

	public function render(Throwable|null $throwable = null): void
	{
		$this->utilRender($throwable);
		$this['document-head-meta']->setRobots(['noindex']);
	}

}
