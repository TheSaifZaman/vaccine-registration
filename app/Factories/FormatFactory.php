<?php

namespace App\Factories;

use App\ConcreteProducts\Formatters\FormatToJson;
use App\ConcreteProducts\Formatters\FormatToModel;
use App\Contracts\FormatterInterface;
use InvalidArgumentException;

class FormatFactory
{
    /**
     * @var string
     */
    private string $format = "";

    /**
     * @return FormatFactory
     */
    public static function factory(): FormatFactory
    {
        return app(FormatFactory::class);
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        return match ($this->getFormat()) {
            'json' => new FormatToJson(),
            'model' => new FormatToModel(),
            default => throw new InvalidArgumentException("Format \"{$this->getFormat()}\" not supported"),
        };
    }
}
