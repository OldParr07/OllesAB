<?php
session_start();
require_once 'config.php';

function login($username, $password) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
    } catch(PDOException $e) {
        error_log("Login error: " . $e->getMessage());
    }
    return false;
}

function logout() {
    session_destroy();
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Hantera inloggningsförsök
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'login' && isset($_POST['username']) && isset($_POST['password'])) {
        $loginSuccess = login($_POST['username'], $_POST['password']);
        if ($loginSuccess) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Felaktigt användarnamn eller lösenord']);
        }
        exit;
    } elseif ($_POST['action'] === 'logout') {
        logout();
        echo json_encode(['success' => true]);
        exit;
    }
}
?>