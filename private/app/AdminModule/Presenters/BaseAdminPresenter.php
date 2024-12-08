<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\Configuration;
use App\Model\Roles;
use Nette;


abstract class BaseAdminPresenter extends Nette\Application\UI\Presenter
{
	protected function beforeRender(): void
	{
		parent::beforeRender();

		if(!$this->getUser()->isInRole(Roles::ROLE_ADMIN)) {
			$this->flashMessage('Pro přístup k této stránce se musíte přihlásit');
			$this->redirect(':FrontModule:Login:default');
		}

		$this->getTemplate()->webName = Configuration::getWebName();

	}
}