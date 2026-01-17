<?php

namespace App\Model\Factory;

use Contributte\Datagrid\Datagrid;

class DatagridFactory
{
	public function create(): Datagrid
	{
		$grid = new Datagrid();
		$grid->setItemsPerPageList([20, 50, 100], false);
		$grid->setAutoSubmit();

		return $grid;
	}
}
