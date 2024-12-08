<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Forms\LoginForm\LoginFormFactory;
use Nette\Application\UI\Form;
use App\FrontModule\Forms\LoginForm\LoginFormData;
use App\Model\UserManager;

final class LoginPresenter extends BaseFrontPresenter
{

	public function __construct(
		private readonly LoginFormFactory $loginFormFactory,
	)
	{
	}

	public function createComponentLoginForm(): Form
	{
		$form = $this->loginFormFactory->create();

		$form->onSuccess[] = function (Form $form, LoginFormData $values): void {

			try {
				$this->getUser()->login($values->email, $values->password);
			} catch (\Exception $e) {
				$this->flashMessage('Nepodařilo se přihlásit, zkontrolujte email a heslo', 'error');
				$this->redirect('Login:');
			}

			$this->flashMessage('Přihlášení proběhlo úspěšně');
			$this->redirect('Home:');

		}; // end onsuccess

		return $form;
	}


	public function renderDefault(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->flashMessage('Už jste přihlášen');
			$this->redirect('Home:default');
		}
	}


}
