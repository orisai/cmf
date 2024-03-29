<?php declare(strict_types = 1);

namespace OriCMF\Auth\UI;

use OriCMF\User\DB\User;
use OriCMF\User\DB\UserRepository;
use Orisai\Auth\Authentication\BaseFirewall;
use Orisai\Auth\Authentication\Exception\NotLoggedIn;
use Orisai\Auth\Authentication\IdentityRefresher;
use Orisai\Auth\Authentication\LoginStorage;
use Orisai\Auth\Authorization\Authorizer;
use Orisai\Clock\Clock;

/**
 * @phpstan-extends BaseFirewall<UserIdentity>
 *
 * @internal
 */
abstract class BaseUIFirewall extends BaseFirewall
{

	public function __construct(
		private readonly UserRepository $userRepository,
		LoginStorage $storage,
		IdentityRefresher $refresher,
		Authorizer $authorizer,
		Clock|null $clock = null,
	)
	{
		parent::__construct($storage, $refresher, $authorizer, $clock);
	}

	public function getUser(): User
	{
		$identity = $this->fetchIdentity();

		if ($identity === null) {
			throw NotLoggedIn::create(static::class, __FUNCTION__);
		}

		return $this->userRepository->getByIdChecked($identity->getId());
	}

	public function getImpersonator(): User|null
	{
		$impersonator = $this->getIdentity()->getImpersonator();

		if ($impersonator === null) {
			return null;
		}

		return $this->userRepository->getByIdChecked($impersonator->getId());
	}

}
