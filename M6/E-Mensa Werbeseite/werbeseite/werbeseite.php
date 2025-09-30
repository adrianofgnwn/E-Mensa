<?php
// Database connection setup
$servername = "localhost";
$username = "root"; // Adjust with your database credentials
$password = "root";  // Adjust with your database credentials
$dbname = "emensawerbeseite";

// Create a connection (ini yang membuat koneksi pada php yang sebenarnya.)
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update visitor count in the database
$updateVisitorQuery = "INSERT INTO visitor_counter (id, visit_count) VALUES (1, 1) 
                       ON DUPLICATE KEY 
                       UPDATE visit_count = visit_count + 1";

if (!$conn->query($updateVisitorQuery)) {
    echo "Error updating visitor count: " . $conn->error;
}

// Fetch the updated visitor count
$visitorQuery = "SELECT visit_count FROM visitor_counter LIMIT 1"; // limit 1 bikin biar yg  muncul yg index pertama doang karena kita update angka disitu
$visitorResult = $conn->query($visitorQuery);

if ($visitorResult && $visitorResult->num_rows > 0) { // did the query worked and is there data on the result?
    $visitorRow = $visitorResult->fetch_assoc();
    $visitor_count = $visitorRow['visit_count'];
} else {
    echo "Error fetching visitor count: " . $conn->error;
}

// 2) Count the number of dishes from the database
$dish_query = "SELECT COUNT(*) AS anzahl_gerichte FROM gericht";
$dish_result = $conn->query($dish_query);
$dish_row = $dish_result->fetch_assoc(); // ini buat dapetin jumlah row yang ada
$dish_count = $dish_row['anzahl_gerichte'];  // ini buat jadiin angka dengan memasukan semua ke array


//BAGIAN NEWSLETTER ANMELDUNG
// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['namen'];
    $email = $_POST['emails'];
    $sprache = $_POST['sprache'];
    $datenschutz = isset($_POST['datenschutz']) ? 1 : 0;  // 1 for accepted, 0 for not accepted

    // Insert data into the newsletter table
    $stmt = $conn->prepare("INSERT INTO newsletter (name, email, sprache, datenschutz) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $sprache, $datenschutz);

    // Execute the query
    if ($stmt->execute()) {
        echo "Vielen Dank für Ihre Anmeldung zum Newsletter!";
    } else {
        echo "Fehler bei der Anmeldung. Bitte versuchen Sie es später noch einmal.";
    }
    $stmt->close();
}

// 2) Count the number of newsletter sign-ups from the database
$signup_query = "SELECT COUNT(*) AS anzahl_anmeldungen FROM newsletter";
$signup_result = $conn->query($signup_query);
$signup_row = $signup_result->fetch_assoc();
$signup_count = $signup_row['anzahl_anmeldungen'];



//Count dishes per category (AUFGABE 6 part 5)
$category_query = "SELECT kategorie.name AS kategorie, COUNT(*) AS anzahl 
                   FROM gericht JOIN gericht_hat_kategorie ghk 
                       ON gericht.id = ghk.gericht_id
                   JOIN kategorie ON ghk.kategorie_id = kategorie.id
                   GROUP BY kategorie.name";
$category_result = $conn->query($category_query);

// Filter popular categories (more than 2 dishes)
$popular_categories = [];
while ($row = $category_result->fetch_assoc()) {
    if ($row['anzahl'] > 2) {
        $popular_categories[$row['kategorie']] = $row['anzahl'];
    }
}


// AUFGABE 5
// Determine sorting order from the URL for the dish list
$sortOrder = (isset($_GET['sort']) && $_GET['sort'] == 'desc') ? 'DESC' : 'ASC';
$newSortOrder = ($sortOrder == 'ASC') ? 'desc' : 'asc';

// Fetch and sort dishes, limit to 5
$dish_list_query = "SELECT id, name, preisintern, preisextern FROM gericht ORDER BY name $sortOrder LIMIT 5";
$dish_list_result = $conn->query($dish_list_query);

// Prepare dishes and allergens data
$dishes = []; //empty list to store the dishes
$allergens_by_dish = []; // New array to track allergens by dish
$allergens_used = [];

if ($dish_list_result->num_rows > 0) {
    while ($dish = $dish_list_result->fetch_assoc()) {
        $dish_id = $dish['id'];

        // Get allergens for each dish
        $allergen_query = "SELECT code FROM gericht_hat_allergen WHERE gericht_id = $dish_id";
        $allergen_result = $conn->query($allergen_query);

        $allergens = [];// Create an empty list for allergens.
        while ($allergen_row = $allergen_result->fetch_assoc()) {
            $allergens[] = $allergen_row['code']; // Add each allergen code to the list.
            $allergens_used[$allergen_row['code']] = true; // Track all allergens used.
        }

        $dish['allergens'] = $allergens; // Attach allergens to the dish.
        $allergens_by_dish[$dish_id] = $allergens; // Add allergens to the array
        $dishes[] = $dish;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>E-mensa</title>
    <style>
        a {
            color: black;
            text-decoration: none;
        }
        a:hover {
            color: blue;
        }
        body {
            font-family: "Comic Sans MS";
        }
        header {
            display: grid;
            grid-template-columns: 20% 60%;
            border-bottom: 2px solid black;
        }
        main {
            margin-left: 20%;
            margin-right: 20%;
        }
        img {
            max-width: 100%;
        }
        #menupic {
            text-align: center;
            margin-top: 10px;
        }
        header > ul {
            font-size: 20px;
            display: grid;
            align-content: space-evenly;
            border: solid black;
            grid-template-columns: repeat(5, auto);
            column-gap: 10px;
            list-style-type: none;
        }
        h1 {
            font-weight: bold;
            justify-items: left;
        }
        #text {
            display: grid;
            border: solid;
            grid-template-rows: repeat(2, auto);
            padding: 5px;
            text-align: justify;
        }
        table {
            width: 100%;
        }
        table, th, td {
            border: 1px solid;
            border-collapse: collapse;
        }
        .Preis {
            text-align: center;
        }
        #inzahlen {
            font-weight: bold;
            font-size: 25px;
            list-style-type: none;
            display: grid;
            grid-template-columns: 30px auto 30px auto 30px auto;
        }
        #form {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 5px;
        }
        #formname, #formaddress, #formlanguage {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            gap: 5px;
        }
        #button {
            font-weight: bold;
            margin-left: 50%;
        }
        #mid {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #Besuch {
            text-align: center;
        }
        footer {
            border-top: solid;
        }
        footer ul {
            display: grid;
            justify-items: center;
            justify-content: center;
            list-style-type: none;
            grid-template-columns: repeat(4, auto);
        }
        footer ul > li + li::before {
            content: "|";
            margin: 10px;
        }
    </style>
</head>
<body>
<header>
    <img src="E-Mensa%20Logo.png" alt="E-Mensa Logo">
    <ul>
        <li><a href="#Ankündigung">Ankündigung</a></li>
        <li><a href="#Speisen">Speisen</a></li>
        <li><a href="#Zahlen">Zahlen</a></li>
        <li><a href="#Kontakt">Kontakt</a></li>
        <li><a href="#Wichtig">Wichtig für uns</a></li>
    </ul>
</header>
<main>
    <div id="menupic">
        <img src="RINDFLEISCH.jpg" alt="Rindfleisch Gerichte">
    </div>
    <h1 id="Ankündigung">Bald gibt es Essen auch online;)</h1>
    <div id="text">
        <?php echo "Test"; ?> <!-- Test PHP is running -->
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
    </div>

    <h1 id="Speisen">Köstlichkeiten, die Sie erwarten</h1>
    <table>
        <tr>
            <th>Gericht</th>
            <th>Preis intern</th>
            <th>Preis extern</th>
            <th>ALLERGENS</th>
        </tr>

        <?php
        // Loop through each dish in the $gerichte array to display its details
        foreach ($dishes as $dish) {
            echo "<tr>";
            echo "<td>";
            // Display image (if exists) and name
            if (!empty($dish['image'])) {
                echo "<img src='{$dish['image']}' alt='{$dish['name']}' style='width:100px;'><br>";
            }
            echo "{$dish['name']}</td>";
            echo "<td class='Preis'>{$dish['preisintern']} &euro;</td>";
            echo "<td class='Preis'>{$dish['preisextern']} &euro;</td>";

            // Display allergens for each dish
            echo "<td>";
            if (!empty($dish['allergens'])) {
                echo implode(", ", $dish['allergens']);  // Show allergens as a comma-separated list
            } else {
                echo "Keine Allergene!!!";  // No allergens found
            }
            echo "</td>";
            echo "</tr>";

        }
        echo "<a href='?sort=$newSortOrder'>Sortiere nach Name (" . strtoupper($newSortOrder) . ")</a>";
        ?>
    </table>

    <!-- Display Allergen List -->
    <h2>Liste der verwendeten Allergene:</h2>
    <ul>
        <?php
        // Display a unique list of allergens
        $unique_allergens = array_unique(array_merge(...array_values($allergens_by_dish)));
        // array values untuk mengambil beberapa array , array merge untuk menggabungkan array menjadi 1 dan array unique membuat setiap value array unique
        foreach ($unique_allergens as $allergen) {
            echo "<li>{$allergen}</li>";
        }
        ?>
    </ul>

        <h1>Kategorien mit mehr als 2 Gerichten</h1>
        <table>
            <tr>
                <th>Kategorie</th>
                <th>Anzahl der Gerichte</th>
            </tr>
            <?php foreach ($popular_categories as $category => $count): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category); ?></td>
                    <td><?php echo $count; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

    <h1 id="Zahlen">E-mensa in Zahlen</h1>
    <ul id="inzahlen">
        <li><?php echo $visitor_count; ?></li>
        <li>Besuche</li>
        <li><?php echo $signup_count; ?></li>
        <li>Anmeldungen zum Newsletter</li>
        <li><?php echo $dish_count; ?></li>
        <li>Speisen</li>
    </ul>

    <h1 id="Kontakt">Interesse geweckt? Wir informieren Sie!</h1>
    <form action="newsletter_signup.php" method="post">
        <div id="form">
            <div id="formname">
                <label for="namen">Ihr Name</label>
                <input type="text" id="namen" name="namen" placeholder="Vorname" required>
            </div>
            <div id="formaddress">
                <label for="email">Ihre E-Mail</label>
                <input type="email" id="email" name="emails" required>
            </div>
            <div id="formlanguage">
                <label for="newsletter">Newsletter bitte in</label>
                <select id="newsletter" name="sprache">
                    <option value="deutsch" selected>Deutsch</option>
                    <option value="englisch">Englisch</option>
                </select><br>
            </div>
        </div>
        <input type="checkbox" required id="checkbox" name="datenschutz">
        <label for="checkbox">Den Datenschutzbestimmungen stimme ich zu</label>
        <input type="submit" value="Zum Newsletter anmelden" id="button">
    </form>



    <h1 id="Wichtig">Das ist uns Wichtig</h1>
    <div id="mid">
        <ul>
            <li>Beste frische saisonale Zutaten</li>
            <li>Ausgewogene abwechslungsreiche Gerichte</li>
            <li>Sauberkeit</li>
        </ul>
    </div>

    <h1 id="Besuch">Wir freuen uns auf Ihren Besuch!</h1>

</main>
<footer>
    <ul>
        <li>(c) E-Mensa GmbH</li>
        <li>Adriano F. Gunawan & Michael X. Espranata</li>
        <li><a href="index.html">Impressum</a></li>
        <a href="../werbeseite/wunschgericht.php"> Wunschgericht melden</a>
    </ul>
</footer>
</body>
</html>
