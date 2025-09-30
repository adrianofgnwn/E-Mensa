<?php
// Database connection
$mysqli = new mysqli('localhost', 'root', '1489', 'emensawerbeseite');

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get sort order from URL, default is ascending
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Query to fetch 5 dishes, sorted by name
$query = "
    SELECT g.id, g.name AS dish_name, g.preisintern, g.preisextern 
    FROM gericht g
    ORDER BY g.name $order
    LIMIT 5;
";

$result = $mysqli->query($query);
if (!$result) {
    die("Query failed: " . $mysqli->error);
}

// Fetch dishes
$dishes = $result->fetch_all(MYSQLI_ASSOC);

// Fetch allergens for displayed dishes
if (!empty($dishes)) {
    $dishIds = array_column($dishes, 'id');
    $dishIds = implode(',', $dishIds); // Prepare IDs for the query

    $allergenQuery = "
        SELECT gha.gericht_id, GROUP_CONCAT(a.code SEPARATOR ', ') AS allergens
        FROM gericht_hat_allergen gha
        LEFT JOIN allergen a ON gha.code = a.code
        WHERE gha.gericht_id IN ($dishIds)
        GROUP BY gha.gericht_id;
    ";

    $allergenResult = $mysqli->query($allergenQuery);
    if (!$allergenResult) {
        die("Allergen query failed: " . $mysqli->error);
    }

    // Map allergens to dish IDs
    $allergens = [];
    while ($row = $allergenResult->fetch_assoc()) {
        $allergens[$row['gericht_id']] = $row['allergens'];
    }
} else {
    $allergens = [];
}

// Query to fetch all unique allergens used
$usedAllergenQuery = "
    SELECT DISTINCT a.code, a.name 
    FROM allergen a
    INNER JOIN gericht_hat_allergen gha ON a.code = gha.code
";

$usedAllergenResult = $mysqli->query($usedAllergenQuery);
if (!$usedAllergenResult) {
    die("Used allergens query failed: " . $mysqli->error);
}

// Display dishes
echo "<h1>Dishes</h1>";
echo "<table border='1'>";
echo "<tr><th>Name</th><th>Internal Price</th><th>External Price</th><th>Allergens</th></tr>";
foreach ($dishes as $dish) {
    $allergenInfo = isset($allergens[$dish['id']]) ? $allergens[$dish['id']] : 'None';
    echo "<tr>
            <td>{$dish['dish_name']}</td>
            <td>{$dish['preisintern']}</td>
            <td>{$dish['preisextern']}</td>
            <td>$allergenInfo</td>
          </tr>";
}
echo "</table>";

// Add a link to toggle sort order
$nextOrder = $order === 'ASC' ? 'desc' : 'asc';
echo "<a href='?order=$nextOrder'>Sort " . ($order === 'ASC' ? "Descending" : "Ascending") . "</a>";

// Display list of used allergens
echo "<h1>Allergens Used</h1>";
echo "<ul>";
while ($row = $usedAllergenResult->fetch_assoc()) {
    echo "<li>{$row['code']} - {$row['name']}</li>";
}
echo "</ul>";

// Close the connection
$mysqli->close();
?>
