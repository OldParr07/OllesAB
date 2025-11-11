<?php
require_once 'config.php';
require_once 'auth.php';

// Säkerhetsfunktion för att kontrollera behörighet
function checkPermission() {
    if (!isLoggedIn()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Du måste vara inloggad för att utföra denna åtgärd']);
        exit;
    }
}

function getAllMembers() {
    try {
        $db = getDB();
        // Hämta först styrelsemedlemmar, sedan övriga sektioner i bokstavsordning
        $stmt = $db->query("SELECT * FROM members 
            ORDER BY 
                CASE section
                    WHEN 'Styrelse' THEN 1
                    WHEN 'Produktion' THEN 2
                    WHEN 'Utveckling' THEN 3
                    WHEN 'IT' THEN 4
                    WHEN 'Säljare' THEN 5
                    ELSE 6
                END,
                CASE 
                    WHEN role LIKE '%VD%' THEN 1
                    WHEN role LIKE '%HR - ansvarig%' THEN 2
                    WHEN role LIKE '%Sekreterare - telefonväxel%' THEN 3
                    WHEN role LIKE '%Avdelningschef%' THEN 4
                    ELSE 5
                END,
                role, 
                name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching members: " . $e->getMessage());
        return [];
    }
}

function getMember($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM members WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching member: " . $e->getMessage());
        return null;
    }
}

function addMember($name, $role, $email, $phone, $section) {
    checkPermission();
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO members (name, role, email, phone, section) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $role, $email, $phone, $section]);
    } catch(PDOException $e) {
        error_log("Error adding member: " . $e->getMessage());
        return false;
    }
}

function updateMember($id, $name, $role, $email, $phone, $section) {
    checkPermission();
    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE members SET name = ?, role = ?, email = ?, phone = ?, section = ? WHERE id = ?");
        return $stmt->execute([$name, $role, $email, $phone, $section, $id]);
    } catch(PDOException $e) {
        error_log("Error updating member: " . $e->getMessage());
        return false;
    }
}

function deleteMember($id) {
    checkPermission();
    try {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM members WHERE id = ?");
        return $stmt->execute([$id]);
    } catch(PDOException $e) {
        error_log("Error deleting member: " . $e->getMessage());
        return false;
    }
}

// Hantera AJAX-förfrågningar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $response = ['success' => false, 'message' => ''];

    // Kontrollera att användaren är inloggad
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'Du måste vara inloggad för att utföra denna åtgärd';
        echo json_encode($response);
        exit;
    }

    switch ($_POST['action']) {
        case 'add':
            if (addMember($_POST['name'], $_POST['role'], $_POST['email'], $_POST['phone'], $_POST['section'])) {
                $response['success'] = true;
                $response['message'] = 'Medlem tillagd';
            } else {
                $response['message'] = 'Kunde inte lägga till medlem';
            }
            break;

        case 'update':
            if (updateMember($_POST['id'], $_POST['name'], $_POST['role'], $_POST['email'], $_POST['phone'], $_POST['section'])) {
                $response['success'] = true;
                $response['message'] = 'Medlem uppdaterad';
            } else {
                $response['message'] = 'Kunde inte uppdatera medlem';
            }
            break;

        case 'delete':
            if (deleteMember($_POST['id'])) {
                $response['success'] = true;
                $response['message'] = 'Medlem borttagen';
            } else {
                $response['message'] = 'Kunde inte ta bort medlem';
            }
            break;
    }

    echo json_encode($response);
    exit;
}
?>