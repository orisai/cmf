<?php declare(strict_types = 1);

namespace OriCMF\UI\Config\DI;

use Brick\DateTime\Duration;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\PhpLiteral;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use OriCMF\UI\Config\AdminConfig;
use OriCMF\UI\Config\LoginConfig;
use OriCMF\UI\Config\PublicConfig;
use OriCMF\UI\Config\UIConfig;
use stdClass;
use function serialize;

/**
 * @property-read stdClass $config
 */
final class UIExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'admin' => Expect::structure([
				'login' => Expect::structure([
					'expiration' => Expect::anyOf(
						Expect::string(),
						Expect::null(),
					)->default(null),
				]),
			]),
			'public' => Expect::structure([
				'login' => Expect::structure([
					'expiration' => Expect::anyOf(
						Expect::string(),
						Expect::null(),
					)->default(null),
				]),
			]),
		]);
	}

	public function loadConfiguration(): void
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$config = $this->config;

		$uiConfig = new UIConfig(
			new AdminConfig(
				new LoginConfig(
					$this->textTimeToInt($config->admin->login->expiration),
				),
			),
			new PublicConfig(
				new LoginConfig(
					$this->textTimeToInt($config->public->login->expiration),
				),
			),
		);

		$builder->addDefinition($this->prefix('config'))
			->setFactory('\unserialize(\'?\')', [
				new PhpLiteral(serialize($uiConfig)),
			])
			->setType(UIConfig::class);
	}

	private function textTimeToInt(string|null $string): int|null
	{
		if ($string === null) {
			return null;
		}

		return Duration::parse($string)->getSeconds();
	}

}
