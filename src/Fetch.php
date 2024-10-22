<?php

namespace Core\Helper;

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
     *   headers: array<string, string>|null,
     * } $options
     *
     * @return Response
     */
    public static function new(string $url, array $options = []): Response
    {
        if (
            empty($options['method'])
            || !array_key_exists($options['method'], self::METHODS)
        ) {
            $options['method'] = self::DEFAULT_METHOD;
        }

        if (empty($options['body'])) {
            $options['body'] = null;
        }

        if (empty($options['headers'])) {
            $options['headers'] = [];
        }

        if (is_array($options['body'])) {
            $options['body'] = json_encode($options['body']);
            $options['headers']['Content-Type'] = 'application/json';
        }

        $options['headers'] = self::formatHeaders($options['headers']);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $options['method']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $options['body']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $body = curl_exec($curl);

        if (curl_errno($curl)) {
            // todo
        }

        $headers = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return new Response($body, $code);
    }

    private static function fetchException(): Exception
    {
        return new Exception();
    }

    /**
     * @param array<string, string> $headers
     * 
     * @return string[]
     */
    private static function formatHeaders(array $headers): array
    {
        $result = [];

        foreach ($headers as $key => $value) {
            $result = "$key: $value";
        }

        return $result;
    }
}

class Response
{
    public int $statusCode;

    private string $body;

    public function __construct(string $body, int $statusCode)
    {
        $this->body = $body;
        $this->statusCode = $statusCode;
    }

    public function json(): array
    {
        return (array) json_decode($this->body);
    }

    public function text(): string
    {
        return $this->body;
    }
}

Fetch::new('www.google.com')->statusCode;