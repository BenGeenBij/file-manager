<?php
// config/config.php
session_start();

define('VERSION', '2.6');
define('APP_TITLE', 'Tiny File Manager');
define('FM_SESSION_ID', 'filemanager');

// Base storage directory and URLs
define('FM_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/storage');
define('FM_ROOT_URL', ''); 

// File upload limits and extensions
define('FM_FILE_EXTENSION', '');
define('FM_UPLOAD_EXTENSION', '');
define('MAX_UPLOAD_SIZE', 5000000000); 
define('UPLOAD_CHUNK_SIZE', 2000000);

// Generate CSRF Token for form security
if (empty($_SESSION['token'])) {
    if (function_exists('random_bytes')) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    } else {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}
?>