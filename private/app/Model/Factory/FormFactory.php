<?php

namespace App\Model\Factory;

use Nette;

class FormFactory
{
    public function __construct(
        public Nette\Localization\Translator $translator
    ){}

    public function create(): Nette\Application\UI\Form
    {
        $form = new Nette\Application\UI\Form;
        $form->setTranslator($this->translator);
        return $form;
    }
}