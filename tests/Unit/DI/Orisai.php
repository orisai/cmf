<?php declare(strict_types = 1);

use Orisai\Installer\Schema\ModuleSchema;

$schema = require __DIR__ . '/../../../orisai.php';
assert($schema instanceof ModuleSchema);

$schema->addConfigFile(__DIR__ . '/wiring.neon');

return $schema;
