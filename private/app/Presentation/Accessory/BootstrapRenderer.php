<?php

declare(strict_types=1);

namespace App\Presentation\Accessory;

use Nette;
use Nette\Forms\Rendering\DefaultFormRenderer;

class BootstrapRenderer extends DefaultFormRenderer
{
	public function __construct()
	{
		$this->wrappers['controls']['container'] = null;
		$this->wrappers['pair']['container'] = 'div class="mb-3"';
		$this->wrappers['pair']['.error'] = null;
		$this->wrappers['control']['container'] = null;
		$this->wrappers['label']['container'] = null;
		$this->wrappers['control']['description'] = 'div class="form-text"';
		$this->wrappers['control']['errorcontainer'] = 'div class="invalid-feedback"';
		$this->wrappers['error']['container'] = 'div class="alert alert-danger"';
		$this->wrappers['error']['item'] = 'p';
	}

	public function render(Nette\Forms\Form $form, ?string $mode = null): string
	{
		foreach ($form->getControls() as $control) {
			if ($control instanceof Nette\Forms\Controls\Button) {
				$control->getControlPrototype()->addClass(empty($usedPrimary) ? 'btn btn-primary' : 'btn btn-secondary');
				$usedPrimary = true;

			} elseif ($control instanceof Nette\Forms\Controls\TextBase) {
				$control->getControlPrototype()->addClass('form-control');
				$control->getLabelPrototype()->addClass('form-label');

			} elseif ($control instanceof Nette\Forms\Controls\SelectBox || $control instanceof Nette\Forms\Controls\MultiSelectBox) {
				$control->getControlPrototype()->addClass('form-select');
				$control->getLabelPrototype()->addClass('form-label');

			} elseif ($control instanceof Nette\Forms\Controls\Checkbox || $control instanceof Nette\Forms\Controls\CheckboxList || $control instanceof Nette\Forms\Controls\RadioList) {
				if ($control instanceof Nette\Forms\Controls\Checkbox) {
					$control->getLabelPrototype()->addClass('form-check-label');
				} else {
					$control->getItemLabelPrototype()->addClass('form-check-label');
				}
				$control->getControlPrototype()->addClass('form-check-input');
				$control->getSeparatorPrototype()->setName('div')->addClass('form-check');
			}

			if ($control->hasErrors()) {
				$control->getControlPrototype()->addClass('is-invalid');
			}
		}

		return parent::render($form, $mode);
	}
}
