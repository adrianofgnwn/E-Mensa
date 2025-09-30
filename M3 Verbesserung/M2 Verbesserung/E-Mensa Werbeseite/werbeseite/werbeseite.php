<?php
// Include the external file that holds the dishes array
include 'gerichte.php';

// 1) Persistent visitor counter
$visitor_file = 'visitor_count.txt';

// Check if the file exists and read the current count
if (file_exists($visitor_file)) {
    $visitor_count = (int)file_get_contents($visitor_file);
} else {
    $visitor_count = 0;
}

// Increment the visitor count
$visitor_count++;

// Save the updated count back to the file
file_put_contents($visitor_file, $visitor_count);

// 2) Count the number of dishes
$dish_count = count($gerichte);

// 3) Read the number of newsletter signups
$signup_count_file = 'newsletter_count.txt';
if (file_exists($signup_count_file)) {
    $signup_count = (int)file_get_contents($signup_count_file);
} else {
    $signup_count = 0;
}
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
        </tr>
        <?php
        // Loop through each dish in the $gerichte array to display its details
        foreach ($gerichte as $gericht) {
            echo "<tr>";
            echo "<td>";
            // Display image (if exists) and name
            if (!empty($gericht['image'])) {
                echo "<img src='{$gericht['image']}' alt='{$gericht['name']}' style='width:100px;'><br>";
            }
            echo "{$gericht['name']}</td>";
            echo "<td class='Preis'>{$gericht['preis_intern']} &euro;</td>";
            echo "<td class='Preis'>{$gericht['preis_extern']} &euro;</td>";
            echo "</tr>";
        }
        ?>
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
    </ul>
</footer>
</body>
</html>
