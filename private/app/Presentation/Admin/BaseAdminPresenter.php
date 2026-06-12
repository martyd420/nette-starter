<?php

declare(strict_types=1);

namespace App\Presentation\Admin;

use App\Model\User\Enum\UserRole;
use Nette;

class BaseAdminPresenter extends Nette\Application\UI\Presenter
{
	public function startup(): void
	{
		if (!$this->getUser()->isLoggedIn() || !$this->getUser()->isInRole(UserRole::Admin->value)) {
			$this->redirect(':Front:Login:');
		}
		parent::startup();
	}
}
