<?php declare(strict_types = 1);

namespace OriCMF\Error\Public;

use OriCMF\UI\Presenter\BasePresenterTemplate;

final class ErrorTemplate extends BasePresenterTemplate
{

	public ErrorPresenter $control;

	public string $title;

	public string $message;

}