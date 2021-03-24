<?php

declare(strict_types=1);

namespace Homeapp\ExchangeBundle\DTO;

use JMS\Serializer\Annotation as Serializer;

class ApiResponse
{
    /**
     * @Serializer\Groups({"API"})
     * @Serializer\Type("boolean")
     * @var boolean Удалось ли выполнить бизнес-логику, требуемую в запрос
     */
    private $success;
    /**
     * @Serializer\Groups({"API"})
     * @var mixed Данные, результат выполнения запроса
     */
    private $data;
    /**
     * @Serializer\Groups({"API"})
     * @var mixed|null
     */
    private $meta;
    /**
     * @var array|null
     * @Serializer\Groups({"API"})
     */
    private $pageParams;

    private $contextGroups;

    private $status;

    private $headers;

    /**
     * @param mixed $data
     */
    public function __construct(
        $data,
        bool $success = true,
        array $contextGroups = [],
        int $status = 200,
        array $headers = []
    ) {
        $this->success = $success;
        $this->data = $data;
        $this->contextGroups = $contextGroups;
        $this->status = $status;
        $this->headers = $headers;
    }

    public static function createError(string $message, int $code, array $groups = []): ApiResponse
    {
        $obj = new self(['error' => $message], false, $groups, $code);

        return $obj;
    }

    public static function createWait(string $message, int $waitSeconds, array $groups = []): ApiResponse
    {
        $obj = new self(['error' => $message], false, $groups, 503, ['retry-after' => $waitSeconds]);

        return $obj;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    /** @return mixed  */
    public function getData()
    {
        return $this->data;
    }

    /** @return mixed|null  */
    public function getMeta()
    {
        return $this->meta;
    }

    /** @param mixed|null $meta  */
    public function setMeta($meta): void
    {
        $this->meta = $meta;
    }

    public function getContextGroups(): array
    {
        return $this->contextGroups;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPageParams(): ?array
    {
        return $this->pageParams;
    }

    public function setPageParams(int $page, int $length): void
    {
        $this->pageParams = ['page' => $page, 'length' => $length];
    }
}
