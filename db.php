<?php
$host = 'localhost';
$dbname = 'student_grading';
$username = 'root';
$password = ''; // Default XAMPP MySQL password is usually blank.

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    exit('Database connection failed. Check that MySQL is running and database.sql has been imported.');
}
