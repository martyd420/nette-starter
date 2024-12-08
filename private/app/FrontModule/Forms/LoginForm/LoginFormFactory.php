<?php

namespace App\FrontModule\Forms\LoginForm;

use Nette\Application\UI\Form;
use Nette;
use Contributte;

class LoginFormFactory
{

	public function __construct(
		public Nette\Localization\Translator $translator,
	)
	{

	}

	public function create(): Form
	{
		$form = new Form();
		$form->setTranslator($this->translator);
		$form->addProtection();

		$form->addText('email', 'Email')
			->setRequired('Musíte zadat platný email.');

		$form->addPassword('password', 'Heslo heslo')
			->addRule($form::MinLength, 'Heslo ná určitě nejméně %d znaků', 8)
			->setRequired('Musíte zadat heslo');

		$form->addSubmit('send', 'Přihlásit se');

		return $form;
	}

}