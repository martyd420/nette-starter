<?php

declare(strict_types=1);

namespace App\Presentation\Front\Login;

use App\Model\Factory\FormFactory;
use Nette;
use Nette\Application\UI\Form;
use stdClass;

class LoginFormFactory
{
	public array $onSuccess = [];

	public function __construct(
		private Nette\Security\User $user,
        private FormFactory $formFactory,
	) {
	}

	public function create(): Form
	{
		$form = $this->formFactory->create();
		$form->addEmail('email', 'forms.email')
			->setRequired('forms.please_enter_email');

		$form->addPassword('password', 'forms.password')
			->setRequired('forms.please_enter_password');

		$form->addSubmit('send', 'forms.login');

		$form->onSuccess[] = function (Form $form, stdClass $data): void {
			try {
				$this->user->login($data->email, $data->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError('strings.invalid_credentials');
			}
		};

		return $form;
	}
}
