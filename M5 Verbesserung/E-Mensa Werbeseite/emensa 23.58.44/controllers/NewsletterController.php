<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/newsletter.php');

class NewsletterController {
    public function signup() {
        // Access POST data directly from $_POST
        $name = $_POST['namen'] ?? null;
        $email = $_POST['emails'] ?? null;
        $language = $_POST['sprache'] ?? null;
        $datenschutz = isset($_POST['datenschutz']) && $_POST['datenschutz'] === 'on' ? 1 : 0;

        // Check if all required fields are present and valid
        if ($datenschutz && $name && $email && $language && db_newsletter_insert($name, $email, $language, $datenschutz)) {
            // Redirect to the homepage on success
            header("Location: /");
            exit;
        } else {
            // Redirect with an error message if something goes wrong
            header("Location: /?error=newsletter");
            exit;
        }
    }
}

