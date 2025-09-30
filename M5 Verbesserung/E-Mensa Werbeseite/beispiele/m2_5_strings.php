<?php
/**
 * Praktikum DBWT. Autoren:
 * Michael Xhristiano, Espranata, 3658655
 * Adriano Ferane, Gunawan, 3659313
 */

// str_replace
echo "<h4>str_replace</h4>";
// Ersetzt ein Teilstück eines Strings mit einem neuen Text.
$text = "Hallo, Welt!";
$neuerText = str_replace("Welt", "PHP", $text);
echo "Original: $text<br>";
echo "Ersetzt: $neuerText<br>";

// str_repeat
echo "<h4>str_repeat</h4>";
// Wiederholt einen String eine bestimmte Anzahl von Malen.
$wort = "PHP ";
$wiederholt = str_repeat($wort, 3);
echo "Wort: $wort<br>";
echo "Wiederholt: $wiederholt<br>";

// substr
echo "<h4>substr</h4>";
// Gibt einen Teil des Strings zurück, basierend auf der Startposition und optionaler Länge.
$text = "Hallo, PHP!";
$teilText = substr($text, 7, 3);
echo "Original: $text<br>";
echo "Extrahiert: $teilText<br>";

// trim / ltrim / rtrim
echo "<h4>trim, ltrim und rtrim</h4>";
// Entfernt Leerzeichen (oder andere Zeichen) von beiden Seiten, nur links oder nur rechts.
$textMitLeerzeichen = "   Hallo, PHP!   ";
echo "Original mit Leerzeichen: '$textMitLeerzeichen'<br>";
echo "Trim: '" . trim($textMitLeerzeichen) . "'<br>";
echo "ltrim: '" . ltrim($textMitLeerzeichen) . "'<br>";
echo "rtrim: '" . rtrim($textMitLeerzeichen) . "'<br>";

// String-Konkatenation
echo "<h4>String-Konkatenation</h4>";
// Verbindet zwei Strings mit dem '.' Operator.
$teil1 = "Hallo, ";
$teil2 = "PHP!";
$zusammen = $teil1 . $teil2; // Verbindet die beiden Zeichenketten
echo "Teil 1: $teil1<br>";
echo "Teil 2: $teil2<br>";
echo "Zusammen: $zusammen<br>";


