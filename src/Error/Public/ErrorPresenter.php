<?php declare(strict_types = 1);

namespace OriCMF\Error\Public;

use OriCMF\Error\UI\ErrorPresenterUtil;
use OriCMF\Public\Presenter\BasePublicPresenter;
use OriCMF\UI\Presenter\NoLogin;
use Throwable;

/**
 * @property-read ErrorTemplate $template
 */
final class ErrorPresenter extends BasePublicPresenter
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
