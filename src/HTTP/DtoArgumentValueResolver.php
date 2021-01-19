<?php

declare(strict_types=1);

namespace Homeapp\ExchangeBundle\HTTP;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class DtoArgumentValueResolver implements ArgumentValueResolverInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return stripos((string) $argument->getType(), 'App\\DTO') === 0;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {

        /** @psalm-suppress ArgumentTypeCoercion */
        yield $this->serializer->deserialize(
            (string) $request->getContent(),
            (string) $argument->getType(),
            'json'
        );
    }
}
