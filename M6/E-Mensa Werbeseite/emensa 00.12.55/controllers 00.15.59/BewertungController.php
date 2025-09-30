<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../models/bewertung.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');

class BewertungController {
    // Show the rating form
    public function showRatingForm($request) {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is logged in
        if (!isset($_SESSION['user'])) {
            // Retrieve gerichtid from the RequestData object
            $gerichtId = $request->query['gerichtid'] ?? '';
            $_SESSION['redirect_after_login'] = '/bewertung?gerichtid=' . $gerichtId;
            return $this->redirect('/anmeldung'); // Redirect to login
        }

        // Retrieve gerichtid from the RequestData object
        $gerichtId = $request->query['gerichtid'] ?? null;
        if (!$gerichtId) {
            return $this->redirect('/'); // Redirect to home if no gerichtid
        }

        // Fetch Gericht details
        $gericht = db_fetch_dish_by_id($gerichtId);
        if (!$gericht) {
            $_SESSION['error'] = 'Dish not found.';
            return $this->redirect('/');
        }

        // Add dish image
        $gericht['image_url'] = db_get_dish_image($gericht['id'], $gericht['bildname']);

        // Render the rating view
        return view('bewertung', [
            'gericht' => $gericht,
            'user' => $_SESSION['user']
        ]);
    }

    public function submitRating() {
        // Ensure the request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate inputs from $_POST
            $userId = $_SESSION['user']['id'];
            $gerichtId = $_POST['gericht_id'] ?? null;
            $bemerkung = $_POST['bemerkung'] ?? '';
            $sterne = $_POST['sterne'] ?? null;

            if (!$gerichtId || !$bemerkung || !$sterne || strlen($bemerkung) < 5) {
                $_SESSION['error'] = 'Bitte gültige Eingaben machen.';
                $this->redirect('/bewertung?gerichtid=' . $gerichtId);
            }

            // Add the rating to the database
            db_add_bewertung($userId, $gerichtId, $bemerkung, $sterne);
            $_SESSION['success'] = 'Bewertung erfolgreich hinzugefügt.';
            $this->redirect('/bewertungen');
        }

        $this->redirect('/');
    }


    // Show all ratings
    public function showAllRatings() {
        $bewertungen = db_fetch_latest_bewertungen(30);
        return view('bewertungen', ['bewertungen' => $bewertungen]);
    }


    // Show user's ratings
    public function showUserRatings() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/anmeldung');
        }

        $userId = $_SESSION['user']['id'];
        $bewertungen = db_fetch_user_bewertungen($userId);

        // Include image paths for each dish
        foreach ($bewertungen as &$bewertung) {
            $bewertung['gericht_image'] = db_get_dish_image($bewertung['gericht_id'], $bewertung['bildname']);
        }

        return view('meinebewertungen', ['bewertungen' => $bewertungen]);
    }


    // Delete a rating
    public function deleteRating() {
        // Check if logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/anmeldung');
        }

        // Debugging: Check if it's a POST request and 'delete' exists
        // prevents something like /bewertungloeschen?id=123
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);  // Check if the form's data is being received properly

            if (isset($_POST['delete'])) {
                $bewertungId = $_POST['delete'];
                $userId = $_SESSION['user']['id'];

                if ($bewertungId && db_delete_bewertung($bewertungId, $userId)) {
                    $_SESSION['success'] = 'Bewertung gelöscht.';
                } else {
                    $_SESSION['error'] = 'Fehler beim Löschen der Bewertung.';
                }
            } else {
                $_SESSION['error'] = 'No delete parameter provided.';
            }
        }

        $this->redirect('/meinebewertungen');
    }


    public function highlightReview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['admin']) {
            $bewertungId = $_POST['bewertung_id'];
            db_update_bewertung_hervorgehoben($bewertungId, true);
            $_SESSION['success'] = 'Bewertung hervorgehoben.';
            $this->redirect('/bewertungen');
        }
    }

    public function unHighlightReview() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['admin']) {
            $bewertungId = $_POST['bewertung_id'];
            db_update_bewertung_hervorgehoben($bewertungId, false);
            $_SESSION['success'] = 'Hervorhebung entfernt.';
            $this->redirect('/bewertungen');
        }
    }

    private function redirect($url) {
        header("Location: $url");
        exit();
    }
}
