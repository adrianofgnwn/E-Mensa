<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/kategorie.php');

class ExampleController
{
    // Aufgabe 7a: Handle query parameter and pass it to the view
    public function m4_7a_queryparameter(RequestData $rd) {
        // Extract the 'name' query parameter
        $name = $rd->query['name'] ?? 'unknown';

        // Pass the 'name' parameter to the view
        return view('examples.m4_7a_queryparameter', [
            'name' => $name
        ]);
    }

    public function m4_7b_kategorie(RequestData $rd) {
        // Use the procedural function to fetch all categories
        $categories = db_kategorie_select_all();

        // Pass the categories to the view
        return view('examples.m4_7b_kategorie', [
            'categories' => $categories
        ]);
    }

    public function m4_7c_gerichte(RequestData $rd) {
        try {
            // Connect to the database
            $link = connectdb();

            // Custom query to filter dishes with preisintern > 2 and sort by name descending
            $sql = 'SELECT id, name, preisintern FROM gericht WHERE preisintern > 2 ORDER BY name DESC';
            $result = mysqli_query($link, $sql);

            // Fetch all results as an associative array
            $dishes = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Close the connection
            mysqli_close($link);
        } catch (Exception $ex) {
            $dishes = []; // If there's an error, return an empty array
        }

        // Pass the filtered and sorted dishes to the view
        return view('examples.m4_7c_gerichte', [
            'dishes' => $dishes
        ]);
    }

    public function m4_7d_pages(RequestData $rd) {
        // Get the query parameter 'no' (default to 1 if not provided)
        $pageNumber = $rd->query['no'] ?? 1;

        // Determine which view to load
        $view = 'examples.pages.m4_7d_page_1'; // Default view
        $title = 'Page 1'; // Default title

        if ($pageNumber == 2) {
            $view = 'examples.pages.m4_7d_page_2';
            $title = 'Page 2';
        }

        // Pass the title and load the view
        return view($view, [
            'title' => $title
        ]);
    }
}
