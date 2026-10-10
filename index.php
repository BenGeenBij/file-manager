<?php
// index.php

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'core/Auth.php';
require_once 'core/Helpers.php';         // Added Helpers
require_once 'core/FileOperations.php';  // Added FileOperations

// Initialize Database and Auth modules
$db = new Database();
$connection = $db->getConnection();
$auth = new Auth($connection);

// Route: Logout
if (isset($_GET['logout'])) {
    $auth->logout();
    header('Location: index.php');
    exit;
}

// Route: Form Submissions / Actions (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] !== 'login') {
    require 'controllers/ApiController.php';
    exit;
}

// Route: Standard Views (Login, File List, Editor)
require 'controllers/ViewController.php';
?>