<?php

namespace App\Model\User\Factory;

use App\Model\Factory\DatagridFactory;
use App\Model\User\Repository\UserRepository;
use Contributte\Datagrid\Datagrid;

class UsersGridFactory
{
	public function __construct(
		private UserRepository $userRepository,
		private DatagridFactory $datagridFactory,
	) {
	}

	public function create(): Datagrid
	{
		$grid = $this->datagridFactory->create();
		$grid->setDataSource($this->userRepository->createQueryBuilder());

		$grid->addColumnNumber('id', 'ID');
		$grid->addColumnText('email', 'Email')->setFilterText();
		$grid->addColumnText('name', 'Name');
		$grid->addColumnText('status', 'Status');

		$grid->addAction('edit', 'Edit', 'edit', ['id' => 'id'])
			->setIcon('pencil')
			->setClass('btn btn-xs btn-primary');

		//$grid->addAction('userDetail', 'Details', 'UserDetail', ['id' => 'id']);

		return $grid;
	}
}
