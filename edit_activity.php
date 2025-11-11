<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: calendar.php');
    exit;
}

try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM activities WHERE id = ?");
    $stmt->execute([$id]);
    $activity = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$activity) {
        header('Location: calendar.php');
        exit;
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $note = $_POST['note'];

    try {
        $db = getDB();
        $stmt = $db->prepare("UPDATE activities SET date = ?, time = ?, title = ?, type = ?, note = ? WHERE id = ?");
        $stmt->execute([$date, $time, $title, $type, $note, $id]);
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
    <title>OllesAB - Edit Activity</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <main class="container">
        <h1>Edit Activity</h1>
        <?php if (isset($error)): ?>
            <p><?= $error ?></p>
        <?php endif; ?>
        <form action="edit_activity.php?id=<?= $id ?>" method="post">
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($activity['date']) ?>" required>
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="text" id="time" name="time" value="<?= htmlspecialchars($activity['time']) ?>">
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($activity['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="training" <?= $activity['type'] == 'training' ? 'selected' : '' ?>>Training</option>
                    <option value="event" <?= $activity['type'] == 'event' ? 'selected' : '' ?>>Event</option>
                    <option value="trip" <?= $activity['type'] == 'trip' ? 'selected' : '' ?>>Trip</option>
                </select>
            </div>
            <div class="form-group">
                <label for="note">Note</label>
                <textarea id="note" name="note"><?= htmlspecialchars($activity['note']) ?></textarea>
            </div>
            <button type="submit" class="btn btn--primary">Update Activity</button>
        </form>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
