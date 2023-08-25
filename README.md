# Yet another Simple PHPChat
A simple PHPChat with a Martial UI design in mind

1. Get a host that supports PHP
2. Get a SQL Datatebase (There is free ones or you can host one)
3. Confige the file to use your SQL Database
4. Boom you got a chat simple for all you chating needs


## This chat is insecure and should only be use in a private environment


Instructions to get SQL Server up and running with this:

1. **Create the Database and Tables:**

```sql
-- Create the database
CREATE DATABASE (name);

-- Use the database
USE (name);

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Create the messages table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create the user_colors table
CREATE TABLE user_colors (
    username VARCHAR(50) PRIMARY KEY,
    color VARCHAR(7) NOT NULL
);
```

2. **User Registration:**

When a user registers, you'll insert their information into the `users` table and assign a default color to them in the `user_colors` table.

```sql
-- Insert user registration data
INSERT INTO users (username, password) VALUES ('example_user', 'hashed_password');

-- Assign a default color to the user
INSERT INTO user_colors (username, color) VALUES ('example_user', '#000000');
```

3. **User Login:**

When a user logs in, you'll validate their credentials against the `users` table.

```sql
-- Validate user credentials
SELECT * FROM users WHERE username = 'example_user' AND password = 'hashed_password';
```

4. **Storing User Color:**

When a user's color is stored, you'll update the `user_colors` table.

```sql
-- Update user color
UPDATE user_colors SET color = '#FF5733' WHERE username = 'example_user';
```

5. **Sending Messages:**

When a user sends a message, you'll insert the message into the `messages` table.

```sql
-- Insert a new message
INSERT INTO messages (sender, message) VALUES ('example_user', 'Hello, world!');
```

6. **Fetching Messages:**

To fetch messages, you'll retrieve the messages from the `messages` table, possibly filtering by timestamp.

```sql
-- Fetch messages
SELECT * FROM messages WHERE timestamp > '2023-08-24 00:00:00' ORDER BY timestamp DESC;
```

7. **Retrieving User Color:**

To retrieve a user's color, you'll query the `user_colors` table.

```sql
-- Retrieve user color
SELECT color FROM user_colors WHERE username = 'example_user';
```

8. **Retrieving User's Username:**

To retrieve the username associated with a user ID, you'll query the `users` table.

```sql
-- Retrieve username by user ID
SELECT username FROM users WHERE id = 1;
```

Also, ensure that you properly sanitize and validate user inputs to prevent SQL injection and other security vulnerabilities.
