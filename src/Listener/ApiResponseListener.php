<?php

declare(strict_types=1);

namespace Homeapp\Exchange\Listener;

use Homeapp\UtilsBundle\DTO\ApiResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;

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

    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        if (!$result instanceof ApiResponse) {
            return;
        }
        $response = $this->getResponse($result);
        $event->setResponse($response);
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getException();
        $result = ApiResponse::createError($e->getMessage(), 500, ['API']);
        $event->setResponse($this->getResponse($result));
    }

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
