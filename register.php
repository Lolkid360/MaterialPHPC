<?php
session_start();
// Check if the registration form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database configuration
    $servername = 'localhost';
    $db_username = 'root';
    $db_password = 'admin';
    $dbname = 'lol';

    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);

    // Get the registration form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username is already taken
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Username is already taken, display an error message
        $error = 'Username is already taken. Please choose a different username.';
    } else {
        // Create a new user record in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $insertStmt->execute(['username' => $username, 'password' => $hashedPassword]);

        // Assign a color to the new user
        $color = generateRandomColor();
        $colorStmt = $pdo->prepare("INSERT INTO user_colors (username, color) VALUES (:username, :color)");
        $colorStmt->execute(['username' => $username, 'color' => $color]);

        // Redirect to the login page
        header("Location: login.php");
        exit();
    }
}

// Function to retrieve the logged-in username
function getLoggedInUsername() {
    // Replace this with your code to retrieve the logged-in username
    return $_SESSION['username'];
}

// Function to retrieve the user color from the database
function getUserColorFromDatabase($username) {
    // Database configuration
    $servername = 'localhost';
    $db_username = 'root';
    $db_password = 'admin';
    $dbname = 'lol';

    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);

    // Check if the 'color' column exists in the 'user_colors' table
    $stmt = $pdo->query("SHOW COLUMNS FROM user_colors LIKE 'color'");
    $columnExists = $stmt->rowCount() > 0;

    if ($columnExists) {
        // Implement your code to fetch the user color from the 'user_colors' table
        $stmt = $pdo->prepare("SELECT color FROM user_colors WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $color = $stmt->fetchColumn();
    } else {
        // Column does not exist, provide a default color
        $color = '#000000'; // Set a default color here
    }

    return $color;
}

// Function to generate a random color
function generateRandomColor() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Add viewport meta tag -->

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="your-styles.css"> <!-- Replace "your-styles.css" with the actual CSS file name -->

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #303030;
            color: #ffffff;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            width: 90%;
            max-width: 400px; /* Add max-width for smaller screens */
            padding: 20px;
            background-color: #424242;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 10px; /* Reduce excessive spacing */
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px; /* Adjust the padding for better spacing */
            border: 1px solid #ccc;
            border-radius: 8px; /* Increase the border-radius for a smoother appearance */
            font-size: 14px; /* Decrease the font size for better fit on mobile */
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .error {
            color: #f00;
            margin-top: 5px; /* Adjust the margin for better visibility */
            margin-bottom: 10px;
            font-size: 14px; /* Increase the font size for better visibility */
        }

        .login-link {
            text-align: center;
            font-size: 14px; /* Increase the font size for better visibility */
            margin-top: -10px; /* Adjust the margin for better visibility */
        }

        .login-link a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
