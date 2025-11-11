CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Skapa en testanvändare med lösenord "test123"
INSERT INTO users (username, password, email) VALUES 
('admin', '$2y$10$zCEqYPQ1cCvzRuQrsIRXBukloj51AYWfMgtlRhpUd508r.8lCy.Xu', 'admin@ksdk.se')
ON DUPLICATE KEY UPDATE 
    password = '$2y$10$zCEqYPQ1cCvzRuQrsIRXBukloj51AYWfMgtlRhpUd508r.8lCy.Xu';