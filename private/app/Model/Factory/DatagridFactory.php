<?php

namespace App\Model\Factory;

use Contributte\Datagrid\Datagrid;

class DatagridFactory
{
	public static function create(): Datagrid
	{
		$grid = new DataGrid();
		$grid->setItemsPerPageList([20, 50, 100], true);

		return $grid;
	}
}