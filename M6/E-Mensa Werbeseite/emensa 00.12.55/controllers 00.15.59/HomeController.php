<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');

class HomeController {
    public function index(RequestData $request) {
        // Start session to access the session variable
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Log access to the homepage
        logger()->info('Homepage accessed.', ['ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);


        // Fetch data from the model
        $visitor_count = db_update_visitor_count();
        $dish_count = db_gericht_count();
        $signup_count = db_newsletter_count();
        $popular_categories = db_dishes_by_category();

        // Handle sorting functionality
        $sortOrder = isset($request->sort) && $request->sort === 'desc' ? 'DESC' : 'ASC';
        $newSortOrder = $sortOrder === 'ASC' ? 'desc' : 'asc';

        // Fetch dishes and allergens
        $dish_data = db_fetch_dishes_with_allergens($sortOrder);
        $dishes = $dish_data['dishes'];
        $unique_allergens = $dish_data['unique_allergens'];

        // Get the username from the session (set by LoginController)
        $username = isset($_SESSION['user']) ? $_SESSION['user']['name'] : null;

        // Add image URL to each dish using the new db_get_dish_image function
        foreach ($dishes as &$dish) {
            $dish['image'] = db_get_dish_image($dish['id'], $dish['bildname']);
        }

        // Pass data to the view (including username)
        return view('home', [
            'rd' => $request,
            'visitor_count' => $visitor_count,
            'signup_count' => $signup_count,
            'dish_count' => $dish_count,
            'popular_categories' => $popular_categories,
            'dishes' => $dishes,
            'newSortOrder' => $newSortOrder,
            'unique_allergens' => $unique_allergens,
            'username' => $username // Pass the username to the view
        ]);
    }

    public function debug(RequestData $request) {
        return view('debug');
    }
}
?>
