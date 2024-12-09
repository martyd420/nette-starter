<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\Entity\User;
use Contributte\Datagrid\Column\Action\Confirmation\StringConfirmation;
use Contributte\Datagrid\Row;
use Nette\Forms\Container;
use Nettrine\ORM\Decorator\SimpleEntityManagerDecorator;
use Contributte\Datagrid\Datagrid;

final class UsersPresenter extends BaseAdminPresenter
{

	/** @inject */
	public SimpleEntityManagerDecorator $em;

	public function renderDefault()
	{

	}

	public function handleDelete($id)
	{
		var_dump($id);
		die();
	}

	public function createComponentUsersGrid(): Datagrid
	{
		$grid = new DataGrid();

		$grid->setDataSource($this->em->getRepository(User::class)->createQueryBuilder('u'));

		$grid->setItemsPerPageList([20, 50, 100], true);

		$grid->addColumnText('id', 'Id')
			->setSortable();

		$grid->addColumnText('email', 'E-mail')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('nick', 'Nick')
			->setFilterText();

		$grid->addColumnText('active', 'Active');


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

		$inlineEdit->onSubmit[] = function ($id, $values): void {
			// update user here
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


		return $grid;
	}

}