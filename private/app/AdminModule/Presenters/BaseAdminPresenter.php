<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\Configuration;
use Nette;


abstract class BaseAdminPresenter extends Nette\Application\UI\Presenter
{
	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this->getTemplate()->webName = Configuration::getWebName();

	}
}