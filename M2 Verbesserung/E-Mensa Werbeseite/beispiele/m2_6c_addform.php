<!DOCTYPE html>
<!--
* Praktikum DBWT. Autoren:
* Michael Xhristiano, Espranata, 3658655
* Adriano Ferane, Gunawan, 3659313
-->
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Addition und Multiplikation</title>
</head>
<body>
<form method="post">
    <label for="a">Wert a:</label>
    <input type="number" id="a" name="a" required><br>

    <label for="b">Wert b:</label>
    <input type="number" id="b" name="b" required><br>

    <button type="submit" name="operation" value="addieren">Addieren</button>
    <button type="submit" name="operation" value="multiplizieren">Multiplizieren</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = $_POST['a'];
    $b = $_POST['b'];
    $operation = $_POST['operation'];

    if ($operation === 'addieren') {
        $ergebnis = $a + $b;
        echo "<p>Ergebnis der Addition: $ergebnis</p>";
    } elseif ($operation === 'multiplizieren') {
        $ergebnis = $a * $b;
        echo "<p>Ergebnis der Multiplikation: $ergebnis</p>";
    }
}
?>
</body>
</html>