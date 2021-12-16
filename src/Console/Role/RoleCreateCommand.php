<?php declare(strict_types = 1);

namespace OriCMF\Console\Role;

use Nextras\Orm\Model\IModel;
use OriCMF\Core\Role\Role;
use OriCMF\Core\Role\RoleRepository;
use Orisai\Auth\Authorization\AuthorizationData;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function implode;

final class RoleCreateCommand extends Command
{

	public function __construct(
		private IModel $model,
		private RoleRepository $roleRepository,
		private AuthorizationData $data,
	)
	{
		parent::__construct();
	}

	public static function getDefaultName(): string
	{
		return 'user:role:create';
	}

	public static function getDefaultDescription(): string
	{
		return 'Create new user role';
	}

	protected function configure(): void
	{
		parent::configure();

		$this->addArgument('role', InputArgument::REQUIRED, 'Role name');

		$this->addOption(
			'immutable',
			null,
			InputOption::VALUE_NONE,
			'Immutable role cannot be changed via UI (useful for creation of supervisor role)',
		);
		$this->addOption(
			'privilege',
			null,
			InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
			'Privilege added to role',
		);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$roleName = $input->getArgument('role');
		$immutable = $input->getOption('immutable');
		$privilegeNames = $input->getOption('privilege');

		$existingRole = $this->roleRepository->getBy([
			'name' => $roleName,
		]);

		if ($existingRole !== null) {
			$output->writeln("<error>Role with name {$roleName} already exists.</error>");

			return 1;
		}

		$missingPrivileges = [];
		foreach ($privilegeNames as $privilegeName) {
			if (!$this->data->privilegeExists($privilegeName)) {
				$missingPrivileges[] = $privilegeName;
			}
		}

		if ($missingPrivileges !== []) {
			$missingPrivilegesInline = implode(', ', $missingPrivileges);
			$output->writeln("<error>Privileges {$missingPrivilegesInline} does not exist.</error>");

			return 1;
		}

		$role = new Role($roleName, $immutable);
		$role->privileges = $privilegeNames;

		$this->model->persistAndFlush($role);

		return 0;
	}

}