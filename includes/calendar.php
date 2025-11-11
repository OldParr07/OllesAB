<?php
require_once 'config.php';
require_once 'auth.php';

function getAllEvents() {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT events.*, users.username as creator_name 
                           FROM events 
                           LEFT JOIN users ON events.created_by = users.id 
                           ORDER BY event_date ASC, start_time ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching events: " . $e->getMessage());
        return [];
    }
}

function getEvent($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching event: " . $e->getMessage());
        return null;
    }
}

function addEvent($title, $description, $event_date, $start_time, $end_time, $location, $event_type, $max_participants, $created_by) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO events (title, description, event_date, start_time, end_time, location, event_type, max_participants, created_by) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $event_date, $start_time, $end_time, $location, $event_type, $max_participants, $created_by]);
    } catch(PDOException $e) {
        error_log("Error adding event: " . $e->getMessage());
        return false;
    }
}

function updateEvent($id, $title, $description, $event_date, $start_time, $end_time, $location, $event_type, $max_participants) {
    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE events 
                             SET title = ?, description = ?, event_date = ?, 
                                 start_time = ?, end_time = ?, location = ?, 
                                 event_type = ?, max_participants = ? 
                             WHERE id = ?");
        return $stmt->execute([$title, $description, $event_date, $start_time, $end_time, $location, $event_type, $max_participants, $id]);
    } catch(PDOException $e) {
        error_log("Error updating event: " . $e->getMessage());
        return false;
    }
}

function deleteEvent($id) {
    try {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    } catch(PDOException $e) {
        error_log("Error deleting event: " . $e->getMessage());
        return false;
    }
}

// Hantera AJAX-förfrågningar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'message' => 'Du måste vara inloggad']);
        exit;
    }

    $response = ['success' => false, 'message' => ''];

    switch ($_POST['action']) {
        case 'add':
            if (addEvent(
                $_POST['title'],
                $_POST['description'],
                $_POST['event_date'],
                $_POST['start_time'],
                $_POST['end_time'],
                $_POST['location'],
                $_POST['event_type'],
                $_POST['max_participants'],
                $_SESSION['user_id']
            )) {
                $response['success'] = true;
                $response['message'] = 'Aktivitet tillagd';
            } else {
                $response['message'] = 'Kunde inte lägga till aktivitet';
            }
            break;

        case 'update':
            if (updateEvent(
                $_POST['id'],
                $_POST['title'],
                $_POST['description'],
                $_POST['event_date'],
                $_POST['start_time'],
                $_POST['end_time'],
                $_POST['location'],
                $_POST['event_type'],
                $_POST['max_participants']
            )) {
                $response['success'] = true;
                $response['message'] = 'Aktivitet uppdaterad';
            } else {
                $response['message'] = 'Kunde inte uppdatera aktivitet';
            }
            break;

        case 'delete':
            if (deleteEvent($_POST['id'])) {
                $response['success'] = true;
                $response['message'] = 'Aktivitet borttagen';
            } else {
                $response['message'] = 'Kunde inte ta bort aktivitet';
            }
            break;
    }

    echo json_encode($response);
    exit;
}
?>