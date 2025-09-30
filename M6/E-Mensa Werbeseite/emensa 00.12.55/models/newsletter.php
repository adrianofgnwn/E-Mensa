<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/db.php');

/** Insert a new newsletter sign-up */
function db_newsletter_insert($name, $email, $language, $datenschutz) {
    try {
        $link = connectdb();
        $sql = "INSERT INTO newsletter (name, email, sprache, datenschutz) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $language, $datenschutz);
        mysqli_stmt_execute($stmt);
        mysqli_close($link);
        return true;
    } catch (Exception $ex) {
        error_log('Error during newsletter sign-up: ' . $ex->getMessage());
        return false;
    }
}
