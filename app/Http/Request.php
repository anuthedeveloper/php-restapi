<?php
namespace App\Http;

class Request
{
    protected $data = [];

    public function __construct()
    {
        // Initialize request data from various sources (GET, POST, JSON payload, etc.)
        $this->data = array_merge($_GET, $_POST, json_decode(file_get_contents('php://input'), true) ?? []);
    }

    /**
     * Retrieve a value from the request data.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function input(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->data;
        }

        return $this->data[$key] ?? $default;
    }

    /**
     * Check if a specific input exists in the request.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Retrieve all input data as an associative array.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get the request method (GET, POST, etc.)
     *
     * @return string
     */
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get the request URI.
     *
     * @return string
     */
    public static function uri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Determine if the request is an AJAX request.
     *
     * @return bool
     */
    public static function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Retrieve an instance of the Request.
     *
     * @return static
     */
    public static function capture(): self
    {
        return new static();
    }
}
