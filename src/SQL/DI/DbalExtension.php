<?php declare(strict_types = 1);

namespace OriCMF\Core\SQL\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nextras\Dbal\Bridges\NetteTracy\BluescreenQueryPanel;
use Nextras\Dbal\Bridges\NetteTracy\ConnectionPanel;
use Nextras\Dbal\Connection;
use Nextras\Dbal\Drivers\IDriver;
use stdClass;
use Tracy\BlueScreen;

/**
 * @property-read stdClass $config
 */
final class DbalExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'debug' => Expect::bool(false),
			'panelQueryExplain' => Expect::bool(true),
			'sqlProcessorFactory' => Expect::anyOf(Expect::string(), Expect::type(Statement::class)),
			'connections' => Expect::arrayOf(
				Expect::structure([
					'autowired' => Expect::bool(true),

					'driver' => Expect::anyOf(
						'mysqli',
						'pdo_mysql',
						'pgsql',
						'pdo_pgsql',
						'sqlsrv',
						'pdo_sqlsrv',
					)->required(),
					'host' => Expect::string(),
					'port' => Expect::int(),
					'username' => Expect::string(),
					'password' => Expect::string(),
					'database' => Expect::string(),
					'connectionTz' => Expect::string(IDriver::TIMEZONE_AUTO_PHP_NAME),
					'nestedTransactionsWithSavepoint' => Expect::bool(true),

					// mysql only
					'charset' => Expect::string(),
					'sqlMode' => Expect::string('TRADITIONAL'),
					'unix_socket' => Expect::mixed(),
					'flags' => Expect::mixed(),

					// pgsql only
					'searchPath' => Expect::mixed(),
					'hostaddr' => Expect::mixed(),
					'connection_timeout' => Expect::mixed(),
					'options' => Expect::mixed(),
					'sslmode' => Expect::mixed(),
					'service' => Expect::mixed(),
				])
					->castTo('array')
					->otherItems(Expect::mixed()),
			),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$sqlProcessorFactory = $config->sqlProcessorFactory;

		foreach ($config->connections as $connectionName => $connectionConfig) {
			$autowired = $connectionConfig['autowired'];
			// Remove from Connection config compile-time only values
			unset($connectionConfig['autowired']);

			// Connection expects empty values to be not set
			foreach ($connectionConfig as $key => $value) {
				if ($value === null) {
					unset($connectionConfig[$key]);
				}
			}

			if ($sqlProcessorFactory !== null) {
				$connectionConfig['sqlProcessorFactory'] = $sqlProcessorFactory;
			}

			$definition = $builder->addDefinition($this->prefix('connection.' . $connectionName))
				->setFactory(Connection::class, [
					'config' => $connectionConfig,
				])
				->setAutowired($autowired);
			//TODO - logger interface
			//              ->addSetup(new PhpLiteral(<<<'PHP'
			//?->onQuery[] = function (\Nextras\Dbal\Connection $connection, string $query, float $time): void {
			//  ?->info("Query:" . $query, ["time" => $time, "connection" => ?]);
			//};
			//PHP
			//              ),
			//                  [
			//                      '@self',
			//                      '@' . LoggerInterface::class,
			//                      $connectionName,
			//                  ],
			//              );

			if ($config->debug) {
				$definition->addSetup(
					'@' . BlueScreen::class . '::addPanel',
					[BluescreenQueryPanel::class . '::renderBluescreenPanel'],
				);
				$definition->addSetup(ConnectionPanel::class . '::install', ['@self', $config->panelQueryExplain]);
			}
		}
	}

}
