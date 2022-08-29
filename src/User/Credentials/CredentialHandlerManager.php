<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials;

final class CredentialHandlerManager
{

	/**
	 * @param array<CredentialHandler> $handlers
	 */
	public function __construct(private readonly array $handlers)
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
