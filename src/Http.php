<?php

namespace Chaos\Http;

class Http
{
    protected $ch;
    protected $response;

    protected array $defaultHeaders = [];
    protected int $defaultTimeout = 15;

    public function __construct()
    {
        $this->ch = curl_init();

        if (!$this->ch) {
            throw new \RuntimeException('Unable to initialize cURL session.');
        }
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    // ! STATIC PROXY ! //
    public static function __callStatic($method, $args)
    {
        $instance = new static();
        return $instance->$method(...$args);
    }

    // ! PERSISTANCE ! //
    public function withHeaders(array $headers): static
    {
        $this->defaultHeaders = array_merge($this->defaultHeaders, $headers);
        return $this;
    }

    public function withTimeout(int $seconds): static
    {
        $this->defaultTimeout = $seconds;
        return $this;
    }

    // ! REQUEST ! //
    protected function request(string $method, string $url, array $options = []): object
    {
        // Merge defaults with per-call headers
        $mergedHeaders = array_merge($this->defaultHeaders, $options['headers'] ?? []);

        // Convert to "Key: Value" format
        $headerLines = [];
        foreach ($mergedHeaders as $key => $value) {
            $headerLines[] = "$key: $value";
        }

        // Common options
        curl_setopt_array($this->ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => $options['timeout'] ?? $this->defaultTimeout,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $headerLines,
        ]);

        // Handle POST/PUT data
        if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($options['body'])) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, is_array($options['body'])
                ? http_build_query($options['body'])
                : $options['body']);
        }

        // Execute and capture result
        $responseBody = curl_exec($this->ch);
        $info = curl_getinfo($this->ch);
        $error = curl_error($this->ch);

        // Reset the CURL
        curl_reset($this->ch);

        // Return response object
        return (object) [
            'success' => $error === '',
            'status' => $info['http_code'] ?? 0,
            'body' => $responseBody,
            'error' => $error ?: null,
            'info' => $info,
        ];
    }

    public function get(string $url, array $options = [])
    {
        $this->response = $this->request('GET', $url, $options);
        return $this;
    }

    public function post(string $url, array $options = [])
    {
        $this->response = $this->request('POST', $url, $options);
        return $this;
    }

    // ! WORK WITH RESPONSE OBJECTS ! //
    /**
     * Returns the body of the response as a json string
     */
    public function json()
    {
        return $this->response->body;
    }

    /**
     * Returns the body of the response as an object
     */
    public function object()
    {
        return json_decode($this->json(), false);
    }

    /**
     * Returns the body of the response as an associative array
     */
    public function array()
    {
        return json_decode($this->json(), true);
    }

    /**
     * Returns headers
     */
    public function headers()
    {
        return $this->response->info ?? [];
    }

    // ! QOL Methods ! //
    public function status(): int
    {
        return $this->response->status ?? 0;
    }

    public function ok(): bool
    {
        return $this->response->success && $this->status() >= 200 && $this->status() < 300;
    }

    public function error(): ?string
    {
        return $this->response->error ?? null;
    }

}