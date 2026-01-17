<?php

declare(strict_types=1);

namespace App\Presentation\Admin\Log;

use App\Model\Core\Entity\Log;
use App\Model\Core\Repository\LogRepository;
use App\Presentation\Admin\BaseAdminPresenter;
use App\Presentation\Admin\Log\Factory\LogGridFactory;
use Contributte\Datagrid\Datagrid;
use Nette\DI\Attributes\Inject;

final class LogPresenter extends BaseAdminPresenter
{
	#[Inject]
	public LogGridFactory $logGridFactory;

	#[Inject]
	public LogRepository $logRepository;

	public function renderDetail(int $id): void
	{
		$log = $this->logRepository->getEntityManager()->find(Log::class, $id);
		if (!$log) {
			$this->error('Log not found');
		}
		$this->template->log = $log;
	}

	public function handleDeleteOld(): void
	{
		$count = $this->logRepository->deleteOlderThan(30);
		$this->flashMessage("$count logs deleted.", 'success');
		$this->redirect('this');
	}

	protected function createComponentLogGrid(): Datagrid
	{
		return $this->logGridFactory->create();
	}
}
