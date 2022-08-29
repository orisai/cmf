<?php declare(strict_types = 1);

namespace OriCMF\Auth\UI;

use Orisai\Auth\Authentication\StringIdentity;
use Orisai\Exceptions\Logic\InvalidState;
use function array_key_exists;

final class UserIdentity extends StringIdentity
{

	/**
	 * @param array<string> $roles
	 */
	public function __construct(string $id, array $roles, private UserIdentity|null $impersonator = null)
	{
		parent::__construct($id, $roles);

		if ($impersonator?->getImpersonator() !== null) {
			throw InvalidState::create()
				->withMessage('Impersonator identity is not allowed to have its own impersonator identity.');
		}
	}

	public function getImpersonator(): UserIdentity|null
	{
		return $this->impersonator;
	}

	/**
	 * @return array<mixed>
	 */
	public function __serialize(): array
	{
		$data = parent::__serialize();
		$data['impersonator'] = $this->impersonator;

		return $data;
	}

	/**
	 * @param array<mixed> $data
	 */
	public function __unserialize(array $data): void
	{
		parent::__unserialize($data);
		$this->impersonator = array_key_exists('impersonator', $data)
			? $data['impersonator']
			: $data['puppeteer']; // BC
	}

}
