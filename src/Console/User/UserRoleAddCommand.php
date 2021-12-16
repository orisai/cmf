<?php declare(strict_types = 1);

namespace OriCMF\Console\User;

use Nextras\Orm\Model\IModel;
use OriCMF\Core\Email\EmailRepository;
use OriCMF\Core\Role\RoleRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UserRoleAddCommand extends Command
{

	public function __construct(
		private IModel $model,
		private RoleRepository $roleRepository,
		private EmailRepository $emailRepository,
	)
	{
		parent::__construct();
	}

	public static function getDefaultName(): string
	{
		return 'user:role:add';
	}

	public static function getDefaultDescription(): string
	{
		return 'Add role to user';
	}

	protected function configure(): void
	{
		parent::configure();

		$this->addArgument('email', InputArgument::REQUIRED, 'Email address');
		$this->addArgument('role', InputArgument::REQUIRED, 'Role name');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$roleName = $input->getArgument('role');
		$emailAddress = $input->getArgument('email');

		$role = $this->roleRepository->getBy([
			'name' => $roleName,
		]);

		if ($role === null) {
			$output->writeln("<error>Role with name {$roleName} does not exist.</error>");

			return 1;
		}

		$email = $this->emailRepository->getBy([
			'emailAddress' => $emailAddress,
		]);

		if ($email === null) {
			$output->writeln("<error>Email address {$emailAddress} does not exist.</error>");

			return 1;
		}

		$user = $email->user;

		if (!$user->roles->has($role)) {
			$user->roles->add($role);
			$this->model->persistAndFlush($user);
		} else {
			$output->writeln(
				"<info>User with email {$emailAddress} already has role {$roleName}.</info>",
				OutputInterface::VERBOSITY_VERBOSE,
			);
		}

		return 0;
	}

}
