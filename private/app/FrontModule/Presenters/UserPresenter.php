<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

final class UserPresenter extends BaseFrontPresenter
{

	public function renderDefault()
	{

	}

	public function actionLogout(): void
	{
		$this->getUser()->logout(true);
		$this->redirect('Login:');
	}

}