<?php
$salt = "1234"; // Choose your salt (at least 4 characters)
$password = "yourpassword"; // Replace with your chosen password
$hashedPassword = sha1($salt . $password); // Combine salt and password, then hash

echo "Generated Hash: " . $hashedPassword;
