<?php declare(strict_types = 1);

namespace OriCMF\UI\Robots;

use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Application\Response;
use Nette\Application\Responses\CallbackResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use function rawurlencode;
use function strlen;

final class RobotsPresenter implements IPresenter
{

	public function run(Request $request): Response
	{
		return new CallbackResponse(static function (IRequest $request, IResponse $response): void {
			$response->setContentType('text/plain');
			$name = 'robots.txt';
			$response->setHeader(
				'Content-Disposition',
				'inline'
				. '; filename="' . $name . '"'
				. '; filename*=utf-8\'\'' . rawurlencode($name),
			);

			$content = <<<'TXT'
User-agent: *
Disallow: /*?do=
Disallow: /*&do=
TXT;

			$response->setHeader('Content-Length', (string) strlen($content));

			echo $content;
		});
	}

}
