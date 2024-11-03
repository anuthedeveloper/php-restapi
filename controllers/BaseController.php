<?php
// controllers/BaseController.php
namespace Controllers;

class BaseController {

    protected function jsonResponse(array $data, int $statusCode = 200) 
    {
        http_response_code($statusCode);
        echo json_encode($data);
    }

    protected function handleException(\Exception $e) 
    {
        $statusCode = $e->getCode() ?: 500;
        $this->jsonResponse(['error' => $e->getMessage(), $statusCode]);
    }

    protected function validateInput(array $data, array $fields): bool 
    {
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                $this->jsonResponse(['error' => "Missing field: $field"], 400);
                exit();
            }
        }
        return true;
    }

    protected function sanitize(mixed $input) 
    {
        return htmlspecialchars(strip_tags($input));
    }

    protected function validateEmail(string $email) 
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

