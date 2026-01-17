<?php

namespace App\Core\Logger;

use App\Model\Core\Entity\Log;
use App\Model\Core\Repository\LogRepository;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class DatabaseLogger extends AbstractLogger
{

    public function __construct(
        private LogRepository $logRepository
    ) {
    }


    public function log($level, $message, array $context = []): void
    {
        $log = new Log((string) $level, (string) $message);
        $log->context = @var_export($context, true);
        $log->source = $this->findCaller();

        $this->logRepository->save($log);
    }


    private function findCaller()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        $loggerFile = __FILE__;

        foreach ($trace as $frame) {
            if (!isset($frame['file']) || $frame['file'] === $loggerFile) continue;

            $class = $frame['class'] ?? null;

            return !empty($frame['file']) ? basename($frame['file']) : $class;
        }

        return 'unknown';
    }


    public function emergency($message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }


    public function alert($message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }


    public function critical($message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }


    public function error($message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }


    public function warning($message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }


    public function notice($message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }


    public function debug($message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

}