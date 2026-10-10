<?php
// core/Auth.php

class Auth {
    private $db;
    private $session_id;

    public function __construct($db_connection) {
        $this->db = $db_connection;
        $this->session_id = FM_SESSION_ID; // Defined in config.php
    }

    /**
     * Authenticate a user against the database
     */
    public function login($username, $password, $csrf_token) {
        // 1. Verify CSRF Token to prevent cross-site request forgery
        if (!$this->verifyToken($csrf_token)) {
            $this->setFlashMessage('Invalid security token.', 'error');
            return false;
        }

        // 2. Prepare the statement to prevent SQL injection
        $query = "SELECT id, username, password_hash, role, directory_path FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Verify the password hash
        if ($user && password_verify($password, $user['password_hash'])) {
            // Secure against session fixation
            session_regenerate_id(true);

            // Set session variables
            $_SESSION[$this->session_id]['logged'] = $user['username'];
            $_SESSION[$this->session_id]['role'] = $user['role'];
            
            // Map the user to their specific directory inside /storage
            $_SESSION[$this->session_id]['directory'] = $user['directory_path'];

            return true;
        }

        // Sleep to deter brute-force attacks
        sleep(1); 
        $this->setFlashMessage('Login failed. Invalid username or password.', 'error');
        return false;
    }

    /**
     * Check if the current user is authenticated
     */
    public function isLoggedIn() {
        return isset($_SESSION[$this->session_id]['logged']);
    }

    /**
     * Get the logged-in user's assigned directory
     */
    public function getUserDirectory() {
        if ($this->isLoggedIn() && !empty($_SESSION[$this->session_id]['directory'])) {
            return $_SESSION[$this->session_id]['directory'];
        }
        return '';
    }

    /**
     * Log the user out and destroy the session
     */
    public function logout() {
        unset($_SESSION[$this->session_id]['logged']);
        unset($_SESSION[$this->session_id]['role']);
        unset($_SESSION[$this->session_id]['directory']);
        unset($_SESSION['token']);
        session_destroy();
    }

    /**
     * Verify CSRF TOKEN using hash_equals to prevent timing attacks
     */
    public function verifyToken($token) {
        if (isset($_SESSION['token']) && hash_equals($_SESSION['token'], $token)) {
            return true;
        }
        return false;
    }

    /**
     * Set a temporary message for the UI
     */
    public function setFlashMessage($msg, $status = 'ok') {
        $_SESSION[$this->session_id]['message'] = $msg;
        $_SESSION[$this->session_id]['status'] = $status;
    }
    
    /**
     * Get and clear the flash message
     */
    public function getFlashMessage() {
        if (isset($_SESSION[$this->session_id]['message'])) {
            $msg = [
                'text' => $_SESSION[$this->session_id]['message'],
                'status' => $_SESSION[$this->session_id]['status'] ?? 'ok'
            ];
            unset($_SESSION[$this->session_id]['message'], $_SESSION[$this->session_id]['status']);
            return $msg;
        }
        return null;
    }
}
?>