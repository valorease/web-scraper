<?php

namespace Core\Helper;

ini_set('display_errors', 1);

use Exception;

class Fetch
{
    public const DEFAULT_METHOD = 'GET';

    public const METHODS = ['GET' => 'GET', 'POST' => 'POST'];

    /**
     * @param string $url
     * @param array{
     *   method: string|null,
     *   body: string|array|null,
     *   query: array|null,
     *   header: array<string, string>|null,
     * } $options
     *
     * @return Response
     */
    public static function to(
        string $url,
        $method = null,
        $body = null,
        $query = null,
        $header = null
    ): Response {
        if (
            empty($options['method'])
            || !array_key_exists($options['method'], self::METHODS)
        ) {
            $options['method'] = self::DEFAULT_METHOD;
        }

        if (empty($options['body'])) {
            $options['body'] = null;
        }

        if (empty($options['header'])) {
            $options['header'] = [];
        }

        if (is_array($options['body'])) {
            $options['body'] = json_encode($options['body']);
            $options['header']['Content-Type'] = 'application/json';
        }

        $options['header'] = self::formatHeader($options['header']);

        if (!empty($options['query'])) {
            $options['query'] = http_build_query($options['query']);
            $url .= "?{$options['query']}";
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $options['method'],
            CURLOPT_POSTFIELDS => $options['body'],
            CURLOPT_HTTPHEADER => $options['header'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return new Response($statusCode, $body, $header);
    }

    /**
     * @param array<string, string> $header
     * 
     * @return string[]
     */
    private static function formatHeader(array $header): array
    {
        $result = [];

        foreach ($header as $key => $value) {
            $result[] = "$key: $value";
        }

        return $result;
    }
}

class Response
{
    public int $statusCode;

    private string $body;

    private string $header;

    public function __construct(
        int $statusCode,
        string $body,
        string $header
    ) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->header = $header;
    }

    public function json(): array
    {
        return (array) json_decode($this->body);
    }

    public function text(): string
    {
        return $this->body;
    }

    public function header(): array
    {
        $header = [];

        $exploded = explode(PHP_EOL, $this->header);

        foreach ($exploded as $line) {
            $line = explode(': ', $line);

            if (count($line) < 2) {
                continue;
            }

            $header[trim($line[0])] = trim($line[1]);
        }

        return $header;
    }
}

$response = Fetch::to(
    'www.google.com',
    method: 'POST',
    query: [
        'page' => 80
    ],
    body: [
        'event' => [
            'token' => '...',
            'siteKey' => '...',
            'userAgent' => '...'
        ]
    ]
);

var_dump($response->header());



