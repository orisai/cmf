<?php declare(strict_types = 1);

namespace OriCMF\UI\Routing;

use Closure;
use Nette\Application\Routers\Route;
use function is_array;
use function is_string;
use function str_starts_with;
use function substr;

final class SimplifiedRoute extends Route
{

	/**
	 * @param string|array<mixed>|Closure():void $metadata
	 */
	public function __construct(string $mask, string|array|Closure $metadata = [])
	{
		if (is_string($metadata)) {
			if (str_starts_with($metadata, ':')) {
				$metadata = substr($metadata, 1);
			}
		} elseif (is_array($metadata)) {
			$presenter = $metadata['presenter'] ?? null;
			if (is_string($presenter) && str_starts_with($presenter, ':')) {
				$metadata['presenter'] = substr($presenter, 1);
			}
		}

		parent::__construct($mask, $metadata);
	}

}
