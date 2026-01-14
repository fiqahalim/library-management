<?php

class ImageService
{
    private static $uploadDir = 'public/uploads/';

    public static function upload($file, $subfolder = 'books')
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowed)) {
            return null;
        }

        $targetDir = self::$uploadDir . $subfolder . '/';
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $file['name']);
        $targetPath = $targetDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return str_replace('\\', '/', $targetPath); 
        }

        return null;
    }
}