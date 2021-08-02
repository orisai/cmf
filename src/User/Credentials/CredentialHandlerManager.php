<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Credentials;

final class CredentialHandlerManager
{

	/**
	 * @param array<CredentialHandler> $handlers
	 */
	public function __construct(private array $handlers)
	{
	}

	/**
	 * @return array<CredentialHandler>
	 */
	public function getHandlers(): array
	{
		return $this->handlers;
	}

}
