<?php
$logFile = 'accesslog.txt';
$date = date("Y-m-d H:i:s"); //Year-Month-Day Hour:Minute
$clientIP = $_SERVER['REMOTE_ADDR']; //gets user IP
$browserInfo = $_SERVER['HTTP_USER_AGENT']; //gets user information
$logEntry = "$date | IP: $clientIP | Browser: $browserInfo\n"; // Creates a single line with all log information (date, IP, and browser), formatted neatly with separators.
file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
echo "Zugriff wurde geloggt.";

//Writes the $logEntry to accesslog.txt.
//FILE_APPEND appends the new entry at the end of the file.
//LOCK_EX prevents other processes from writing to the file at the same time.