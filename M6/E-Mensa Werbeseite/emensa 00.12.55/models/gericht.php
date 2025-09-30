<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/db.php');

/** Fetch all dishes ordered by name */

function db_gericht_select_all() {
    try {
        $link = connectdb();
        $sql = 'SELECT id, name, beschreibung FROM gericht ORDER BY name';
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_BOTH);
        mysqli_close($link);
    } catch (Exception $ex) {
        $data = [
            ['id' => '-1', 'error' => true,
                'name' => 'Datenbankfehler ' . $ex->getCode(),
                'beschreibung' => $ex->getMessage()]
        ];
    }
    return $data;
}

/** Count the number of dishes */
function db_gericht_count() {
    $link = connectdb();
    $sql = "SELECT COUNT(*) AS anzahl_gerichte FROM gericht";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($link);
    return $row['anzahl_gerichte'] ?? 0;
}

/** Count the number of newsletter sign-ups */
function db_newsletter_count() {
    $link = connectdb();
    $sql = "SELECT COUNT(*) AS anzahl_anmeldungen FROM newsletter";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($link);
    return $row['anzahl_anmeldungen'] ?? 0;
}

/** Count dishes by category */
function db_dishes_by_category() {
    $link = connectdb();
    $sql = "SELECT kategorie.name AS kategorie, COUNT(*) AS anzahl
            FROM gericht 
            JOIN gericht_hat_kategorie ghk ON gericht.id = ghk.gericht_id
            JOIN kategorie ON ghk.kategorie_id = kategorie.id
            GROUP BY kategorie.name";
    $result = mysqli_query($link, $sql);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['anzahl'] > 2) {
            $categories[$row['kategorie']] = $row['anzahl'];
        }
    }
    mysqli_close($link);
    return $categories;
}

/** Fetch dishes with allergens */
function db_fetch_dishes_with_allergens($sortOrder = 'ASC') {
    $link = connectdb();
    $sql = "SELECT id, name, preisintern, preisextern,bildname 
            FROM gericht 
            ORDER BY name $sortOrder LIMIT 5";
    $result = mysqli_query($link, $sql);

    $dishes = [];
    $allergens_used = [];
    while ($dish = mysqli_fetch_assoc($result)) {
        $dish_id = $dish['id'];
        $allergen_query = "SELECT code FROM gericht_hat_allergen WHERE gericht_id = $dish_id";
        $allergen_result = mysqli_query($link, $allergen_query);

        $allergens = [];
        while ($allergen_row = mysqli_fetch_assoc($allergen_result)) {
            $allergens[] = $allergen_row['code'];
            $allergens_used[$allergen_row['code']] = true;
        }

        $dish['allergens'] = $allergens;
        $dishes[] = $dish;
    }
    mysqli_close($link);

    return ['dishes' => $dishes, 'unique_allergens' => array_keys($allergens_used)];
}

/** Update visitor counter */
function db_update_visitor_count() {
    $link = connectdb();
    $sql = "INSERT INTO visitor_counter (id, visit_count) VALUES (1, 1)
            ON DUPLICATE KEY UPDATE visit_count = visit_count + 1";
    mysqli_query($link, $sql);
    $visitor_query = "SELECT visit_count FROM visitor_counter LIMIT 1";
    $result = mysqli_query($link, $visitor_query);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($link);
    return $row['visit_count'] ?? 0;
}

function db_get_dish_image($dishId, $bildname) {
    // Construct the image path relative to the web root
    $imagePath = "/img/gerichte/gerichte/{$bildname}";  // This is the correct relative path

    // Full path for server file checking
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;



    if (file_exists($fullPath)) {
        return $imagePath; // Return relative path for the web
    } else {
        // Return missing image path if the file doesn't exist
        return "/img/gerichte/gerichte/00_image_missing.jpg"; // Return default missing image
    }
}


/** Fetch a dish by its ID */
function db_fetch_dish_by_id($id) {
    $link = connectdb();
    $sql = "SELECT id, name, beschreibung, bildname FROM gericht WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);

    // Bind the parameter and execute
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dish = mysqli_fetch_assoc($result);
    mysqli_close($link);

    return $dish;
}

