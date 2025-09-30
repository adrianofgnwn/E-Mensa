<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/db.php');

/** Fetch user by email */
function db_fetch_user_by_email($email) {
    $link = connectdb();
    $sql = "SELECT id, name, email, passwort, anzahlfehler,admin FROM benutzer WHERE email = ?";
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        error_log("Statement preparation failed: " . mysqli_error($link));
        return null;
    }
    mysqli_stmt_bind_param($stmt, 's', $email);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Query execution failed: " . mysqli_stmt_error($stmt));
        return null;
    }
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_close($link);
    return $user;
}

/** Increment login count */
function db_increment_login_count($userId, $link) { // link itu connection ke sql base
    $sql = "CALL increment_registration_count(?)";  // Call the procedure
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        error_log("Statement preparation failed: " . mysqli_error($link));
        return false;
    }
    mysqli_stmt_bind_param($stmt, 'i', $userId);  // Bind the user ID
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Query execution failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    return true;
}

/** Update last successful login */
function db_update_last_login($userId, $link) {
    $sql = "UPDATE benutzer SET letzteanmeldung = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        error_log("Statement preparation failed: " . mysqli_error($link));
        return false;
    }
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Query execution failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    return true;
}

/** Update last failed login */
function db_update_last_failed_login($userId, $link) {
    $sql = "UPDATE benutzer SET letzterfehler = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        error_log("Statement preparation failed: " . mysqli_error($link));
        return false;
    }
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Query execution failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    return true;
}

/** Increment the number of login errors for a user */
function db_increment_error_count($userId, $link) {
    $sql = "UPDATE benutzer SET anzahlfehler = anzahlfehler + 1 WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        error_log("Statement preparation failed: " . mysqli_error($link));
        return false;
    }
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Query execution failed: " . mysqli_stmt_error($stmt));
        return false;
    }
    return true;
}
?>
