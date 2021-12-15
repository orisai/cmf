<?php declare(strict_types = 1);

namespace OriCMF\UI\Admin\Error;

use OriCMF\UI\Admin\Base\Presenter\BaseAdminPresenter;
use OriCMF\UI\Error\ErrorPresenterUtil;

/**
 * @property-read ErrorTemplate $template
 */
final class ErrorPresenter extends BaseAdminPresenter
{

	use ErrorPresenterUtil;

}
