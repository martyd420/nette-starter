<?php

declare(strict_types=1);

namespace App\Presentation\Front\Register;

use App\Presentation\Front\BaseFrontPresenter;
use Nette;
use Nette\Application\UI\Form;

class RegisterPresenter extends BaseFrontPresenter
{
	public function __construct(
		private RegisterFormFactory $registerFormFactory,
	) {
		parent::__construct();
	}

	protected function createComponentRegisterForm(): Form
	{
		$form = $this->registerFormFactory->create();
		$form->onSuccess[] = function (): void {
			$this->flashMessage($this->translator->translate('strings.register_success'), 'success');
			$this->redirect('Login:default');
		};

		return $form;
	}
}
