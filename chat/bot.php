<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please login first.");
}

// Retrieve user input
$userInput = $_POST['userInput'];
$userId = $_SESSION['user_id']; // Retrieve logged-in user's ID

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "chatbot_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch bot response based on user input
$sql = "SELECT bot_response FROM chat_history WHERE user_message = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $userInput);
$stmt->execute();
$stmt->bind_result($botResponse);

if ($stmt->fetch()) {
    echo $botResponse;
} else {
    $botResponse = "I'm sorry, I didn't understand that.";
    echo $botResponse;
}

$stmt->close();

// Insert the chat history for the logged-in user
$sql = "INSERT INTO chat_history (user_message, bot_response, user_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssi", $userInput, $botResponse, $userId);
$stmt->execute();

$stmt->close();
$conn->close();
?>
