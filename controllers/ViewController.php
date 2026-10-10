<?php
// controllers/ViewController.php

if (!isset($auth)) {
    die('Direct access not permitted');
}

// Handle Login Form Submission
if (!$auth->isLoggedIn() && isset($_POST['action']) && $_POST['action'] === 'login') {
    $auth->login($_POST['username'], $_POST['password'], $_POST['token'] ?? '');
    header("Location: index.php");
    exit;
}

if (!$auth->isLoggedIn()) {
    require 'views/login.php';
    exit;
}

$user_dir = $auth->getUserDirectory();
$base_path = rtrim(FM_ROOT_PATH . '/' . $user_dir, '/');

if (!is_dir($base_path)) {
    mkdir($base_path, 0777, true);
}

// Sanitize path
$request_path = isset($_GET['path']) ? $_GET['path'] : '';
$request_path = trim($request_path, '/');
$request_path = str_replace(['../', '..\\'], '', $request_path);

$current_full_path = $base_path . ($request_path !== '' ? '/' . $request_path : '');

if (realpath($current_full_path) && strpos(realpath($current_full_path), realpath($base_path)) !== 0) {
    $auth->setFlashMessage('Access denied.', 'error');
    header("Location: index.php");
    exit;
}

$action = $_GET['action'] ?? 'list';

if ($action === 'edit' && isset($_GET['file'])) {
    $file_param = str_replace(['../', '..\\'], '', $_GET['file']);
    $filepath = $base_path . '/' . $file_param;
    
    if (is_file($filepath)) {
        $filename = basename($filepath);
        $current_path = dirname($file_param) === '.' ? '' : dirname($file_param);
        $file_content = file_get_contents($filepath);
        
        require 'views/editor.php';
    } else {
        $auth->setFlashMessage('File not found.', 'error');
        header("Location: index.php?path=" . urlencode(dirname($file_param)));
    }
    exit;
}

$folders = [];
$files = [];

if (is_dir($current_full_path)) {
    $items = scandir($current_full_path);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $item_path = $current_full_path . '/' . $item;
        
        if (is_dir($item_path)) {
            $folders[] = $item;
        } elseif (is_file($item_path)) {
            // Using the Helpers class here
            $files[] = [
                'name' => $item,
                'size' => Helpers::formatSize(Helpers::getFileSize($item_path)),
                'modified' => date('Y-m-d H:i', filemtime($item_path)),
                'icon' => Helpers::getFileIconClass($item_path)
            ];
        }
    }
}

natcasesort($folders);
usort($files, function($a, $b) {
    return strnatcasecmp($a['name'], $b['name']);
});

$current_path = $request_path;
require 'views/table.php';
?>