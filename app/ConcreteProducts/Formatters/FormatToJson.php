<?php

namespace App\ConcreteProducts\Formatters;

use App\Contracts\FormatterInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FormatToJson implements FormatterInterface
{
    /**
     * @var mixed|null
     */
    private mixed $result = null;

    /**
     * @var int
     */
    private int $responseCode = Response::HTTP_OK;

    /**
     * @var array
     */
    private array $headers = [];

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
     * @param int|null $responseCode
     * @return $this
     */
    public function setResponseCode(int $responseCode = null): self
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers = []): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseCode(): string
    {
        return $this->responseCode;
    }
    /**
     * @return mixed
     */
    public function getResult(): mixed
    {
        return $this->result;
    }
    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return JsonResponse
     */
    public function format(): JsonResponse
    {
        return new JsonResponse($this->getResult(), $this->getResponseCode(), $this->getHeaders());
    }
}
