<?php

declare(strict_types=1);

namespace App;

use Nette;
use Nette\Bootstrap\Configurator;

class Bootstrap
{
	private readonly Configurator $configurator;
	private readonly string $rootDir;

	public function __construct()
	{
		$this->rootDir = dirname(__DIR__);
		$this->configurator = new Configurator();
		$this->configurator->setTempDirectory($this->rootDir . '/temp');
	}


	public function boot(): Nette\DI\Container
	{
		//$this->configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
		$this->configurator->enableTracy($this->rootDir . '/log');

		$this->configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configDir = $this->rootDir . '/config';
		$this->configurator->addConfig($configDir . '/common.neon');
		$this->configurator->addConfig($configDir . '/services.neon');

		if (file_exists($configDir . '/local.neon')) {
			$this->configurator->addConfig($configDir . '/local.neon');
		}

		return $this->configurator->createContainer();
	}
}
