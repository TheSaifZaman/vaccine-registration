<?php

namespace App\Rules;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class ValidDate implements Rule
{
    /**
     * @var string|null
     */
    protected ?string $format;
    /**
     * @var string|null
     */
    protected ?string $errorMessage = null;

    /**
     * @param string $format
     */
    public function __construct(string $format = DATE_FORMAT)
    {
        $this->format = $format;
    }

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            $result = true;
            $date = Carbon::parse($value);
            if (!$date || $date->format($this->format) != $value) {
                $this->errorMessage = "The {$attribute} does not match {$this->format} format.";
                $result = false;
            }
            return $result;
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return false;
        }
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->errorMessage;
    }
}

