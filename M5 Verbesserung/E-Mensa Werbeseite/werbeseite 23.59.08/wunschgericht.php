<?php
session_start();

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Ungültiges CSRF-Token!");
    }

    // Sanitize User Input
    $creator_name = !empty($_POST['creator_name']) ? htmlspecialchars($_POST['creator_name'], ENT_QUOTES, 'UTF-8') : 'anonym';
    $creator_email = filter_var($_POST['creator_email'], FILTER_SANITIZE_EMAIL);
    $dish_name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');

    if (!filter_var($creator_email, FILTER_VALIDATE_EMAIL)) {
        die("Ungültige E-Mail-Adresse!");
    }

    // Database Connection
    $conn = new mysqli('localhost', 'root', 'root', 'emensawerbeseite');
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

    // Insert into Ersteller
    $stmt = $conn->prepare("INSERT INTO Ersteller (name, email) VALUES (?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
    $stmt->bind_param("ss", $creator_name, $creator_email);
    $stmt->execute();
    $creator_id = $stmt->insert_id;
    $stmt->close();

    // Insert into Wunschgericht
    $stmt = $conn->prepare("INSERT INTO Wunschgericht (name, description, creation_date, creator_id) VALUES (?, ?, CURRENT_DATE, ?)");
    $stmt->bind_param("ssi", $dish_name, $description, $creator_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<p>Vielen Dank! Ihr Wunschgericht wurde erfolgreich eingetragen.</p>";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wunschgericht eintragen</title>
</head>
<body>
<h1>Wunschgericht eintragen</h1>
<form method="POST" action="">
    <label for="creator_name">Ihr Name:</label><br>
    <input type="text" id="creator_name" name="creator_name" placeholder="Ihr Name (optional)"><br><br>

    <label for="creator_email">Ihre E-Mail:</label><br>
    <input type="email" id="creator_email" name="creator_email" placeholder="Ihre E-Mail" required><br><br>

    <label for="name">Name des Gerichts:</label><br>
    <input type="text" id="name" name="name" placeholder="Name des Gerichts" required><br><br>

    <label for="description">Beschreibung:</label><br>
    <textarea id="description" name="description" placeholder="Beschreibung des Gerichts" rows="4" required></textarea><br><br>

    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <button type="submit">Wunsch abschicken</button>
</form>
<a href="../werbeseite/werbeseite.php">Zurück zur Startseite</a>
</body>
</html>
