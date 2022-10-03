<?php declare(strict_types = 1);

namespace OriCMF\UI\Response;

use Nette\Application\Response;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use function basename;
use function rawurlencode;
use function strlen;

final class FileContentResponse implements Response
{

	private string $name;

	public function __construct(
		string $name,
		private string $fileContent,
		private string $contentType = 'application/octet-stream',
		private bool $forceDownload = true,
	)
	{
		$this->name = basename($name);
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getFileContent(): string
	{
		return $this->fileContent;
	}

	public function getContentType(): string
	{
		return $this->contentType;
	}

	public function send(IRequest $httpRequest, IResponse $httpResponse): void
	{
		$httpResponse->setContentType($this->contentType);
		$httpResponse->setHeader(
			'Content-Disposition',
			($this->forceDownload ? 'attachment' : 'inline')
			. '; filename="' . $this->name . '"'
			. '; filename*=utf-8\'\'' . rawurlencode($this->name),
		);
		$httpResponse->setHeader('Content-Length', (string) strlen($this->fileContent));

		echo $this->fileContent;
	}

}
