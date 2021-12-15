<?php declare(strict_types = 1);

namespace OriCMF\UI\Presenter;

trait NoLogin
{

	protected function isLoginRequired(): bool
	{
		return false;
	}

	protected function checkUserIsLoggedIn(): void
	{
		// Disables login requirements
	}

}
