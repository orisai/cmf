<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Credentials;

use Nextras\Orm\Model\IModel;
use OriCMF\Core\Email\Email;
use OriCMF\Core\Email\EmailRepository;
use OriCMF\Core\User\Credentials\Exception\CredentialAlreadyInUse;
use Orisai\Localization\TranslatableMessage;

final class EmailCredentialHandler implements VerifyingCredentialHandler
{

	public function __construct(private IModel $model, private EmailRepository $emailRepository)
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
				new TranslatableMessage('ori.core.log.in.alreadyInUse.email'),
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
