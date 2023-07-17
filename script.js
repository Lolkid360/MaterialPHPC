$(document).ready(function() {
  // Array to store the timestamps of appended messages
  var appendedTimestamps = [];

  // Function to append a new message to the message container
  function appendMessage(message) {
    var loggedInUsername = getLoggedInUsername();
    var isYou = message.sender === loggedInUsername;
    var color = isYou ? '#FFFFFF' : getUserColor(message.sender); // Set 'you' color to white
    var html = '<div class="message">';
    html += '<span class="sender' + (isYou ? ' you' : '') + '" style="color: ' + color + ';">' + (isYou ? 'You' : message.sender) + ':</span>';
    html += '<span class="text">' + message.message + '</span>';
    html += '<span class="timestamp">' + convertToPacificTime(message.timestamp) + '</span>';
    html += '</div>';
    $('#messages').append(html);

    // Update the appended timestamps array
    appendedTimestamps.push(message.timestamp);
  }

  // Function to convert timestamp to America Pacific Time
  function convertToPacificTime(timestamp) {
    var date = new Date(timestamp);
    var options = {
      timeZone: 'America/Los_Angeles',
      year: 'numeric',
      month: 'numeric',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric'
    };
    return date.toLocaleString('en-US', options);
  }


  // Function to get the logged-in username from the server
  function getLoggedInUsername() {
    var loggedInUsername = '';

    $.ajax({
      url: 'get_username.php',
      type: 'GET',
      async: false, // Ensure the request is synchronous
      success: function(response) {
        loggedInUsername = response.username;
      },
      error: function(xhr, status, error) {
        console.log('Error getting username:', error);
      }
    });

    return loggedInUsername;
  }

  // Function to get or generate a user color
  function getUserColor(username) {
    var storedColor = getUserColorFromDatabase(username);
    if (storedColor) {
      return storedColor;
    }

    var color = generateRandomColor();
    storeUserColorInDatabase(username, color);

    return color;
  }

  // Function to generate a random color
  function generateRandomColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
  }

  // Function to retrieve user color from the database
  function getUserColorFromDatabase(username) {
    var color = '';

    $.ajax({
      url: 'get_user_color.php',
      type: 'POST',
      dataType: 'json',
      data: { username: username },
      async: false, // Ensure the request is synchronous
      success: function(response) {
        color = response.color;
      },
      error: function(xhr, status, error) {
        console.log('Error retrieving user color:', error);
      }
    });

    return color;
  }

  // Function to store user color in the database
  function storeUserColorInDatabase(username, color) {
    $.ajax({
      url: 'store_user_color.php',
      type: 'POST',
      data: { username: username, color: color },
      success: function(response) {
        console.log('User color stored successfully.');
      },
      error: function(xhr, status, error) {
        console.log('Error storing user color:', error);
      }
    });
  }

  // Function to load new messages from the server
  function loadNewMessages() {
    $.ajax({
      url: 'fetch_messages.php',
      type: 'GET',
      dataType: 'json',
      data: { lastTimestamp: getLastAppendedTimestamp() }, // Send the last timestamp to the server
      success: function(response) {
        // Reverse the response array to maintain message order
        response.reverse();

        // Append each new message to the message container
        for (var i = 0; i < response.length; i++) {
          var message = response[i];
          appendMessage(message);
        }

        // Scroll to the bottom of the message container
        $('#messages').scrollTop($('#messages')[0].scrollHeight);
      },
      error: function(xhr, status, error) {
        console.log('Error loading new messages:', error);
      }
    });
  }

  // Function to get the last appended timestamp
  function getLastAppendedTimestamp() {
    if (appendedTimestamps.length > 0) {
      return appendedTimestamps[appendedTimestamps.length - 1];
    }
    return null;
  }

  // Load new messages on page load
  loadNewMessages();

  // Refresh messages every 5 seconds
  setInterval(function() {
    loadNewMessages();
  }, 5000);

  // Submit the chat message
  $('#chat-form').submit(function(event) {
    event.preventDefault();

    // Get the message from the input field
    var message = $('#message-input').val().trim();

    // Clear the input field
    $('#message-input').val('');

    // Check if the message is not empty
    if (message !== '') {
      // Send the message to the server
      $.ajax({
        url: 'send_message.php',
        type: 'POST',
        data: { message: message },
        success: function() {
          // Create a new message object with the current timestamp
          var timestamp = new Date().toISOString();
          var newMessage = {
            sender: getLoggedInUsername(),
            message: message,
            timestamp: timestamp
          };

          // Append the new message to the message container
          appendMessage(newMessage);

          // Scroll to the bottom of the message container
          $('#messages').scrollTop($('#messages')[0].scrollHeight);
        },
        error: function(xhr, status, error) {
          console.log('Error sending message:', error);
        }
      });
    }
  });
});
