<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/db.php');

function db_add_bewertung($userId, $gerichtId, $bemerkung, $sterne) {
    $link = connectdb();
    $sql = "INSERT INTO bewertung (user_id, gericht_id, bemerkung, sterne) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'iiss', $userId, $gerichtId, $bemerkung, $sterne);
    mysqli_stmt_execute($stmt);
    mysqli_close($link);
}

function db_fetch_latest_bewertungen($limit) {
    $link = connectdb();
    $sql = "
        SELECT b.*, g.name AS gericht_name, g.bildname, g.id AS gericht_id
        FROM bewertung b
        JOIN gericht g ON b.gericht_id = g.id
        ORDER BY b.bewertungszeitpunkt DESC
        LIMIT ?
    ";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $bewertungen = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['gericht_image'] = db_get_dish_image($row['gericht_id'], $row['bildname']);
        $bewertungen[] = $row;
    }

    mysqli_close($link);
    return $bewertungen;
}




function db_fetch_user_bewertungen($userId) {
    $link = connectdb();
    $sql = "
        SELECT b.*, g.name AS gericht_name, g.bildname, g.id AS gericht_id
        FROM bewertung b
        JOIN gericht g ON b.gericht_id = g.id
        WHERE b.user_id = ?
        ORDER BY b.bewertungszeitpunkt DESC
    ";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $bewertungen = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['gericht_image'] = db_get_dish_image($row['gericht_id'], $row['bildname']);
        $bewertungen[] = $row;
    }

    mysqli_close($link);
    return $bewertungen;
}


function db_delete_bewertung($bewertungId, $userId) {
    $link = connectdb();
    $sql = "DELETE FROM bewertung WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $bewertungId, $userId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_close($link);
    return $result;
}


function db_update_bewertung_hervorgehoben($bewertungId, $highlight) {
    $link = connectdb();
    $sql = "UPDATE bewertung SET hervorgehoben = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    $highlightValue = $highlight ? 1 : 0;
    mysqli_stmt_bind_param($stmt, 'ii', $highlightValue, $bewertungId);
    mysqli_stmt_execute($stmt);
    mysqli_close($link);
}

?>
