<?php
// Check if the 'suche' parameter is set
if (isset($_GET['suche'])) {
    $searchWord = trim($_GET['suche']);
} else {
    $searchWord = '';
}

// Initialize variable to store the translation
$foundTranslation = '';

// Check if 'suche' parameter was provided
if ($searchWord != '') {
    // Load the file "en.txt" into an array (each line as an element)
    $translations = file('en.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


    foreach ($translations as $line) { // Loop through each line of the file

        $line = trim($line); // Remove any extra spaces from the beginning and end of the line

        // Find the position of the semicolon (;) delimiter
        $delimiterPos = strpos($line, ';');

        // Check if a semicolon exists and that it's not at the start or end
        if ($delimiterPos !== false && $delimiterPos > 0 && $delimiterPos < strlen($line) - 1) {
            // Extract the English word (before the semicolon)
            $english = substr($line, 0, $delimiterPos);

            // Extract the translation (after the semicolon)
            $translation = substr($line, $delimiterPos + 1);

            // Compare the English word to the search word (case-sensitive)
            if (trim($english) == $searchWord) {
                $foundTranslation = trim($translation);
                break; // Exit the loop once we find the word
            }
        }
    }

    // If no translation was found
    if ($foundTranslation == '') {
        $message = "The word \"$searchWord\" was not found in the dictionary.";
    } else {
        $message = "Translation: <strong>$foundTranslation</strong>";
    }
} else {
    $message = "Please enter a word to search for.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Word Translator</title>
</head>
<body>

<h1>Word Translator</h1>

<!-- Form for entering the word to search -->
<form method="get">
    <label for="suche">Enter word to translate:</label>
    <input type="text" id="suche" name="suche" value="<?php echo htmlspecialchars($searchWord); ?>">
    <input type="submit" value="Search">
</form>

<p><?php echo $message; ?></p>

</body>
</html>
