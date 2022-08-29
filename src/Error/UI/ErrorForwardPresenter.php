<?php declare(strict_types = 1);

namespace OriCMF\Error\UI;

use Nette\Application\BadRequestException;
use Nette\Application\Request;
use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;
use OriCMF\Error\Public\ErrorPresenter as PublicErrorPresenter;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Throwable;

final class ErrorForwardPresenter extends Presenter
{

	private const DefaultErrorPresenter = ':' . PublicErrorPresenter::class . ':default';

	/**
	 * @var array<array<string>>
	 * @phpstan-var array<array{string, string}>
	 */
	private array $errorPresenters = [];

	public function __construct(private readonly LoggerInterface $logger)
	{
		parent::__construct();
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
		$this->forward(self::DefaultErrorPresenter, ['throwable' => $exception, 'request' => $request]);
	}

}
