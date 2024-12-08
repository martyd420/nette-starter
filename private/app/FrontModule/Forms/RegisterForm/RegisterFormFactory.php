<?php

namespace App\FrontModule\Forms\RegisterForm;

use Nette\Application\UI\Form;
use Nette;
use Contributte;

class RegisterFormFactory
{

	public function __construct(
		public Nette\Localization\Translator $translator,
		public Contributte\Translation\LocalesResolvers\Header $translatorHeaderResolver
	)
	{

	}

	public function create(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);
		$form->addProtection();

		$form->addEmail('email', 'Email')
			->setRequired('Musíte zadat platný email.');

		$form->addText('nick', 'Nick')
			->setHtmlAttribute('class', 'optional');

		$form->addText('password', 'Heslo')
			->addRule($form::MinLength, $this->translator->translate('password_min_length', ['min_length' => '8']), 8)
			->setRequired('must_enter_password');

		$form->addCheckbox('accept', 'accept')->setRequired('Přečti si podmínky');

		$form->addSubmit('send', 'register');

		return $form;
	}

}