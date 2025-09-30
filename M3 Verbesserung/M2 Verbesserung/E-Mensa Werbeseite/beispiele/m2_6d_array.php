<!DOCTYPE html>
<!--
* Praktikum DBWT. Autoren:
* Michael Xhristiano, Espranata, 3658655
* Adriano Ferane, Gunawan, 3659313
-->
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Famous Meals</title>
    <style>
        ul {
            list-style-type: decimal;
            padding-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php
$famousMeals = [
    ['name' => 'Currywurst mit Pommes', 'winner' => [2001, 2003, 2007, 2010, 2020]],
    ['name' => 'Hähnchencrossies mit Paprikareis', 'winner' => [2002, 2004, 2008]],
    ['name' => 'Spaghetti Bolognese', 'winner' => [2011, 2012, 2017]],
    ['name' => 'Jägerschnitzel mit Pommes', 'winner' => [2019]]
];

echo "<h3>Famous Meals</h3><ul>";
foreach ($famousMeals as $meal) {
    echo "<li>" . $meal['name'] . "<br>" . implode(", ", array_reverse($meal['winner'])) . "</li>";
}
echo "</ul>";

// Funktion zur Ermittlung der Jahre ohne Gewinner seit 2000
function jahreOhneGewinner($meals) {
    $alleJahre = range(2000, date("Y"));
    $gewinnerJahre = [];

    foreach ($meals as $meal) {
        $gewinnerJahre = array_merge($gewinnerJahre, $meal['winner']);
    }

    $gewinnerJahre = array_unique($gewinnerJahre);
    sort($gewinnerJahre);

    return array_diff($alleJahre, $gewinnerJahre);
}

$jahreOhneGewinner = jahreOhneGewinner($famousMeals);
echo "<h4>Jahre ohne Gewinner seit 2000:</h4><p>" . implode(", ", $jahreOhneGewinner) . "</p>";
?>
</body>
</html>