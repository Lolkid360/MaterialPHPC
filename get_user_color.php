<?php
// Database configuration
$servername = 'localhost';
$db_username = 'root';
$db_password = 'admin';
$dbname = 'lol';

// Get the username from the POST data
$username = $_POST['username'];

try {
  // Create a new PDO instance
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);

  // Set PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Prepare the query to retrieve the user color
  $stmt = $pdo->prepare("SELECT color FROM user_colors WHERE username = :username");

  // Bind the username parameter
  $stmt->bindParam(':username', $username);

  // Execute the query
  $stmt->execute();

  // Fetch the color from the query result
  $color = $stmt->fetchColumn();

  // Return the color as JSON response
  echo json_encode(['color' => $color]);
} catch (PDOException $e) {
  // Handle database connection or query errors
  // You can log the error or return an appropriate response
  echo json_encode(['error' => 'Failed to retrieve user color']);

  // Output the error message for debugging
  error_log($e->getMessage());
}
?>
