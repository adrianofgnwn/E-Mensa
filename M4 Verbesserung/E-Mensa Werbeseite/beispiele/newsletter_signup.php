<?php
// Function to validate the email address
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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vorname = trim($_POST['vorname']);
    $nachname = trim($_POST['nachname']);
    $email = trim($_POST['email']);
    $intervall = $_POST['interval'];
    $privacy = isset($_POST['datenschutz']) ? true : false;

    $errors = [];
    if (empty($vorname) || ctype_space($vorname)) {
        $errors[] = "Bitte geben Sie einen gültigen Vornamen ein.";
    }
    if (empty($nachname) || ctype_space($nachname)) {
        $errors[] = "Bitte geben Sie einen gültigen Nachnamen ein.";
    }
    if (!is_valid_email($email)) {
        $errors[] = "Die E-Mail-Adresse ist ungültig oder stammt von einer nicht erlaubten Domain.";
    }
    if (!$privacy) {
        $errors[] = "Sie müssen den Datenschutzbestimmungen zustimmen.";
    }

    // Display errors if any
    if (!empty($errors)) {
        echo "<p style='color: red;'>" . implode("<br>", $errors) . "</p>";
        echo "<br><button onclick='window.history.back()'>Zurück</button>";
    } else {
        // Valid data, save to file
        $subscriber_data = [
            'vorname' => $vorname,
            'nachname' => $nachname,
            'email' => $email,
            'intervall' => $intervall,
            'privacy' => $privacy
        ];

        // File where data will be saved
        $file_path = 'newsletter_subscribers.json';

        // Check if file exists and read existing data
        if (file_exists($file_path)) {
            $current_data = json_decode(file_get_contents($file_path), true);
        } else {
            $current_data = [];
        }

        // Append new subscriber data
        $current_data[] = $subscriber_data;

        // Save data to the file
        if (file_put_contents($file_path, json_encode($current_data, JSON_PRETTY_PRINT))) {
            // Increment the signup count
            $signup_count_file = 'newsletter_count.txt';

            if (file_exists($signup_count_file)) {
                $signup_count = (int)file_get_contents($signup_count_file);
            } else {
                $signup_count = 0;
            }

            // Increment and save the updated signup count
            $signup_count++;
            file_put_contents($signup_count_file, $signup_count);

            echo "<p style='color: green;'>Danke für Ihre Anmeldung! Sie erhalten bald unseren Newsletter.</p>";
        } else {
            echo "<p style='color: red;'>Ein Fehler ist bei der Speicherung Ihrer Daten aufgetreten. Bitte versuchen Sie es später noch einmal.</p>";
        }
    }
}
?>
