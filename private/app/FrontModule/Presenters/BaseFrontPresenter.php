<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Model\Configuration;
use Nette;


abstract class BaseFrontPresenter extends Nette\Application\UI\Presenter
{
	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->getTemplate()->webName = Configuration::getWebName();
	}
}