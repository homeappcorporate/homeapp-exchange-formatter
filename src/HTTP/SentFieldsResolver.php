<?php

declare(strict_types=1);

namespace Homeapp\ExchangeBundle\HTTP;

use Homeapp\ExchangeBundle\DTO\SentFieldsCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class SentFieldsResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() === SentFieldsCollection::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $collection = new SentFieldsCollection();
        try {
            /** @var array<int, mixed> $data */
            $data = json_decode(
                (string) $request->getContent(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            $collection->fields = array_keys($data);
        } catch (\Exception $e) {
        }
        yield $collection;
    }
}
