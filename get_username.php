<?php
// Replace with your database connection details
$servername = 'localhost';
$db_username = 'root';
$db_password = 'admin';
$dbname = 'lol';

// Get the user ID from the request
$user_id = $_GET['user_id'];

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the query to fetch the username
    $stmt = $pdo->prepare('SELECT username FROM users WHERE id = :user_id');

    // Bind the user ID parameter
    $stmt->bindParam(':user_id', $user_id);

    // Execute the query
    $stmt->execute();

    // Fetch the username
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = $row['username'];

    // Return the username as JSON
    echo json_encode(['username' => $username]);
} catch (PDOException $e) {
    // Handle database connection or query errors
    // You can log the error or return an appropriate response
    echo json_encode(['error' => 'Failed to retrieve username']);

    // Output the error message for debugging
    error_log($e->getMessage());
}
?>
