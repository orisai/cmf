<?php declare(strict_types = 1);

namespace OriCMF\Core\User\Command;

use OriCMF\Core\Email\Email;
use OriCMF\Core\Password\Password;
use OriCMF\Core\User\Credentials\Credentials;
use OriCMF\Core\User\Credentials\Exception\CredentialAlreadyInUse;
use OriCMF\Core\User\Register\UserRegistrarGetter;
use OriCMF\Core\User\User;
use OriCMF\Core\User\UserState;
use Orisai\Auth\Passwords\PasswordEncoder;
use Orisai\Localization\TranslatorGetter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function assert;
use function is_bool;

final class UserRegisterCommand extends Command
{

	public function __construct(
		private UserRegistrarGetter $userRegistrarGetter,
		private PasswordEncoder $passwordEncoder,
		private TranslatorGetter $translatorGetter,
	)
	{
		parent::__construct();
	}

	public static function getDefaultName(): string
	{
		return 'user:register';
	}

	public static function getDefaultDescription(): string
	{
		return 'Register new user';
	}

	protected function configure(): void
	{
		parent::configure();

		$this->addArgument('fullName', InputArgument::REQUIRED, 'Full name');
		$this->addArgument('email', InputArgument::REQUIRED, 'Email address');
		$this->addArgument('password', InputArgument::REQUIRED, 'Password');

		$this->addOption('no-verify', null, InputOption::VALUE_NONE, 'Disable user credentials verification');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$fullName = $input->getArgument('fullName');
		$emailAddress = $input->getArgument('email');
		$rawPassword = $input->getArgument('password');
		$noVerify = $input->getOption('no-verify');
		assert(is_bool($noVerify));

		$user = new User($fullName);
		$user->state = UserState::ACTIVE();
		$email = new Email($emailAddress, Email::TYPE_PRIMARY, $user);
		$password = new Password(
			$this->passwordEncoder->encode($rawPassword),
			$user,
		);

		$credentials = new Credentials();
		$credentials->verifyCredentialsOwner = !$noVerify;
		$credentials->addCredential($email);
		$credentials->addCredential($password);

		try {
			$this->userRegistrarGetter->get()->register($user, $credentials);
		} catch (CredentialAlreadyInUse $exception) {
			$message = $exception->getErrorMessage();

			if ($message !== null) {
				$output->writeln($message->translate($this->translatorGetter->get()));
			} else {
				$output->writeln('One of the credentials is already used by existing user.');
			}

			return 1;
		}

		return 0;
	}

}
