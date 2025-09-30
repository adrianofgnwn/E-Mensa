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