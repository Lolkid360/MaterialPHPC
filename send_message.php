<?php
session_start();

if (!isset($_SESSION['username'])) {
    exit;
}

// Get the message from the request
$message = isset($_POST['message']) ? $_POST['message'] : '';

// Validate and sanitize the input
$message = trim($message); // Remove leading/trailing whitespace
// Add further input validation or sanitation as needed

if ($message === '') {
    echo json_encode(['error' => 'Empty message']);
    exit;
}

// Database configuration
$servername = 'localhost';
$db_username = 'root';
$db_password = 'admin';
$dbname = 'lol';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the query to insert the new message
    $stmt = $pdo->prepare("INSERT INTO messages (sender, message, timestamp) VALUES (:sender, :message, :timestamp)");

    // Bind parameters
    $stmt->bindParam(':sender', $_SESSION['username']);
    $stmt->bindParam(':message', $message);
    $stmt->bindValue(':timestamp', date('Y-m-d H:i:s'));

    // Execute the query
    $stmt->execute();

    // Construct the new message
    $newMessage = [
        'sender' => $_SESSION['username'],
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Set the appropriate response headers
    header('Content-Type: application/json');

    // Return the new message as JSON
    echo json_encode($newMessage);
} catch (PDOException $e) {
    // Handle database connection or query errors
    // You can log the error or return an appropriate response
    echo json_encode(['error' => 'Failed to send message']);

    // Output the error message for debugging
    error_log($e->getMessage());
    exit;
}
?>
