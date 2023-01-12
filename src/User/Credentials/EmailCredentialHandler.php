<?php declare(strict_types = 1);

namespace OriCMF\User\Credentials;

use Nextras\Orm\Model\IModel;
use OriCMF\Email\DB\Email;
use OriCMF\Email\DB\EmailRepository;
use OriCMF\User\Credentials\Exception\CredentialAlreadyInUse;
use Orisai\TranslationContracts\TranslatableMessage;

final class EmailCredentialHandler implements VerifyingCredentialHandler
{

	public function __construct(private readonly IModel $model, private readonly EmailRepository $emailRepository)
	{
	}

	/**
	 * @throws CredentialAlreadyInUse
	 */
	public function beforeRegistration(object $credential): Email|null
	{
		if (!$credential instanceof Email) {
			return null;
		}

		$existing = $this->emailRepository->getBy(['emailAddress' => $credential->emailAddress]);

		if ($existing !== null && $existing->id !== $credential->id) {
			throw CredentialAlreadyInUse::create(
				$credential,
				new TranslatableMessage('ori.cmf.login.email.alreadyInUse'),
			);
		}

		$this->model->persist($credential);

		return $credential;
	}

	public function requestVerification(object $credential): void
	{
		if (!$credential instanceof Email) {
			return;
		}

		//TODO - send activation email
	}

}
