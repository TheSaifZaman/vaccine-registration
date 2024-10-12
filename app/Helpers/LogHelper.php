<?php

namespace App\Helpers;

use App\Enums\BooleanStatusEnum;
use App\Enums\LogTypeEnum;
use App\Services\LogHandler\DatabaseLogHandler;
use App\Services\LogHandler\EmailLogHandler;
use App\Services\LogHandler\LocalLogHandler;
use Exception;

class LogHelper
{
    /**
     * @var LogTypeEnum
     */
    private LogTypeEnum $logType = LogTypeEnum::Error;
    /**
     * @var object|string
     */
    private object|string $exceptionOrMessage;
    /**
     * @var string
     */
    private string $label = "";
    /**
     * @var BooleanStatusEnum
     */
    private BooleanStatusEnum $logStatus = BooleanStatusEnum::ERROR;
    /**
     * @var array
     */
    protected array $logHandlers = [];

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->initializeLogHandlers();
    }

    /**
     * @return LogHelper
     */
    public static function factory(): LogHelper
    {
        return app(LogHelper::class);
    }

    /**
     * @param object|string $exceptionOrMessage
     * @return $this
     */
    public function setExceptionOrMessage(object|string $exceptionOrMessage): self
    {
        $this->exceptionOrMessage = $exceptionOrMessage;
        return $this;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param BooleanStatusEnum $logStatus
     * @return $this
     */
    public function setLogStatus(BooleanStatusEnum $logStatus): self
    {
        $this->logStatus = $logStatus;
        return $this;
    }

    /**
     * @param LogTypeEnum $logType
     * @return $this
     */
    public function setLogType(LogTypeEnum $logType): self
    {
        $this->logType = $logType;
        return $this;
    }

    /**
     * @return object
     */
    public function getLogType(): object
    {
        return $this->logType;
    }

    /**
     * @return object|string
     */
    public function getExceptionOrMessage(): object|string
    {
        return $this->exceptionOrMessage;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return BooleanStatusEnum
     */
    public function getLogStatus(): BooleanStatusEnum
    {
        return $this->logStatus;
    }

    /**
     * Initialize Log Handlers
     *
     * @return void
     * @throws Exception
     */
    protected function initializeLogHandlers(): void
    {
        // Get all log levels
        $logLevels = LogTypeEnum::toArray();

        foreach ($logLevels as $level) {
            // Assign log handlers for each log level
            $this->logHandlers[$level] = $this->getLogHandlersForLevel($level);
        }
    }

    /**
     * Get Log Handlers For Level
     *
     * @param $level
     * @return array
     */
    protected function getLogHandlersForLevel($level): array
    {
        // Determine the log handlers based on the log level
        return match ($level) {
            LogTypeEnum::Emergency->value,
            LogTypeEnum::Critical->value,
            LogTypeEnum::Error->value,
            LogTypeEnum::Success->value,
            LogTypeEnum::Warning->value,
            LogTypeEnum::Alert->value,
            LogTypeEnum::Notice->value,
            LogTypeEnum::Info->value,
            LogTypeEnum::Debug->value,
            LogTypeEnum::Verbose->value => [
                new LocalLogHandler(),
            ],
            default => [],
        };
    }

    /**
     * Handle the Log
     *
     * @return void
     */
    public function log(): void
    {
        // Get the log handlers for the specified log type
        $handlers = $this->logHandlers[$this->getLogType()->value];

        if (isset($handlers)) {
            // Iterate over the log handlers and handle the log
            foreach ($handlers as $handler) {
                $handler->handle(
                    $this->getLogStatus()->name,
                    $this->getLogType()->value,
                    $this->getLabel(),
                    $this->getExceptionOrMessage()
                );
            }
        }
    }
}
