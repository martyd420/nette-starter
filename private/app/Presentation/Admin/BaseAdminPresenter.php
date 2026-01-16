<?php

namespace App\Presentation\Admin;

use Nette;

class BaseAdminPresenter extends Nette\Application\UI\Presenter
{

    public function startup()
    {
        if (!$this->getUser()->isLoggedIn() || !$this->getUser()->isInRole('admin')) {
            $this->redirect(':Front:Login:');
        }
        parent::startup();
    }
}