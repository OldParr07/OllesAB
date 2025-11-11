<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$date = $_GET['date'] ?? date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $note = $_POST['note'];

    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO activities (date, time, title, type, note) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$date, $time, $title, $type, $note]);
        header('Location: calendar.php');
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OllesAB - Lägg till aktivitet</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: var(--space-8) auto;
            padding: var(--space-8);
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            text-align: left;
        }

        .form-title {
            text-align: center;
            margin-bottom: var(--space-6);
            color: var(--primary);
        }

        .activity-form .form-group {
            margin-bottom: var(--space-4);
        }

        .activity-form label {
            display: block;
            margin-bottom: var(--space-2);
            font-weight: 600;
            color: var(--text-secondary);
        }

        .activity-form input,
        .activity-form select,
        .activity-form textarea {
            width: 100%;
            padding: var(--space-3);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 1rem;
            transition: border-color var(--transition-fast);
        }

        .activity-form input:focus,
        .activity-form select:focus,
        .activity-form textarea:focus {
            outline: none;
            border-color: var(--primary);
        }

        .activity-form .btn {
            width: 100%;
            padding: var(--space-4);
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <main class="container">
        <div class="form-container">
            <h1 class="form-title">Lägg till aktivitet</h1>
            <?php if (isset($error)): ?>
                <p class="error-message"><?= $error ?></p>
            <?php endif; ?>
            <form action="add_activity.php" method="post" class="activity-form">
                <div class="form-group">
                    <label for="date">Datum</label>
                    <input type="date" id="date" name="date" value="<?= htmlspecialchars($date) ?>" required>
                </div>
                <div class="form-group">
                    <label for="time">Tid</label>
                    <input type="text" id="time" name="time" placeholder="HH:MM-HH:MM">
                </div>
                <div class="form-group">
                    <label for="title">Titel</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="type">Typ</label>
                    <select id="type" name="type">
                        <option value="training">Jobb</option>
                        <option value="event">Event</option>
                        <option value="trip">Resa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="note">Notering</label>
                    <textarea id="note" name="note"></textarea>
                </div>
                <button type="submit" class="btn btn--primary">Lägg till aktivitet</button>
            </form>
        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
