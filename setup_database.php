<?php
// Först, anslut utan att välja databas
try {
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Skapa databasen om den inte finns
    $pdo->exec("CREATE DATABASE IF NOT EXISTS ollesab DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Databasen 'ollesab' har skapats eller fanns redan.\n";
    
    // Välj databasen
    $pdo->exec("USE ollesab");
    
    // Drop the table if it exists
    $pdo->exec("DROP TABLE IF EXISTS members");
    $pdo->exec("DROP TABLE IF EXISTS activities");
    
    // Skapa members-tabellen
    $pdo->exec("CREATE TABLE members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        section VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Skapa activities-tabellen
    $pdo->exec("CREATE TABLE activities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        time VARCHAR(50),
        title VARCHAR(255) NOT NULL,
        type VARCHAR(50),
        note TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    
    echo "Databastabellerna 'members' och 'activities' har skapats framgångsrikt!\n";
    
} catch(PDOException $e) {
    echo "Fel vid databasuppsättning: " . $e->getMessage() . "\n";
}
?>