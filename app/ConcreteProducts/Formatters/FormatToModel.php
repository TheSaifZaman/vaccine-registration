<?php

namespace App\ConcreteProducts\Formatters;

use App\Contracts\FormatterInterface;

class FormatToModel implements FormatterInterface
{
    /**
     * @var mixed|null
     */
    private mixed $result = null;

    /**
     * @param null $result
     * @return $this
     */
    public function setResult($result = null): self
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult(): mixed
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function format(): mixed
    {
        return $this->getResult();
    }
}
