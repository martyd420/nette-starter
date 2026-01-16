<?php

declare(strict_types=1);

namespace App\Presentation\Front\Login;

use App\Presentation\Front\BaseFrontPresenter;
use Nette;
use Nette\Application\UI\Form;

class LoginPresenter extends BaseFrontPresenter
{
	public function __construct(
		private LoginFormFactory $loginFormFactory,
	) {
		parent::__construct();
	}

	protected function createComponentLoginForm(): Form
	{
		$form = $this->loginFormFactory->create();
		$form->onSuccess[] = function (): void {
			$this->flashMessage($this->translator->translate('strings.login_success'), 'success');
			$this->redirect('Home:default');
		};

		return $form;
	}

	public function actionOut(): void
	{
		$this->getUser()->logout();
		$this->flashMessage($this->translator->translate('strings.logout_success'));
		$this->redirect('Home:default');
	}
}
