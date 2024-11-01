<?php
// controllers/FileController.php
namespace Controllers;

use Models\File;

class FileController extends BaseController {
    public function uploadFile(array $file) 
    {
        if (empty($file['name']) || empty($file['type']) || empty($file['tmp_name'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid file upload']);
            return;
        }

        $filename = $file['name'];
        $mimeType = $file['type'];
        $fileData = file_get_contents($file['tmp_name']);

        $fileId = File::uploadFile($filename, $mimeType, $fileData);
        $this->jsonResponse(['message' => 'File uploaded', 'fileId' => $fileId], 201);
    }

    public function downloadFile(int $id) 
    {
        $file = File::find($id);
        if ($file) {
            header("Content-Type: " . $file['mime_type']);
            header("Content-Disposition: attachment; filename=" . $file['filename']);
            echo $file['data'];
        } else {
            $this->jsonResponse(['error' => 'File not found'], 404);
        }
    }

    public function getFile(int $id) 
    {
        $file = File::getFile($id);
        if ($file) {
            header("Content-Type: " . $file['mime_type']);
            header("Content-Disposition: attachment; filename=" . $file['filename']);
            echo $file['data'];
        } else {
            $this->jsonResponse(['error' => 'File not found'], 404);
        }
    }
}
