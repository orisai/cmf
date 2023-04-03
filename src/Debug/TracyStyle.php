<?php declare(strict_types = 1);

namespace OriCMF\Debug;

use Orisai\Exceptions\Logic\InvalidState;
use Tracy\Debugger;

final class TracyStyle
{

	public static function enable(): void
	{
		if (Debugger::isEnabled()) {
			$fn = __FUNCTION__;
			$debugger = Debugger::class;

			throw InvalidState::create()
				->withMessage("Call '$fn()' before '$debugger' is enabled");
		}

		Debugger::$customCssFiles[] = __DIR__ . '/tracy.css';
	}

}
