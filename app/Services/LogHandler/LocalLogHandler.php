<?php

namespace App\Services\LogHandler;

use App\Contracts\LogHandlerInterface;
use App\Enums\BooleanStatusEnum;
use Exception;
use Illuminate\Support\Facades\File;

class LocalLogHandler implements LogHandlerInterface
{

    /**
     * @param string $status
     * @param string $type
     * @param string $label
     * @param Exception|string $exceptionOrMessage
     * @return void
     */
    public function handle(string $status, string $type, string $label, Exception|string $exceptionOrMessage): void
    {
        $logMessage = $this->formatLogMessage($label, $exceptionOrMessage);
        $logFilePath = $this->getLogFilePath($status);
        $directory = dirname($logFilePath);
        $this->createDirectory($directory);
        $this->appendToLogFile($logFilePath, $logMessage);
    }

    /**
     * Format the log message.
     *
     * @param string $label
     * @param Exception|string $exceptionOrMessage
     * @return string
     */
    protected function formatLogMessage(string $label, Exception|string $exceptionOrMessage): string
    {
        return $this->prepareMessageFromException($label, ($exceptionOrMessage instanceof Exception) ? $exceptionOrMessage->getMessage() : $exceptionOrMessage);
    }

    /**
     * @param $label
     * @param $message
     * @return string
     */
    protected function prepareMessageFromException($label, $message): string
    {
        return date(DATE_FORMAT) . " [$label]: " . $message . "\n";
    }

    /**
     * Get the log file path.
     *
     * @param string|null $status
     * @return string
     */
    protected function getLogFilePath(string $status = null): string
    {
        return storage_path('logs/' . self::getChannelFileName($status) . '_' . date('Y_m_d') . '.log');
    }

    /**
     * Create the directory if it doesn't exist.
     *
     * @param string $directory
     * @return void
     */
    protected function createDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }
    }

    /**
     * Append the log message to the log file.
     *
     * @param string $logFilePath
     * @param string $logMessage
     * @return void
     */
    protected function appendToLogFile(string $logFilePath, string $logMessage): void
    {
        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
    }

    /**
     * @param string|null $status
     * @return string
     */
    protected static function getChannelFileName(string $status = null): string
    {
        return match ($status) {
            BooleanStatusEnum::SUCCESS->name => config("settings.log_file_name.success_log"),
            default => config("settings.log_file_name.error_log"),
        };
    }
}
