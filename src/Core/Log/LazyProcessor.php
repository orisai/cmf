<?php declare(strict_types = 1);

namespace OriCMF\Core\Log;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Nette\DI\Container;
use Orisai\Exceptions\Logic\InvalidArgument;

final class LazyProcessor implements ProcessorInterface
{

	private ProcessorInterface|null $processor = null;

	public function __construct(private string $serviceName, private Container $container)
	{
	}

	private function getProcessor(): ProcessorInterface
	{
		if ($this->processor === null) {
			$service = $this->container->getByName($this->serviceName);

			if (!$service instanceof ProcessorInterface) {
				$processorClass = ProcessorInterface::class;
				$serviceClass = $service::class;

				throw InvalidArgument::create()
					->withMessage(
						"Service '$this->serviceName' has to be instance of '$processorClass', '$serviceClass' given.",
					);
			}

			$this->processor = $service;
		}

		return $this->processor;
	}

	public function __invoke(LogRecord $record): LogRecord
	{
		return $this->getProcessor()($record);
	}

}
