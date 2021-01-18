<?php

declare(strict_types=1);

namespace Homeapp\Exchange\Serializer;

use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;

class IdenticalStrategy implements PropertyNamingStrategyInterface
{
    public function translateName(PropertyMetadata $property): string
    {
        return $property->serializedName ?: $property->name;
    }
}
