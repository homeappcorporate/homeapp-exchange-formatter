<?php

declare(strict_types=1);

namespace Homeapp\ExchangeBundle\HTTP;

use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;

class DtoArgumentValueResolver implements ArgumentValueResolverInterface
{
    private $serializer;

    private ValidatorInterface $validator;


    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $typeInUppercase = strtoupper((string)$argument->getType());
        if (in_array($typeInUppercase, $this->exclusions)) {
            return false;
        }
        if(false === strpos($typeInUppercase, 'DTO')) {
            return false;
        }
        if ($request->isMethod(Request::METHOD_GET)) {
            return false;
        }
        $content = (string)$request->getContent();
        if (empty($content)) {
            return false;
        }
        return true;
    }

    /**
     * @throws RuntimeException
     * @throws BadRequestException
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {

        /** @psalm-suppress ArgumentTypeCoercion */
        $dto = $this->serializer->deserialize(
            (string)$request->getContent(),
            (string)$argument->getType(),
            'json'
        );

        $validationResult = $this->validator->validate($dto);
        $this->printErrors($validationResult);

        yield $dto;
    }

    /**
     * @throws BadRequestException
     */
    private function printErrors(ConstraintViolationListInterface $errors): void
    {
        if (count($errors) > 0) {
            $text = '';
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $text .= sprintf(
                    "Ошибка в поле %s: %s\n",
                    $error->getPropertyPath(),
                    (string)$error->getMessage()
                );
            }
            throw new BadRequestException($text);
        }
    }
}
