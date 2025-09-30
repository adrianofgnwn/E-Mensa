<?php
/**
 * Praktikum DBWT. Autoren:
 * Michael Xhristiano, Espranata, 3658655
 * Adriano Ferane, Gunawan, 3659313
 */
const GET_PARAM_MIN_STARS = 'search_min_stars';
const GET_PARAM_SEARCH_TEXT = 'search_text';
const GET_PARAM_SHOW_DESCRIPTION = 'show_description';
const GET_PARAM_LANGUAGE = 'language'; //ngambil value dari href
const GET_PARAM_RATING_TYPE = 'rating_type';
$translations = [
    'de' => [
        'dish' => 'Gericht',
        'description' => 'Beschreibung anzeigen:',
        'allergens' => 'Allergene',
        'price_internal' => 'Preis Intern',
        'price_external' => 'Preis Extern',
        'ratings' => 'Bewertungen',
        'search' => 'Suchen',
        'min_stars' => 'Min Sterne',
        'rating_type' => 'Typ',
        'top' => 'Top',
        'flop' => 'Flop',
        'submit' => 'Ok',
        'author' => 'Autor',
        'text' => 'Text',
        'stars' => 'Sterne',
    ],
    'en' => [
        'dish' => 'Dish',
        'description' => 'Show Description:',
        'allergens' => 'Allergens',
        'price_internal' => 'Internal Price',
        'price_external' => 'External Price',
        'ratings' => 'Ratings',
        'search' => 'Search',
        'min_stars' => 'Min Stars',
        'rating_type' => 'Type',
        'top' => 'Top',
        'flop' => 'Flop',
        'submit' => 'Submit',
        'author' => 'Author',
        'text' => 'Text',
        'stars' => 'Stars',
    ],
];


$language = isset($_GET[GET_PARAM_LANGUAGE]) ? $_GET[GET_PARAM_LANGUAGE] : 'de';
$text = $translations[$language];
$allergens = [
    11 => 'Gluten',
    12 => 'Krebstiere',
    13 => 'Eier',
    14 => 'Fisch',
    17 => 'Milch'
];

$meal = [
    'name' => 'Süßkartoffeltaschen mit Frischkäse und Kräutern gefüllt',
    'description' => 'Die Süßkartoffeln werden vorsichtig aufgeschnitten und der Frischkäse eingefüllt.',
    'price_intern' => 2.90,
    'price_extern' => 3.90,
    'allergens' => [11, 12, 13, 14, 17],
    'amount' => 42
];

$ratings = [
    [ 'text' => 'Die Kartoffel ist einfach klasse. Nur die Fischstäbchen schmecken nach Käse.', 'author' => 'Ute U.', 'stars' => 2 ],
    [ 'text' => 'Sehr gut. Immer wieder gerne', 'author' => 'Gustav G.', 'stars' => 4 ],
    [ 'text' => 'Der Klassiker für den Wochenstart. Frisch wie immer', 'author' => 'Renate R.', 'stars' => 4 ],
    [ 'text' => 'Kartoffel ist gut. Das Grüne ist mir suspekt.', 'author' => 'Marta M.', 'stars' => 3 ]
];

$showRatings = []; //gunanya buat ngesave ratings yang sesuai filter
$searchTerm = isset($_GET[GET_PARAM_SEARCH_TEXT]) ? strtolower($_GET[GET_PARAM_SEARCH_TEXT]) : '';
$minStars = isset($_GET[GET_PARAM_MIN_STARS]) ? $_GET[GET_PARAM_MIN_STARS] : '';
$ratingType = isset($_GET[GET_PARAM_RATING_TYPE]) ? $_GET[GET_PARAM_RATING_TYPE] : '';

if (!empty($searchTerm)) { // kalau searchterm ga empty (user ngetik sesuatu)
    foreach ($ratings as $rating) {
        if (strpos(strtolower($rating['text']), $searchTerm) !== false) { //checks if strpos is correct(!==false)
            $showRatings[] = $rating;
        }
    }
} else if (!empty($minStars)) {
    foreach ($ratings as $rating) {
        if ($rating['stars'] >= $minStars) {
            $showRatings[] = $rating;
        }
    }
} else if ($ratingType === 'top') {
    $maxStars = max(array_column($ratings, 'stars'));
    foreach ($ratings as $rating) {
        if ($rating['stars'] === $maxStars) {
            $showRatings[] = $rating; //shows top rating reviews
        }
    }
} else if ($ratingType === 'flop') {
    $minStars = min(array_column($ratings, 'stars'));
    foreach ($ratings as $rating) {
        if ($rating['stars'] === $minStars) {
            $showRatings[] = $rating; //shows flop reviews
        }
    }
} else {
    $showRatings = $ratings; // If no filter, show all reviews
}

function calcMeanStars(array $ratings) {
    $sum = array_sum(array_column($ratings, 'stars'));
    return $sum / count($ratings);
}

?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo $text['dish']; ?>: <?php echo $meal['name']; ?></title>
</head>
<body>
<!-- Language Switch Links -->
<a href="?language=de">Deutsch</a> | <a href="?language=en">English</a>

<!-- Dish Name -->
<h1><?php echo $text['dish']; ?>: <?php echo $meal['name']; ?></h1>

<!-- Show Description Checkbox -->
<form method="get">
    <label for="show_description"><?php echo $text['description']; ?></label>
    <input type="hidden" name="show_description" value="0">
    <input type="checkbox" id="show_description" name="show_description" value="1"
        <?php echo isset($_GET[GET_PARAM_SHOW_DESCRIPTION]) && $_GET[GET_PARAM_SHOW_DESCRIPTION] === '1' ? 'checked' : ''; ?>>
    <input type="submit" value="<?php echo $text['submit']; ?>">
</form>

<!-- Description Display -->
<?php if (isset($_GET[GET_PARAM_SHOW_DESCRIPTION]) && $_GET[GET_PARAM_SHOW_DESCRIPTION] === '0'): ?>
    <!-- Description is hidden -->
<?php else: ?>
    <p><?php echo $meal['description']; ?></p>
<?php endif; ?>

<!-- Allergens -->
<h3><?php echo $text['allergens']; ?>:</h3>
<ul>
    <?php
    foreach ($meal['allergens'] as $allergenCode) {
        if (isset($allergens[$allergenCode])) {
            echo "<li>{$allergens[$allergenCode]}</li>";
        }
    }
    ?>
</ul>

<!-- Prices -->
<p><?php echo $text['price_internal']; ?>: <?php echo number_format($meal['price_intern'], 2, ',', '') . "€"; ?></p>
<p><?php echo $text['price_external']; ?>: <?php echo number_format($meal['price_extern'], 2, ',', '') . "€"; ?></p>

<!-- Ratings Section -->
<h1><?php echo $text['ratings']; ?> (Insgesamt: <?php echo calcMeanStars($ratings); ?>)</h1>

<!-- Filter Form -->
<form method="get">
    <label for="search_text"><?php echo $text['search']; ?>:</label>
    <input id="search_text" type="text" name="search_text" value="<?php echo htmlspecialchars($searchTerm); ?>">

    <label for="min_stars"><?php echo $text['min_stars']; ?>:</label>
    <input type="number" id="min_stars" name="search_min_stars" value="<?php echo htmlspecialchars($minStars); ?>">

    <label for="rating_type"><?php echo $text['rating_type']; ?>:</label>
    <select name="rating_type">
        <option value=""><?php echo $text['ratings']; ?></option>
        <option value="top" <?php echo $ratingType === 'top' ? 'selected' : ''; ?>><?php echo $text['top']; ?></option>
        <option value="flop" <?php echo $ratingType === 'flop' ? 'selected' : ''; ?>><?php echo $text['flop']; ?></option>
    </select>

    <input type="submit" value="<?php echo $text['submit']; ?>">
</form>

<!-- Ratings Table -->
<table class="rating">
    <thead>
    <tr>
        <td><?php echo $text['author']; ?></td>
        <td><?php echo $text['text']; ?></td>
        <td><?php echo $text['stars']; ?></td>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($showRatings as $rating) {
        echo "<tr>
                      <td class='rating_author'>{$rating['author']}</td>
                      <td class='rating_text'>{$rating['text']}</td>
                      <td class='rating_stars'>{$rating['stars']}</td>
                  </tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>

