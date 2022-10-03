<?php declare(strict_types = 1);

namespace OriCMF\Robots;

use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Application\Response;
use OriCMF\UI\Response\FileContentResponse;

final class RobotsPresenter implements IPresenter
{

	public function run(Request $request): Response
	{
		$content = <<<'TXT'
User-agent: *
Disallow: /*?do=
Disallow: /*&do=
TXT;

		return new FileContentResponse('robots.txt', $content, 'text/plain', false);
	}

}
