<?php
// Funktion zur Validierung der E-Mail-Adresse
function is_valid_email($email) {
    // Prüft das E-Mail-Format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    // CHECKS IF THE EMAIL IS IN THE DOMAIN
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

    // Name überprüfen (darf nicht leer sein)
    if (empty($name) || ctype_space($name)) {
        $errors[] = "Bitte geben Sie einen gültigen Namen ein.";
    }

    // E-Mail-Adresse überprüfen
    if (!is_valid_email($email)) {
        $errors[] = "Die E-Mail-Adresse ist ungültig oder stammt von einer nicht erlaubten Domain.";
    }

    // Datenschutzbestimmung überprüfen
    if (!$privacy) {
        $errors[] = "Sie müssen den Datenschutzbestimmungen zustimmen.";
    }

    // Wenn es Fehler gibt, zeige diese an
    if (!empty($errors)) {
        echo "<p style='color: red;'>" . implode("<br>", $errors) . "</p>"; //print kalimat error salah
        echo "<br><button onclick='window.history.back()'>Zurück</button>";
    } else {
        // Erfolgreiche Validierung, speichern der Anmeldungen



        // BUKA DATA BUAT NGESAVE ISIAN
        $data_file = 'newsletter_subscribers.json';

        // Wenn die Datei existiert, lade den Inhalt
        if (file_exists($data_file)) {
            $subscribers = json_decode(file_get_contents($data_file), true);
        } else {
            $subscribers = [];
        }

        // Speichere die neuen Anmeldedaten
        $subscriber = [
            'name' => $name,
            'email' => $email,
            'language' => $sprache,
            'datenschutz' => $privacy,
            'timestamp' => time() // Speichern des Zeitpunkts der Anmeldung
        ];

        // masukin subscriber ke array nye
        $subscribers[] = $subscriber;

        // Speichere die aktualisierte Liste der Anmeldungen zurück in die Datei
        file_put_contents($data_file, json_encode($subscribers, JSON_PRETTY_PRINT)); //This function converts a PHP array or object into a JSON string.

        // Erhöhe die Anzahl der Anmeldungen
        $signup_file = 'newsletter_count.txt';
        if (file_exists($signup_file)) {
            $signup_count = (int)file_get_contents($signup_file);
        } else {
            $signup_count = 0;
        }
        $signup_count++;

        // masukin angka baru di dalem
        file_put_contents($signup_file, $signup_count);

        // Erfolgsmeldung anzeigen
        echo "<p style='color: green;'>Danke für Ihre Anmeldung! Sie erhalten bald unseren Newsletter.</p>";
        echo "<br><a href='index.php'>Zurück zur Startseite</a>";
    }
}
?>
