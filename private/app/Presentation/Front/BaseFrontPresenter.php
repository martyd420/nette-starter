<?php

namespace App\Presentation\Front;

use App\Model\Factory\LangFormFactory;
use Nette;
use Nette\Application\Attributes\Persistent;
use Nette\DI\Attributes\Inject;

class BaseFrontPresenter extends Nette\Application\UI\Presenter
{

    #[Inject]
    public \Contributte\Translation\Translator $translator;

    #[Inject]
    public LangFormFactory $langFormFactory;

    #[Persistent]
    public string $lang = 'en';


    public function startup(): void
    {
        parent::startup();
        $this->translator->setLocale($this->lang);
    }


    protected function createComponentLangForm(): Nette\Application\UI\Form
    {
        return $this->langFormFactory->create($this->lang, function (string $lang): void {
            $this->redirect('this', ['lang' => $lang]);
        });
    }

}