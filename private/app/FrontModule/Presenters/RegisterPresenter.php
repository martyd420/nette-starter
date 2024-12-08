<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\Model\UserManager;
use Nette\Application\UI\Form;
use App\FrontModule\Forms\RegisterForm\RegisterFormFactory;
use App\FrontModule\Forms\RegisterForm\RegisterFormData;




final class RegisterPresenter extends BaseFrontPresenter
{

	public function __construct(
		private readonly RegisterFormFactory $registerFormFactory,
		private readonly UserManager         $userManager,
	)
	{
	}

	public function renderDefault(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->flashMessage('Jste přihlášen, nemůžete se registrovat');
			$this->redirect('Homepage:default');
		}
	}

	public function createComponentRegisterForm(): Form
	{
		$form = $this->registerFormFactory->create();
		//$form->setTranslator($this->translator);
		$form->onSuccess[] = function (Form $form, RegisterFormData $values): void {
			$this->userManager->createUser($values->email, $values->password, $values->nick);
			$this->flashMessage('Registrace proběhla úspěšně, můžete se přihlásit.');
			$this->redirect('Login:');
		}; // end onsuccess

		return $form;
	}
































}
