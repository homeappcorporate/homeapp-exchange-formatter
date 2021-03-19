<?php

declare(strict_types=1);

namespace Homeapp\ExchangeBundle\Listener;

use Homeapp\ExchangeBundle\DTO\ApiResponse;
use JMS\Serializer\Exception\LogicException;
use JMS\Serializer\Exception\RuntimeException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiResponseListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @throws LogicException
     * @throws RuntimeException
     */
    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        if (!$result instanceof ApiResponse) {
            return;
        }
        $response = $this->getResponse($result);
        $event->setResponse($response);
    }

    /**
     * @throws LogicException
     * @throws RuntimeException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        $statusCode = 500;

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }

        $result = ApiResponse::createError(
            $e->getMessage(),
            $statusCode,
            ['API']
        );
        $event->setResponse($this->getResponse($result));
    }

    /**
     * @throws LogicException
     * @throws RuntimeException
     */
    public function getResponse(ApiResponse $result): JsonResponse
    {
        $groups = !empty($result->getContextGroups()) ? $result->getContextGroups() : ['API'];
        $cnt = SerializationContext::create();
        if (!empty($groups)) {
            $cnt->setGroups($groups);
        }
        $body = $this->serializer->serialize($result, 'json', $cnt);

        return new JsonResponse($body, $result->getStatus(), $result->getHeaders(), true);
    }
}
