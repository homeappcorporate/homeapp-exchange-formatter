<?php

declare(strict_types=1);

namespace Homeapp\ExchangeBundle\DTO;

class SentFieldsCollection
{
    public array $fields = [];

    public function hasField(string $fieldName): bool
    {
        return in_array($fieldName, $this->fields, true);
    }

    public function unsetField(string $fieldName): void
    {
        $key = array_search($fieldName, $this->fields, true);
        if (false !== $key) {
            unset($this->fields[$key]);
        }
    }
}
