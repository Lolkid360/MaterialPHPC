<?php
session_start();

if (!isset($_SESSION['username'])) {
    exit;
}

// Get the last timestamp received from the client
$lastTimestamp = isset($_GET['lastTimestamp']) ? $_GET['lastTimestamp'] : null;

// Validate and sanitize the input
if ($lastTimestamp !== null) {
    $lastTimestamp = trim($lastTimestamp); // Remove leading/trailing whitespace
    // Add further input validation or sanitation as needed
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

    // Prepare the query to fetch the new messages
    $query = "SELECT * FROM messages";
    if ($lastTimestamp) {
        $query .= " WHERE timestamp > :lastTimestamp";
    }
    $query .= " ORDER BY timestamp DESC";

    $stmt = $pdo->prepare($query);

    // Bind the last timestamp parameter, if available
    if ($lastTimestamp) {
        $stmt->bindParam(':lastTimestamp', $lastTimestamp);
    }

    // Execute the query
    $stmt->execute();

    // Fetch all the new messages as an associative array
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set the appropriate response headers
    header('Content-Type: application/json');

    // Output the messages as JSON
    echo json_encode($messages);
} catch (PDOException $e) {
    // Handle database connection or query errors
    // You can log the error or return an appropriate response
    echo json_encode(['error' => 'Failed to fetch messages']);

    // Output the error message for debugging
    error_log($e->getMessage());
    exit;
}
?>
