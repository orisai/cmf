<?php declare(strict_types = 1);

namespace OriCMF\Admin\Error;

use OriCMF\Admin\Presenter\BaseAdminPresenter;
use OriCMF\UI\Error\ErrorPresenterUtil;

/**
 * @property-read ErrorTemplate $template
 */
final class ErrorPresenter extends BaseAdminPresenter
{

	use ErrorPresenterUtil;

}
