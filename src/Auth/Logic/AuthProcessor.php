<?php declare(strict_types = 1);

namespace OriCMF\Auth\Logic;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Orisai\Auth\Authentication\BaseFirewall;
use Orisai\Auth\Authentication\Data\Logins;
use Orisai\Auth\Authentication\Firewall;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authentication\LoginStorage;
use ReflectionClass;
use function assert;

final class AuthProcessor implements ProcessorInterface
{

	/** @var array<string, array{int|string|null, array<int|string, mixed>|string}> */
	private array $dataCache = [];

	/** @var array<string, true> */
	private array $upToDateCache = [];

	/**
	 * @param array<int, Firewall<Identity>> $firewalls
	 */
	public function __construct(private readonly array $firewalls)
	{
	}

	/**
	 * @return array<string, mixed>
	 */
	private function getExtras(): array
	{
		$extras = [];
		foreach ($this->firewalls as $firewall) {
			$namespace = $firewall->getNamespace();
			[$identity, $upToDate] = $this->getIdentity($firewall);
			$id = $identity?->getId();

			$cached = $this->fetchFromCache($namespace, $id);
			if ($cached !== null) {
				$extra = $cached;
			} else {
				$extra = $this->createExtra($firewall);

				if ($upToDate) {
					$this->storeInCache($namespace, $id, $extra);
				}
			}

			$extras[$namespace] = $extra;
		}

		return $extras;
	}

	/**
	 * @return string|array<int|string, mixed>|null
	 */
	private function fetchFromCache(string $namespace, int|string|null $id): string|array|null
	{
		if (!isset($this->dataCache[$namespace])) {
			return null;
		}

		if ($this->dataCache[$namespace][0] !== $id) {
			unset($this->dataCache[$namespace]);

			return null;
		}

		return $this->dataCache[$namespace][1];
	}

	/**
	 * @param string|array<int|string, mixed> $extra
	 */
	private function storeInCache(string $namespace, int|string|null $id, string|array $extra): void
	{
		$this->dataCache[$namespace] = [$id, $extra];
	}

	/**
	 * @param Firewall<Identity> $firewall
	 * @return array{Identity|null, bool}
	 */
	private function getIdentity(Firewall $firewall): array
	{
		$isset = isset($this->upToDateCache[$firewall->getNamespace()]);

		if ($firewall instanceof BaseFirewall && !$isset) {
			$reflection = new ReflectionClass(BaseFirewall::class);

			$logins = $reflection->getProperty('logins')->getValue($firewall);
			assert($logins instanceof Logins || $logins === null);

			if ($logins === null) {
				$storage = $reflection->getProperty('storage')->getValue($firewall);
				assert($storage instanceof LoginStorage);
				$logins = $storage->getLogins($firewall->getNamespace());

				return [$logins->getCurrentLogin()?->getIdentity(), false];
			}
		}

		if (!$isset) {
			$this->upToDateCache[$firewall->getNamespace()] = true;
		}

		$identity = $firewall->isLoggedIn()
			? $firewall->getIdentity()
			: null;

		return [$identity, true];
	}

	/**
	 * @param Firewall<Identity> $firewall
	 * @return string|array<string, mixed>
	 */
	private function createExtra(Firewall $firewall): string|array
	{
		[$identity, $upToDate] = $this->getIdentity($firewall);

		return $identity !== null ? [
			'id' => $identity->getId(),
			'upToDate' => $upToDate,
		] : '[Not logged in]';
	}

	public function __invoke(LogRecord $record): LogRecord
	{
		static $loops = false;

		if ($loops) {
			return $record;
		}

		$loops = true;
		$record->extra['user'] = $this->getExtras();
		$loops = false;

		return $record;
	}

}
