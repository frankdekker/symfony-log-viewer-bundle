<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\Host;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class ResponseFactory
{
    private const ALLOWED_HEADERS = [
        'content-disposition',
        'content-length',
        'content-type'
    ];

    /**
     * @throws Throwable
     */
    public function toStreamedResponse(HttpClientInterface $httpClient, ResponseInterface $httpResponse): StreamedResponse
    {
        $headers         = array_filter(
            $httpResponse->getHeaders(false),
            static fn(string $header): bool => in_array(strtolower($header), self::ALLOWED_HEADERS, true),
            ARRAY_FILTER_USE_KEY
        );

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
            $headers
        );
    }
}
