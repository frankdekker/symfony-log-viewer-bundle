<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\Host;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class ResponseFactory
{
    /**
     * @throws Throwable
     */
    public function toStreamedResponse(HttpClientInterface $httpClient, ResponseInterface $httpResponse): StreamedResponse
    {
        return new StreamedResponse(
            function () use ($httpClient, $httpResponse) {
                $outputStream = fopen('php://output', 'wb');
                assert(is_resource($outputStream));

                foreach ($httpClient->stream($httpResponse) as $chunk) {
                    fwrite($outputStream, $chunk->getContent());
                }
                fclose($outputStream);
            },
            $httpResponse->getStatusCode(),
            $httpResponse->getHeaders(false)
        );
    }
}
