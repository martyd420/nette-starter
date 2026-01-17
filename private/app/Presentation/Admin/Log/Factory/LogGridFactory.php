<?php

declare(strict_types=1);

namespace App\Presentation\Admin\Log\Factory;

use App\Model\Core\Repository\LogRepository;
use App\Model\Factory\DatagridFactory;
use Contributte\Datagrid\Datagrid;

class LogGridFactory
{
    public function __construct(
        private LogRepository $logRepository,
        private DatagridFactory $datagridFactory,
    ) {}

    public function create(): Datagrid
    {
        $grid = $this->datagridFactory->create();
        $grid->setDataSource($this->logRepository->createQueryBuilder('l'));
        $grid->setDefaultSort(['createdAt' => 'DESC']);

        $grid->addColumnDateTime('createdAt', 'Time')
            ->setSortable()
            ->setFormat('j.n.Y H:i:s');
            
        $grid->addColumnText('level', 'Level')
            ->setSortable()
            ->setFilterText();
            
        $grid->addColumnText('message', 'Message')
            ->setFilterText();
            
        $grid->addColumnText('source', 'Source')
            ->setFilterText();

        $grid->addAction('detail', 'Metadata', 'detail')
            ->setIcon('eye')
            ->setClass('btn btn-xs btn-info');

        return $grid;
    }
}
