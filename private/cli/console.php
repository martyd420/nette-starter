<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

exit((new App\Bootstrap())
	->boot()
	->getByType(Contributte\Console\Application::class)
	->run());
