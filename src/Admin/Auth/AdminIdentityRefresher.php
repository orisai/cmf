<?php declare(strict_types = 1);

namespace OriCMF\Admin\Auth;

use OriCMF\Core\User\UserRepository;
use OriCMF\UI\Auth\BaseUIIdentityRefresher;
use OriCMF\UI\Auth\UserIdentity;
use OriCMF\UI\Auth\UserIdentityCreator;
use Orisai\Auth\Authentication\Exception\IdentityExpired;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authorization\Authorizer;

final class AdminIdentityRefresher extends BaseUIIdentityRefresher
{

	public function __construct(
		UserRepository $userRepository,
		private Authorizer $authorizer,
		private UserIdentityCreator $identityCreator,
	)
	{
		parent::__construct($userRepository);
	}

	public function refresh(Identity $identity): UserIdentity
	{
		$puppeteer = $this->refreshPuppeteer($identity);

		// Puppeteer is no longer allowed to puppet, return user to own identity
		if ($puppeteer !== null && !$this->authorizer->isAllowed($puppeteer, Authorizer::ROOT_PRIVILEGE)) {
			return $puppeteer;
		}

		$newIdentity = $this->identityCreator->create(
			$this->getUser($identity),
			$puppeteer,
		);

		if (!$this->authorizer->isAllowed($newIdentity, 'ori.administration.entry')) {
			throw IdentityExpired::create();
		}

		return $newIdentity;
	}

}
