<?php
session_start();

// Check if the login form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database configuration
    $servername = 'localhost';
    $username = 'root';
    $password = 'admin';
    $dbname = 'lol';

    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement to retrieve user information
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Set the username in the session
        $_SESSION['username'] = $username;

        // Redirect to the chat page
        header("Location: chat.php");
        exit();
    } else {
        // Invalid username or password, display an error message
        $error = 'Invalid username or password. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Add viewport meta tag -->

    <link rel="stylesheet" href="styles.css"> <!-- Replace "your-styles.css" with the actual CSS file name -->

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
            background-color: #2962ff;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0039cb;
        }

        .error {
            color: #f00;
            margin-top: 5px; /* Adjust the margin for better visibility */
            margin-bottom: 10px;
            font-size: 14px; /* Increase the font size for better visibility */
        }

        .register-link {
            text-align: center;
            font-size: 14px; /* Increase the font size for better visibility */
            margin-top: -10px; /* Adjust the margin for better visibility */
        }

        .register-link a {
            color: #2962ff; /* Change the color of the "Register" link */
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
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
                <button type="submit" class="btn">Login</button>
            </div>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>