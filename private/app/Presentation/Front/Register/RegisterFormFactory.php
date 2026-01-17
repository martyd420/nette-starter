<?php

declare(strict_types=1);

namespace App\Presentation\Front\Register;

use App\Model\Factory\FormFactory;
use App\Model\User\Exception\DuplicateEmailException;
use App\Model\User\Facade\RegistrationData;
use App\Model\User\Facade\UserRegistrationFacade;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use stdClass;

class RegisterFormFactory
{
	/** @var array<callable> */
	public array $onSuccess = [];

	public function __construct(
		private UserRegistrationFacade $registrationFacade,
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

		$form->addText('firstName', 'forms.name')
			->setRequired('forms.please_enter_name');

		$form->addText('lastName', 'forms.surname')
			->setRequired('forms.please_enter_surname');

		$form->addText('street', 'forms.street')
			->setRequired('forms.please_enter_street');

		$form->addText('city', 'forms.city')
			->setRequired('forms.please_enter_city');

		$form->addText('zip', 'forms.zip')
			->setRequired('forms.please_enter_zip');

		$form->addSubmit('send', 'forms.create_account');

		$form->onSuccess[] = function (Form $form, stdClass $values): void {
			try {
				$data = new RegistrationData();
				$data->email = $values->email;
				$data->password = $values->password;
				$data->firstName = $values->firstName;
				$data->lastName = $values->lastName;
				$data->street = $values->street;
				$data->city = $values->city;
				$data->zip = $values->zip;

				$this->registrationFacade->register($data);

			} catch (DuplicateEmailException $e) {
				/** @var BaseControl $emailControl */
				$emailControl = $form['email'];
				$emailControl->addError($e->getMessage());
			}
		};

		return $form;
	}
}
