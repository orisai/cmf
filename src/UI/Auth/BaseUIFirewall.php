<?php declare(strict_types = 1);

namespace OriCMF\UI\Auth;

use Brick\DateTime\Clock;
use OriCMF\Core\User\User;
use OriCMF\Core\User\UserRepository;
use Orisai\Auth\Authentication\BaseFirewall;
use Orisai\Auth\Authentication\Exception\NotLoggedIn;
use Orisai\Auth\Authentication\IdentityRefresher;
use Orisai\Auth\Authentication\LoginStorage;
use Orisai\Auth\Authorization\Authorizer;

/**
 * @phpstan-extends BaseFirewall<UserIdentity>
 *
 * @internal
 */
abstract class BaseUIFirewall extends BaseFirewall
{

	public function __construct(
		private UserRepository $userRepository,
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

	public function getPuppeteer(): User|null
	{
		$puppeteer = $this->getIdentity()->getPuppeteer();

		if ($puppeteer === null) {
			return null;
		}

		return $this->userRepository->getByIdChecked($puppeteer->getId());
	}

}
