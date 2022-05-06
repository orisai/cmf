<?php declare(strict_types = 1);

namespace OriCMF\UI\Error;

use Nette\Application\BadRequestException;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;
use OriCMF\Front\Error\ErrorPresenter as FrontErrorPresenter;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Throwable;

final class ErrorForwardPresenter extends Presenter
{

	// Codes which are translated and shown to user
	public const MessageSupportedCodes = [400, 403, 404, 410, 500];

	private string $defaultErrorPresenter = ':' . FrontErrorPresenter::class . ':default';

	/**
	 * @var array<array<string>>
	 * @phpstan-var array<array{string, string}>
	 */
	private array $errorPresenters = [];

	public function __construct(private LoggerInterface $logger)
	{
		parent::__construct();
	}

	public function setDefaultErrorPresenter(string $presenter): void
	{
		$this->defaultErrorPresenter = $presenter;
	}

	public function addErrorPresenter(string $presenter, string $regex): void
	{
		$this->errorPresenters[] = [$presenter, $regex];
	}

	public function actionDefault(Throwable $exception, Request|null $request): void
	{
		// Log error
		$this->logger->log(
			$exception instanceof BadRequestException ? LogLevel::INFO : LogLevel::ERROR,
			$exception->getMessage(),
			[
				'presenter' => $request?->getPresenterName() ?? 'unknown',
				'exception' => $exception,
			],
		);

		// Forward to error presenter if matches pattern
		if ($request !== null) {
			foreach ($this->errorPresenters as [$presenter, $regex]) {
				if (Strings::match($request->getPresenterName(), $regex) !== null) {
					$this->forward($presenter, ['throwable' => $exception, 'request' => $request]);
				}
			}
		}

		// Forward to default error presenter
		$this->forward($this->defaultErrorPresenter, ['throwable' => $exception, 'request' => $request]);
	}

}
