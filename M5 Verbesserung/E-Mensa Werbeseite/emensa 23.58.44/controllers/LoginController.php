<?php

// Assuming a function db_fetch_user_by_email() that fetches user data by email from the database
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/user.php');

class LoginController {
    // Method to show the login form
    public function showLoginForm(RequestData $request) {
        // Check if there's a login error in the session
        $loginError = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : null;

        // Clear the session variable after reading it/clear session error message after showing
        unset($_SESSION['login_error']);

        // Pass the error message to the view
        return view('login', [ // This will load the login.blade.php view
            'rd' => $request,
            'loginError' => $loginError
        ]);
    }

    // Define the salt for password hashing
    private $salt = "1234";

    // Tp redirect user to a url
    private function redirect($url) {
        header("Location: $url");
        exit();
    }

    // Method to handle login form display and verification
    public function verifylogin(RequestData $request) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { //Use POST (More Secure)
            //Retrieve data
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            //Fetch the user by email
            $user = db_fetch_user_by_email($email);

            //Check if user exists
            if (!$user) {
                logger()->warning('Failed login attempt.', ['username' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
                $_SESSION['login_error'] = 'User not found. Please check your email address.';
                return $this->redirect('/anmeldung');
            }

            //Handle empty password
            if (empty($password)) {
                // Log failed login attempt due to empty password
                logger()->warning('Failed login attempt - empty password.', ['username' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
                $_SESSION['login_error'] = 'Password is required.';
                return $this->redirect('/anmeldung');
            }

            $hashedPassword = sha1($this->salt . $password);
            //Check password
            if ($user['passwort'] !== $hashedPassword) {
                // Log failed login attempt due to incorrect password
                logger()->warning('Failed login attempt - incorrect password.', [
                    'username' => $email,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);

                // Begin transaction for logging failed login
                $link = connectdb(); // Establish database connection
                mysqli_begin_transaction($link);

                try {
                    // Increment error count
                    db_increment_error_count($user['id'], $link);

                    // Update last failed login timestamp
                    db_update_last_failed_login($user['id'], $link);

                    // Commit transaction if both operations succeed
                    mysqli_commit($link);
                } catch (Exception $e) {
                    // Roll back transaction on any failure
                    mysqli_rollback($link);

                    // Log the error for debugging
                    logger()->error('Failed to log login failure.', [
                        'user' => $user['id'],
                        'error' => $e->getMessage()
                    ]);
                }

                // Set session error message and redirect to login page
                $_SESSION['login_error'] = 'Incorrect password. Please try again.';
                return $this->redirect('/anmeldung');
            }

            // Log successful login
            logger()->info('User logged in.', ['user' => $user['name'], 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);

            //Start Transaction
            //Commits or rolls back the transaction depending on success
            //Both last login and login count need to succeed otherwise rollback
            $link = connectdb();
            mysqli_begin_transaction($link);

            // Increment login count and update last login
            if (db_increment_login_count($user['id'], $link) && db_update_last_login($user['id'], $link)) {
                mysqli_commit($link);
                //Stores the user data
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'admin' => $user['admin']
                ];

                $_SESSION['login_success'] = 'Login successful! Welcome back, ' . htmlspecialchars($user['name']) . '.';
                return $this->redirect('/');
            } else {
                mysqli_rollback($link);
                $_SESSION['login_error'] = 'An error occurred during the login process. Please try again.';
                return $this->redirect('/anmeldung');
            }

            mysqli_close($link);
        }

        return view('login', ['rd' => $request]);
    }

    public function logout() {
        // Log logout
        if (isset($_SESSION['user'])) {
            logger()->info('User logged out.', ['user' => $_SESSION['user']['name'], 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
        }

        // Clear the session data for the user
        unset($_SESSION['user']);
        return $this->redirect('/loggedout');
    }

    public function loggedOut() {
        return view('loggedout');  // Return the loggedout.blade.php view
    }

}
?>
