<?php
require_once 'config.php';

try {
    $db = getDB();
    
    // Skapa events-tabell
    $db->exec("CREATE TABLE IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        event_date DATE NOT NULL,
        start_time TIME,
        end_time TIME,
        location VARCHAR(255),
        event_type VARCHAR(50) NOT NULL,
        max_participants INT,
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id)
    )");

    echo "Kalendertabellen har skapats framgångsrikt!";
    
} catch(PDOException $e) {
    die("Ett fel uppstod: " . $e->getMessage());
}
?>