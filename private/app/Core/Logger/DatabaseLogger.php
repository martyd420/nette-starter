<?php

declare(strict_types=1);

namespace App\Core\Logger;

use App\Model\Core\Entity\Log;
use App\Model\Core\Repository\LogRepository;
use Psr\Log\AbstractLogger;

class DatabaseLogger extends AbstractLogger
{
	public function __construct(
		private LogRepository $logRepository,
	) {
	}


	/** @param array<mixed> $context */
	public function log($level, string|\Stringable $message, array $context = []): void
	{
		$log = new Log((string) $level, (string) $message);
		$encoded = json_encode($context);
		$log->context = $encoded !== false ? $encoded : '';
		$log->source = $this->findCaller();

		$this->logRepository->save($log);
	}


	private function findCaller(): string
	{
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);

		foreach ($trace as $frame) {
			$file = $frame['file'] ?? null;
			if ($file === null || $file === __FILE__ || str_contains($file, 'psr/log')) {
				continue;
			}

			return basename($file);
		}

		return 'unknown';
	}
}
