<?php
$salt = "1234"; // Choose your salt (at least 4 characters)
$password = "11234"; // Replace with your chosen password
$hashedPassword = sha1($salt . $password); // Combine salt and password, then hash

echo "Generated Hash: " . $hashedPassword;

// Insert into the database (you can use this in your SQL tool)
echo "\n\nSQL to insert the admin user:\n";
?>