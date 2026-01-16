<?php

declare(strict_types=1);

namespace App\Model\Factory;

use Nette\Application\UI\Form;

class LangFormFactory
{
	public function __construct(
		private FormFactory $formFactory,
	) {
	}

	public function create(string $currentLang, callable $onSuccess): Form
	{
		$form = $this->formFactory->create();

		$form->addSelect('lang', null, [
			'en' => 'EN',
			'cs' => 'CZ',
		])->setDefaultValue($currentLang)
			->setHtmlAttribute('onchange', 'this.form.submit()');

		$form->addSubmit('send', 'messages.change_language');

		$form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {
			$onSuccess($data->lang);
		};

		return $form;
	}
}
