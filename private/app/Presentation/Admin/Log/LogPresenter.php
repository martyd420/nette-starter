<?php

declare(strict_types=1);

namespace App\Presentation\Admin\Log;

use App\Model\Core\Repository\LogRepository;
use App\Presentation\Admin\BaseAdminPresenter;
use App\Presentation\Admin\Log\Factory\LogGridFactory;
use Contributte\Datagrid\Datagrid;

final class LogPresenter extends BaseAdminPresenter
{
	public function __construct(
		private readonly LogGridFactory $logGridFactory,
		private readonly LogRepository $logRepository,
	) {
		parent::__construct();
	}

	public function renderDetail(int $id): void
	{
		$log = $this->logRepository->getById($id);
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
