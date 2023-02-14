<?php declare(strict_types = 1);

namespace OriCMF\Error\Admin;

use OriCMF\Admin\Presenter\BaseAdminPresenter;
use OriCMF\Error\UI\ErrorPresenterUtil;

/**
 * @property-read ErrorPresenterTemplate $template
 */
final class ErrorPresenter extends BaseAdminPresenter
{

	use ErrorPresenterUtil;

}
