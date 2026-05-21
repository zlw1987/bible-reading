<?php
// Copy this file to connect.php and update values for your environment.

$connection = mysqli_connect(
    getenv('DB_HOST') ?: 'db',
    getenv('DB_USER') ?: 'bible_user',
    getenv('DB_PASSWORD') ?: 'bible_pass',
    getenv('DB_NAME') ?: 'bible'
);

if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}
