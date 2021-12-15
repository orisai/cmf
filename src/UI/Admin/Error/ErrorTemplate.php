<?php declare(strict_types = 1);

namespace OriCMF\UI\Admin\Error;

use OriCMF\UI\Presenter\Base\BasePresenterTemplate;

final class ErrorTemplate extends BasePresenterTemplate
{

	public ErrorPresenter $control;

	public string $title;

	public string $message;

}
