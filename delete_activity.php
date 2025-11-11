<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM activities WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Handle error, maybe log it
        error_log("Delete error: " . $e->getMessage());
    }
}

header('Location: calendar.php');
exit;
?>