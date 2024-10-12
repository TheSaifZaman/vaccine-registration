<?php

namespace App\Contracts;

use Exception;

interface LogHandlerInterface
{
    /**
     * @param string $status
     * @param string $type
     * @param string $label
     * @param Exception|string $exceptionOrMessage
     * @return void
     */
    public function handle(string $status, string $type, string $label, Exception|string $exceptionOrMessage): void;
}
