<?php
// controllers/ApiController.php

if (!isset($auth) || !$auth->isLoggedIn()) {
    die(json_encode(['status' => 'error', 'message' => 'Unauthorized']));
}

if (!isset($_POST['token']) || !$auth->verifyToken($_POST['token'])) {
    $auth->setFlashMessage('Security token validation failed.', 'error');
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? 'index.php'));
    exit;
}

$action = $_POST['action'] ?? '';
$user_dir = $auth->getUserDirectory();
$base_path = realpath(FM_ROOT_PATH . '/' . $user_dir);

function getSecurePath($base_path, $target_path) {
    $target_path = str_replace(['../', '..\\'], '', $target_path);
    $full_path = rtrim($base_path . '/' . ltrim($target_path, '/'), '/');
    
    if (file_exists($full_path)) {
        if (strpos(realpath($full_path), $base_path) !== 0) return false;
        return realpath($full_path);
    }
    
    $parent_dir = realpath(dirname($full_path));
    if ($parent_dir && strpos($parent_dir, $base_path) === 0) return $full_path;
    
    return false;
}

switch ($action) {
    case 'save_file':
        $file_param = $_POST['file'] ?? '';
        $content = $_POST['content'] ?? '';
        $filepath = getSecurePath($base_path, $file_param);
        
        if ($filepath && is_file($filepath)) {
            if (file_put_contents($filepath, $content) !== false) {
                $auth->setFlashMessage('File saved successfully!', 'ok');
            } else {
                $auth->setFlashMessage('Error saving file. Check permissions.', 'error');
            }
        } else {
            $auth->setFlashMessage('Invalid file path.', 'error');
        }
        
        header("Location: index.php?action=edit&file=" . urlencode($file_param));
        break;

    case 'create_folder':
        $current_path = $_POST['current_path'] ?? '';
        $folder_name = $_POST['folder_name'] ?? '';
        
        // Use Helper to validate filename
        if (!Helpers::isValidFilename($folder_name) || empty($folder_name)) {
            $auth->setFlashMessage('Invalid folder name.', 'error');
        } else {
            $target_dir = getSecurePath($base_path, $current_path . '/' . $folder_name);
            if ($target_dir && !file_exists($target_dir)) {
                if (mkdir($target_dir, 0755, true)) {
                    $auth->setFlashMessage('Folder created successfully.', 'ok');
                } else {
                    $auth->setFlashMessage('Failed to create folder.', 'error');
                }
            } else {
                $auth->setFlashMessage('Folder already exists or invalid path.', 'error');
            }
        }
        header("Location: index.php?path=" . urlencode($current_path));
        break;

    case 'delete':
        $file_param = $_POST['file'] ?? '';
        $current_path = dirname($file_param) === '.' ? '' : dirname($file_param);
        $target_path = getSecurePath($base_path, $file_param);
        
        if ($target_path && $target_path !== $base_path) {
            // Using the new FileOperations class for recursive deletion
            if (FileOperations::deleteRecursive($target_path)) {
                $auth->setFlashMessage('Item deleted successfully.', 'ok');
            } else {
                $auth->setFlashMessage('Failed to delete item.', 'error');
            }
        } else {
            $auth->setFlashMessage('Invalid deletion request.', 'error');
        }
        header("Location: index.php?path=" . urlencode($current_path));
        break;

    case 'upload':
        $current_path = $_POST['current_path'] ?? '';
        $upload_dir = getSecurePath($base_path, $current_path);
        
        if ($upload_dir && is_dir($upload_dir) && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $filename = basename($_FILES['file']['name']);
            
            // Validate filename using Helper
            if (Helpers::isValidFilename($filename)) {
                $target_file = $upload_dir . '/' . $filename;
                
                if (!file_exists($target_file)) {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                        $auth->setFlashMessage('File uploaded successfully.', 'ok');
                    } else {
                        $auth->setFlashMessage('File upload failed during move.', 'error');
                    }
                } else {
                    $auth->setFlashMessage('File already exists.', 'error');
                }
            } else {
                $auth->setFlashMessage('Invalid characters in filename.', 'error');
            }
        } else {
            $auth->setFlashMessage('Upload failed or no file selected.', 'error');
        }
        header("Location: index.php?path=" . urlencode($current_path));
        break;

    default:
        $auth->setFlashMessage('Unknown action.', 'error');
        header("Location: index.php");
        break;
}
exit;
?>