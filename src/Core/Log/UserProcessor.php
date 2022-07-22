<?php declare(strict_types = 1);

namespace OriCMF\Core\Log;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use OriCMF\Core\SQL\SqlLogger;
use Orisai\Auth\Authentication\Firewall;
use Orisai\Auth\Authentication\Identity;
use Orisai\Auth\Authentication\IdentityRefresher;
use function debug_backtrace;
use function is_a;
use const DEBUG_BACKTRACE_IGNORE_ARGS;

final class UserProcessor implements ProcessorInterface
{

	/** @var array<string, array{int|string|null, array<int|string, mixed>|string}> */
	private array $cache = [];

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
			$id = $this->getUserId($firewall);

			$cached = $this->fetchFromCache($namespace, $id);
			if ($cached !== null) {
				$extra = $cached;
			} else {
				$extra = $this->createExtra($firewall);
				$this->storeInCache($namespace, $id, $extra);
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
		if (!isset($this->cache[$namespace])) {
			return null;
		}

		if ($this->cache[$namespace][0] !== $id) {
			unset($this->cache[$namespace]);

			return null;
		}

		return $this->cache[$namespace][1];
	}

	/**
	 * @param string|array<int|string, mixed> $extra
	 */
	private function storeInCache(string $namespace, int|string|null $id, string|array $extra): void
	{
		$this->cache[$namespace] = [$id, $extra];
	}

	/**
	 * @param Firewall<Identity> $firewall
	 */
	private function getUserId(Firewall $firewall): int|string|null
	{
		return $firewall->isLoggedIn()
			? $firewall->getIdentity()->getId()
			: null;
	}

	/**
	 * @param Firewall<Identity> $firewall
	 * @return string|array<int|string, mixed>
	 */
	private function createExtra(Firewall $firewall): string|array
	{
		return $firewall->isLoggedIn() ? [
			'id' => $firewall->getIdentity()->getId(),
		] : '[Not logged in]';
	}

	public function __invoke(LogRecord $record): LogRecord
	{
		// Prevent looping with query logger in IdentityRefresher calls
		if (($record->extra[SqlLogger::ContextIdentifier] ?? false) === true) {
			foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $item) {
				if (isset($item['class']) && is_a($item['class'], IdentityRefresher::class, true)) {
					return $record;
				}
			}
		}

		$record->extra['user'] = $this->getExtras();

		return $record;
	}

}
