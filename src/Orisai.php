<?php declare(strict_types = 1);

use Orisai\Installer\Schema\ModuleSchema;

$schema = new ModuleSchema();

$schema->addConfigFile(__DIR__ . '/wiring.neon');

return $schema;
