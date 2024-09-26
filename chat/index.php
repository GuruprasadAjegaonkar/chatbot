<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatbot_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web-Technology Chatbot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('sky.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
        }

        .chat-container {
            width: 90%;
            max-width: 800px;
            height: 90%;
            max-height: 600px;
            display: flex;
            flex-direction: column;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .card-header {
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            padding: 15px;
            background-color: #87CEFA; /* Sky blue color */
            color: white;
            border-radius: 10px 10px 0 0;
            position: relative;
        }

        #chat-box {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        .chat-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            max-width: 70%;
        }

        .bot-message {
            color: #000;
            background-color: #e0f7fa;
            margin-left: auto;
        }

        .user-message {
            color: #000;
            background-color: #e8f5e9;
        }

        .card-footer {
            padding: 15px;
            background-color: #87CEFA; /* Sky blue color */
            display: flex;
            align-items: center;
            border-radius: 0 0 10px 10px;
        }

        .input-group {
            flex-grow: 1;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Logout button at the top-right corner */
        .logout-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 16px;
            font-size: 14px;
            background-color: #dc3545;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="card-header">
            Web-Technology Chatbot
            <a href="logout.php" class="logout-btn">Logout</a> <!-- Top-right Logout button -->
        </div>

        <div class="card-body" id="chat-box">
            <!-- Fetch and display chat history for the logged-in user -->
            <?php
            $sql = "SELECT user_message, bot_response FROM chat_history WHERE user_id = ? ORDER BY id";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("i", $_SESSION['user_id']);
            $stmt->execute();
            $stmt->bind_result($userMessage, $botResponse);

            while ($stmt->fetch()) {
                echo "<div class='chat-message user-message'><strong>User:</strong> $userMessage</div>";
                echo "<div class='chat-message bot-message'><strong>Bot:</strong> $botResponse</div>";
            }

            $stmt->close();
            ?>
        </div>

        <div class="card-footer">
            <div class="input-group">
                <input type="text" name="userInput" class="form-control" id="user-input" placeholder="Type your message...">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" onclick="sendMessage()">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

<?php
$conn->close();
?>
