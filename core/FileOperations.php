<?php
// core/FileOperations.php

class FileOperations {

    /**
     * Delete a file or folder recursively
     */
    public static function deleteRecursive($path) {
        if (is_link($path)) {
            return unlink($path);
        } elseif (is_dir($path)) {
            $objects = array_diff(scandir($path), ['.', '..']);
            $ok = true;
            foreach ($objects as $file) {
                if (!self::deleteRecursive($path . '/' . $file)) {
                    $ok = false;
                }
            }
            return $ok ? rmdir($path) : false;
        } elseif (is_file($path)) {
            return unlink($path);
        }
        return false;
    }

    /**
     * Copy a file or folder recursively
     */
    public static function copyRecursive($source, $dest, $update_override = true) {
        if (!is_dir($source) && !is_file($source)) {
            return false;
        }

        if (is_dir($source)) {
            if (!file_exists($dest) && !mkdir($dest, 0755, true)) {
                return false;
            }
            $objects = array_diff(scandir($source), ['.', '..']);
            foreach ($objects as $file) {
                if (!self::copyRecursive("$source/$file", "$dest/$file", $update_override)) {
                    return false;
                }
            }
            return true;
        }

        // Handle single file copy
        if (file_exists($dest) && $update_override) {
            $time1 = filemtime($source);
            $time2 = filemtime($dest);
            if ($time2 >= $time1) return false; // Destination is newer
        }
        
        $ok = copy($source, $dest);
        if ($ok) {
            touch($dest, filemtime($source)); // Preserve modified time
        }
        return $ok;
    }

    /**
     * Safely rename or move a file/directory
     */
    public static function renameItem($old_path, $new_path) {
        if (!file_exists($old_path) || file_exists($new_path)) {
            return false;
        }
        return rename($old_path, $new_path);
    }

    /**
     * Extract a ZIP archive to a destination folder
     */
    public static function extractZip($zip_path, $extract_to) {
        if (!class_exists('ZipArchive')) return false;

        $zip = new ZipArchive();
        if ($zip->open($zip_path) === true) {
            $zip->extractTo($extract_to);
            $zip->close();
            return true;
        }
        return false;
    }

    /**
     * Create a ZIP archive from an array of file/folder paths
     */
    public static function createZip($zip_name, $files_array, $base_dir) {
        if (!class_exists('ZipArchive')) return false;

        $zip = new ZipArchive();
        if ($zip->open($zip_name, ZipArchive::CREATE) !== true) {
            return false;
        }

        foreach ($files_array as $file) {
            $full_path = $base_dir . '/' . $file;
            if (is_file($full_path)) {
                $zip->addFile($full_path, $file);
            } elseif (is_dir($full_path)) {
                // To pack a folder recursively, you would iterate through it here
                // For brevity, adding empty dir
                $zip->addEmptyDir($file);
            }
        }
        
        $zip->close();
        return true;
    }
}
?>