<?php declare(strict_types = 1);

namespace OriCMF\UI\Auth;

use Orisai\Auth\Authentication\StringIdentity;
use Orisai\Exceptions\Logic\InvalidState;

final class UserIdentity extends StringIdentity
{

	/**
	 * @param array<string> $roles
	 */
	public function __construct(string $id, array $roles, private UserIdentity|null $puppeteer = null)
	{
		parent::__construct($id, $roles);

		if ($puppeteer?->getPuppeteer() !== null) {
			throw InvalidState::create()
				->withMessage('Parent identity is not allowed to have its own parent identity.');
		}
	}

	public function getPuppeteer(): UserIdentity|null
	{
		return $this->puppeteer;
	}

	/**
	 * @return array<mixed>
	 */
	public function __serialize(): array
	{
		$data = parent::__serialize();
		$data['puppeteer'] = $this->puppeteer;

		return $data;
	}

	/**
	 * @param array<mixed> $data
	 */
	public function __unserialize(array $data): void
	{
		parent::__unserialize($data);
		$this->puppeteer = $data['puppeteer'];
	}

}
