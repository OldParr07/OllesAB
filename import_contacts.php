<?php
require_once 'includes/config.php';

// Array med alla kontakter från olika sektioner
$contacts = [
                "Styrelse" => [
                    [
                        'role' => 'VD',
                        'name' => 'Glenn Klappfentrikel',
                        'phone' => '123-456-78 90',
                        'email' => 'VD@OllesAB.com'
                    ],
                    [
                        'role' => 'HR - ansvarig',
                        'name' => 'Helga Braun',
                        'phone' => '123-456 78 91',
                        'email' => 'HR@OllesAB.com'
                    ],
                    [
                        'role' => 'Sektreterare - telefonväxel',
                        'name' => 'Paulina Olsson',
                        'phone' => '123-456 78 92',
                        'email' => 'Sektrerare@OllesAB.com'
                    ],
                ],
                "Produktion" => [
                    [
                        'role' => 'Avdelningschef, Produktionsansvarig',
                        'name' => 'Greger Andersson',
                        'phone' => '123-456 78 93',
                        'email' => 'Greger@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC-programmering, CNC',
                        'name' => 'Bob "Lillen" Mkumba',
                        'phone' => '123-456 78 93',
                        'email' => 'lillen@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC, Lager/Truck',
                        'name' => 'Pekka Miillikainen',
                        'phone' => '123-456 78 93',
                        'email' => 'Pekka@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC',
                        'name' => 'Gunilla Eriksson',
                        'phone' => '123-456 78 93',
                        'email' => 'Gunilla@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC, Facklig företrädare för IF Metall',
                        'name' => 'Eva Braun',
                        'phone' => '123-456 78 93',
                        'email' => 'Eva@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC',
                        'name' => 'Patrik Svensson',
                        'phone' => '123-456 78 93',
                        'email' => 'Patrik@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC',
                        'name' => 'Lisbeth Puktrumma',
                        'phone' => '123-456 78 93',
                        'email' => 'Lisbeth@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC',
                        'name' => 'Henry Mitäpää',
                        'phone' => '123-456 78 93',
                        'email' => 'Henry@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC',
                        'name' => 'Omar Muhammed',
                        'phone' => '123-456 78 93',
                        'email' => 'Omar@OllesAB.com'
                    ],
                    [
                        'role' => 'CNC',
                        'name' => 'Bruce Bannerhag',
                        'phone' => '123-456 78 93',
                        'email' => 'Bruce@OllesAB.com'
                    ],
                ],
                "Utveckling" => [
                    [
                        'role' => 'Utvecklingsledare - Designer',
                        'name' => 'Hans von Lilleskog',
                        'phone' => '123-456-78 90',
                        'email' => 'Hans@OllesAB.com'
                    ],
                    [
                        'role' => 'Designer/Beräkningar, Ritningsunderlag',
                        'name' => 'Pamela Andersson',
                        'phone' => '123-456 78 91',
                        'email' => 'Pamela@OllesAB.com'
                    ],
                    [
                        'role' => 'Ritningsunderlag - Forskning',
                        'name' => 'Peter Norrut',
                        'phone' => '123-456 78 92',
                        'email' => 'Peter@OllesAB.com'
                    ],
                ],
                "IT" => [
                    [
                        'role' => 'Datorsystem, Telefonväxelprogrammering, webbmaster',
                        'name' => 'Muhammed Ahdmin',
                        'phone' => '123-456-78 90',
                        'email' => 'Ahdmin@OllesAB.com'
                    ],
                ],
                "Säljare" => [
                    [
                        'role' => 'Säljchef - Marknadsföring',
                        'name' => 'Åke "CZ" Carlzon',
                        'phone' => '123-456-78 90',
                        'email' => 'CZ@OllesAB.com'
                    ],
                    [
                        'role' => 'Säljare - Marknadsföring',
                        'name' => 'Lisa Karlsson',
                        'phone' => '123-456-78 90',
                        'email' => 'Lisa@OllesAB.com'
                    ],
                ],

            ];

try {
    $db = getDB();
    
    // Rensa tabellen först för att undvika dubletter
    $db->exec("TRUNCATE TABLE members");
    
    $stmt = $db->prepare("INSERT INTO members (name, role, email, phone, section) VALUES (?, ?, ?, ?, ?)");
    
    $totalContacts = 0;
    
    foreach ($contacts as $section => $people) {
        foreach ($people as $person) {
            // Om rollen inte innehåller sektionsnamnet, lägg till det som separat fält
            $stmt->execute([
                $person['name'],
                $person['role'],
                $person['email'],
                $person['phone'],
                $section
            ]);
            $totalContacts++;
        }
    }
    
    echo "Importerade " . $totalContacts . " kontakter framgångsrikt!";
    
} catch(PDOException $e) {
    echo "Fel vid import: " . $e->getMessage();
}