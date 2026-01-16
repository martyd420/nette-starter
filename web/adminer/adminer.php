<?php

require __DIR__ . '/../../private/vendor/autoload.php';

$configurator = new Nette\Bootstrap\Configurator;
$configurator->setTempDirectory(__DIR__ . '/../../private/temp');

if (!$configurator->isDebugMode()) {
    http_response_code(404);
    exit;
}

require __DIR__ . '/adminer.phps';