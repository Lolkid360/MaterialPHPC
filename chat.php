<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Database configuration
$servername = 'localhost';
$db_username = 'root';
$db_password = 'admin';
$dbname = 'lol';

// Function to retrieve the logged-in username
function getLoggedInUsername() {
    // Replace this with your code to retrieve the logged-in username
    return $_SESSION['username'];
}

// Function to retrieve the user color from the database
function getUserColorFromDatabase($username) {
    global $servername, $db_username, $db_password, $dbname; // Add this line

    // Implement your database query here to fetch the user color based on the username
    // Use the $servername, $db_username, $db_password, $dbname variables for the database connection

    // Example query to fetch user color using PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
    $stmt = $pdo->prepare("SELECT color FROM user_colors WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the color from the query result
    $color = $stmt->fetchColumn();

    return $color;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LanChat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* CSS styles remain the same */
        #username {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
    margin-top: 4px;
    }  
    </style>
</head>
<body>
    <h1>Welcome to the Chat!</h1>
    <div id="chat-container">
        <div id="messages">
            <!-- Existing messages will be displayed here -->
        </div>
        <form id="chat-form" action="send_message.php" method="post">
            <input type="text" id="message-input" name="message" placeholder="Type a message..." required>
            <button type="submit">Send</button>
        </form>
    </div>
    <span id="username"></span> <!-- Display the username here -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="script.js"></script>
    <script>
        // JavaScript code remains the same

        // Apply the logged-in user's color to the username element
        var loggedInUsername = "<?php echo getLoggedInUsername(); ?>";
        var loggedInUserColor = "<?php echo getUserColorFromDatabase(getLoggedInUsername()); ?>";
        $('#username').css('color', loggedInUserColor);
        $('#username').text(loggedInUsername); // Set the text to the logged-in username
    </script>
</body>
</html>
