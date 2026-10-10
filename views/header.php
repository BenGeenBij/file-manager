<?php
// views/header.php
if (!defined('APP_TITLE')) exit; 
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars(APP_TITLE) ?></title>
    
    <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body { background: #F7F7F7; font-size: 15px; }
        .navbar-brand { font-weight: bold; }
        .message { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .message.ok { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<?php if (isset($auth) && $auth->isLoggedIn()): ?>
<nav class="navbar navbar-expand-lg mb-4 bg-body-tertiary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><?= htmlspecialchars(APP_TITLE) ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="?logout=1"><i class="fa fa-sign-out"></i> Sign Out</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container-fluid">
    <?php 
    // Display Flash Messages
    if (isset($auth)) {
        $msg = $auth->getFlashMessage();
        if ($msg) {
            echo '<div class="message ' . htmlspecialchars($msg['status']) . '">' . htmlspecialchars($msg['text']) . '</div>';
        }
    }
    ?>