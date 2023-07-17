<?php
// Database configuration
$servername = 'localhost';
$db_username = 'root';
$db_password = 'admin';
$dbname = 'lol';

// Get the user color from the request
$color = $_POST['color'];
$username = $_SESSION['username'];

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the query to update the user color
    $stmt = $pdo->prepare("UPDATE users SET color = :color WHERE username = :username");

    // Bind parameters
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':username', $username);

    // Execute the query
    $stmt->execute();

    // Send a success response
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Handle database connection or query errors
    // You can log the error or return an appropriate response
    echo json_encode(['error' => 'Failed to store user color']);

    // Output the error message for debugging
    error_log($e->getMessage());
}
?>