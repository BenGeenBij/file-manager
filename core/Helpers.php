<?php
// core/Helpers.php

class Helpers {
    
    /**
     * Get nice filesize (e.g., 2.5 MB)
     */
    public static function formatSize($size) {
        $size = (float) $size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $power = ($size > 0) ? floor(log($size, 1024)) : 0;
        $power = min($power, count($units) - 1);
        return sprintf('%s %s', round($size / pow(1024, $power), 2), $units[$power]);
    }

    /**
     * Get exact filesize securely (handles >2GB on 32-bit systems if necessary)
     */
    public static function getFileSize($file) {
        $size = @filesize($file);
        if ($size !== false && $size >= 0) {
            return $size;
        }
        // Fallback for large files on older systems
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && class_exists('COM')) {
            try {
                $fsobj = new COM('Scripting.FileSystemObject');
                $f = $fsobj->GetFile(realpath($file));
                return $f->Size;
            } catch (Exception $e) {}
        }
        return 0;
    }

    /**
     * Prevent XSS attacks in filenames
     */
    public static function isValidFilename($text) {
        return (strpbrk($text, '/?%*:|"<>' . chr(0)) === false);
    }

    /**
     * Get MIME type of a file
     */
    public static function getMimeType($file_path) {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file_path);
            finfo_close($finfo);
            return $mime;
        } elseif (function_exists('mime_content_type')) {
            return mime_content_type($file_path);
        }
        return 'application/octet-stream';
    }

    /**
     * Get FontAwesome CSS classname based on file extension
     */
    public static function getFileIconClass($path) {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $icons = [
            'image'   => ['ico', 'gif', 'jpg', 'jpeg', 'png', 'bmp', 'svg', 'webp'],
            'code'    => ['php', 'js', 'ts', 'html', 'css', 'scss', 'json', 'xml', 'sql', 'py'],
            'archive' => ['zip', 'rar', 'tar', 'gz', '7z', 'bz2'],
            'audio'   => ['wav', 'mp3', 'ogg', 'm4a'],
            'video'   => ['mp4', 'avi', 'mov', 'mkv', 'webm'],
            'word'    => ['doc', 'docx', 'odt'],
            'excel'   => ['xls', 'xlsx', 'csv'],
            'pdf'     => ['pdf'],
            'text'    => ['txt', 'md', 'log', 'ini']
        ];

        if (in_array($ext, $icons['image'])) return 'fa fa-picture-o text-success';
        if (in_array($ext, $icons['code'])) return 'fa fa-file-code-o text-info';
        if (in_array($ext, $icons['archive'])) return 'fa fa-file-archive-o text-danger';
        if (in_array($ext, $icons['audio'])) return 'fa fa-music text-warning';
        if (in_array($ext, $icons['video'])) return 'fa fa-file-video-o text-warning';
        if (in_array($ext, $icons['word'])) return 'fa fa-file-word-o text-primary';
        if (in_array($ext, $icons['excel'])) return 'fa fa-file-excel-o text-success';
        if (in_array($ext, $icons['pdf'])) return 'fa fa-file-pdf-o text-danger';
        if (in_array($ext, $icons['text'])) return 'fa fa-file-text-o text-secondary';

        return 'fa fa-file-o text-secondary'; // Default icon
    }
}
?>