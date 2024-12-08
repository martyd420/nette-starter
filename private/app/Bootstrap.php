<?php

declare(strict_types=1);

namespace App;

use Nette;
use Nette\Bootstrap\Configurator;
use Nette\Utils\Finder;


class Bootstrap
{
	private Configurator $configurator;
	private string $rootDir;


	public function __construct()
	{
		$this->rootDir = dirname(__DIR__);
		$this->configurator = new Configurator;
		$this->configurator->setTempDirectory($this->rootDir . '/temp');
	}


	public function bootWebApplication(): Nette\DI\Container
	{
		$this->initializeEnvironment();
		$this->setupContainer();
		return $this->configurator->createContainer();
	}

	public function bootForCli(): Nette\DI\Container
	{
		$this->initializeEnvironment();
		$this->setupContainer();
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		return $this->configurator->createContainer();
	}

	public function initializeEnvironment(): void
	{
		$this->configurator->setDebugMode(true);
		$this->configurator->enableTracy($this->rootDir . '/log');

		$this->configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();
	}


	private function setupContainer(): void
	{
		$configDir = $this->rootDir . '/config';

		foreach(Finder::findFiles('*.neon')->in($configDir) as $configFile) {
			$this->configurator->addConfig($configFile->getPathname());
		}
	}
}