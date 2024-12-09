<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\Entity\User;
use App\Model\Factory\DatagridFactory;
use App\Model\UserManager;
use Contributte\Datagrid\Column\Action\Confirmation\StringConfirmation;
use Nette\Forms\Container;
use Nette\Utils\ArrayHash;
use Nettrine\ORM\Decorator\SimpleEntityManagerDecorator;
use Contributte\Datagrid\Datagrid;

final class UsersPresenter extends BaseAdminPresenter
{

	/** @inject */
	public UserManager $userManager;

	private string $test = 'test-ajax';

	public function renderDefault():void
	{
		$this->template->test = $this->test;
		parent::beforeRender();
	}

	public function handleDelete($id)
	{
		var_dump($id);
		die();
	}

	public function createComponentUsersGrid(): Datagrid
	{
		$grid = DatagridFactory::create();

		$grid->setDataSource($this->userManager->getRepository()->createQueryBuilder('u'));

		$grid->addColumnText('id', 'Id')
			->setSortable();

		$grid->addColumnText('email', 'E-mail')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('nick', 'Nick')
			->setFilterText();

		$grid->addColumnText('active', 'Active')->setReplacement([
			'1' => 'Aktivní',
			'0' => 'Neaktivní'
		]);;


		// inline edit
		$inlineEdit = $grid->addInlineEdit();
		$inlineEdit->setText('Edit');

		$inlineEdit->onControlAdd[] = function ($container): void {
			$container->addText('nick', '')
				->setRequired('aaa');
			$container->addText('email', '');
			$container->addSelect('active', '', [
				1 => 'Active',
				0 => 'Inactive',
			]);
		};

		$inlineEdit->onSetDefaults[] = function (Container $container, User $u): void {
			$container->setDefaults([
				'id' => $u->getId(),
				'nick' => $u->getNick(),
				'email' => $u->getEmail(),
				'active' => $u->isActive(),
			]);
		};

		$inlineEdit->onSubmit[] = function ($id, ArrayHash $values): void {
			$this->userManager->updateUser((int)$id, $values);
			$this->flashMessage('Record was updated!', 'success');
			$this->redrawControl('flashes');
		};

		$inlineEdit->setShowNonEditingColumns();



		$grid->addAction('delete', 'X', 'delete!')
			//->setIcon('trash')
			->setTitle('Delete')
			->setClass('btn btn-xs btn-danger')
			->setConfirmation(
				new StringConfirmation('Do you really want to delete example %s?', 'email')
			);


		$inlineAdd = $grid->addInlineAdd()->setText('Add user');

		$inlineAdd->setPositionTop()->onControlAdd[] = function ($container): void {
			$container->addText('nick', '');
			$container->addText('email', '');
			$container->addSelect('active', '', [
				'1' => 'Active',
				'0' => 'Inactive',
			]);
		};

		$inlineAdd->onSubmit[] = function ($values): void {

			$this->flashMessage('Record was added!', 'success');
			$this->redrawControl('flashes');
		};

		return $grid;
	}

	public function handleTest()
	{
		$this->test = 'test-ok ' . date('H:i:s');
		$this->redrawControl();
	}
}