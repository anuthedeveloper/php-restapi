<?php

namespace App\Helpers;

class Response
{
    /**
     * Send a JSON response.
     *
     * @param array $data The data to be encoded as JSON.
     * @param int $status The HTTP status code (default is 200).
     * @param array $headers Additional headers to include in the response.
     */
    public static function json(array $data, int $status = 200, bool $return = false): ?string
    {
        // Set default headers for JSON response
        header('Content-Type: application/json');

        // Set HTTP status code
        http_response_code($status);

        // Output the JSON-encoded data
        $jsonResponse = json_encode($data);

        if ($return) {
            // Return the JSON as a string if $return is true
            return $jsonResponse;
        } else {
            // Output the JSON directly for immediate response
            echo $jsonResponse;
            exit();
        }
        // Stop further execution after sending the response
        exit();
    }

    /**
     * Send a file as a response.
     *
     * @param string $filePath The path to the file to be sent.
     * @param array $headers Optional headers to include in the response.
     */
    public static function file(string $filePath, array $headers = []): void
    {
        // Ensure the file exists
        if (!file_exists($filePath)) {
            self::json(['error' => 'File not found.'], 404);
        }

        // Set default headers for file response
        $defaultHeaders = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
            'Content-Length' => filesize($filePath),
        ];

        // Merge custom headers with defaults
        $headers = array_merge($defaultHeaders, $headers);

        // Send headers
        foreach ($headers as $key => $value) {
            header("$key: $value");
        }

        // Send the file content
        readfile($filePath);
        exit;
    }
}
