<?php
// Database connection settings
$servername = "localhost";  // Replace with your server
$username = "root";  // Replace with your database username
$password = "root";  // Replace with your database password
$dbname = "emensawerbeseite";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Funktion zur Validierung der E-Mail-Adresse
function is_valid_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    $blocked_domains = ['wegwerfmail.de', 'trashmail.de', 'trashmail.com'];
    $email_domain = substr(strrchr($email, "@"), 1);
    if (in_array($email_domain, $blocked_domains)) {
        return false;
    }
    return true;
}

// Überprüfen, ob das Formular übermittelt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Eingabedaten aus dem Formular holen
    $name = trim($_POST['namen']);
    $email = trim($_POST['emails']);
    $sprache = $_POST['sprache'];
    $privacy = isset($_POST['datenschutz']) ? true : false;

    // VALIDATE THE INPUT
    $errors = [];

    if (empty($name) || ctype_space($name)) {
        $errors[] = "Bitte geben Sie einen gültigen Namen ein.";
    }

    if (!is_valid_email($email)) {
        $errors[] = "Die E-Mail-Adresse ist ungültig oder stammt von einer nicht erlaubten Domain.";
    }

    if (!$privacy) {
        $errors[] = "Sie müssen den Datenschutzbestimmungen zustimmen.";
    }

    if (!empty($errors)) { // KALO ERRORNYA ADA
        echo "<p style='color: red;'>" . implode("<br>", $errors) . "</p>";
        echo "<br><button onclick='window.history.back()'>Zurück</button>";
    } else {
        // Insert the form data into the database
        $sql = "INSERT INTO newsletter (name, email, sprache, datenschutz) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $sprache, $privacy);
            //bind_param adds the users input into the query
        if ($stmt->execute()) {
            echo "<p style='color: green;'>Danke für Ihre Anmeldung! Sie erhalten bald unseren Newsletter.</p>";
        } else {
            echo "<p style='color: red;'>Es gab ein Problem bei der Anmeldung. Bitte versuchen Sie es später noch einmal.</p>";
        }

        // Get the total number of signups from the database
        $sql = "SELECT COUNT(*) AS total_signups FROM newsletter";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_signups = $row['total_signups'];

        // Display the total sign-up count
        echo "<p>Aktuelle Anzahl der Newsletter-Anmeldungen: $total_signups</p>";

        $stmt->close();
    }
}

$conn->close();
?>
