<?php

declare(strict_types=1);

namespace Homeapp\Exchange\Logger;

use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Psr7\BufferStream;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class NoBinaryFormatter extends MessageFormatter
{
    public function format(
        RequestInterface $request,
        ResponseInterface $response = null,
        \Exception $error = null
    ) {
        if ($response !== null) {
            $body = (string) $response->getBody();
            if (false === mb_detect_encoding($body, 'UTF-8', true)) {
                $stream = new BufferStream();
                $stream->write('-------- Binary data -------');
                $response = $response->withBody($stream);
            }
        }

        return parent::format($request, $response, $error);
    }
}
