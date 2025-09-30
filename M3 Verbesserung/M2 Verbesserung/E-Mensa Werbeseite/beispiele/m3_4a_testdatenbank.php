<?php
phpinfo();
// Connect to the database
$link = mysqli_connect(
    "localhost",        // Hostname
    "root",             // Username
    "1489", // Password
    "emensawerbeseite"  // Database name
);

// Check the connection
if (!$link) {
    echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
    exit();
}

// SQL query
$sql = "SELECT id, name, beschreibung FROM gericht";

// Execute the query
$result = mysqli_query($link, $sql);

if (!$result) {
    echo "Fehler während der Abfrage: ", mysqli_error($link);
    exit();
}

// Generate HTML output
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datenbankergebnisse</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<h1 style="text-align:center;">Datenbankergebnisse</h1>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Beschreibung</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // Fetch results and populate table rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['beschreibung']) . '</td>';
        echo '</tr>';
    }
    ?>
    </tbody>
</table>
</body>
</html>
<?php
// Free the result set and close the connection
mysqli_free_result($result);
mysqli_close($link);
?>
