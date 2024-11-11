<?php
// controllers/FileController.php
namespace App\Http\Controllers;

use App\Models\File;

class FileController extends Controller {
    public function uploadFile(array $file) 
    {
        if (empty($file['name']) || empty($file['type']) || empty($file['tmp_name'])) {
            response()->json(['error' => 'Invalid file upload'], 400);
            return;
        }

        $filename = $file['name'];
        $mimeType = $file['type'];
        $fileData = file_get_contents($file['tmp_name']);

        $fileId = File::uploadFile($filename, $mimeType, $fileData);
        response()->json(['message' => 'File uploaded', 'fileId' => $fileId], 201);
    }

    public function downloadFile(int $id) 
    {
        $file = File::find($id);
        if ($file) {
            header("Content-Type: " . $file['mime_type']);
            header("Content-Disposition: attachment; filename=" . $file['filename']);
            echo $file['data'];
        } else {
            response()->json(['error' => 'File not found'], 404);
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
            response()->json(['error' => 'File not found'], 404);
        }
    }
}
