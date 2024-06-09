<?php
// Retrieve user input
$userInput = $_POST['userInput'];

// Database connection parameters
$servername = "localhost";
$username = "root"; // Default MySQL username in XAMPP
$password = ""; // Default MySQL password is empty in XAMPP
$dbname = "chatbot_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement to retrieve bot response based on user input
$sql = "SELECT bot_response FROM chat_history WHERE user_message = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userInput);
$stmt->execute();
$stmt->bind_result($botResponse);

// Fetch bot response
if ($stmt->fetch()) {
    echo $botResponse;
} else {
    // If no response found in the database, use the default response
    echo "I'm sorry, I didn't understand that.";
}

$stmt->close();
$conn->close();



























/*
// Retrieve user input
$userInput = $_POST['userInput'];

// Basic bot responses based on user input
$botResponses = array(
    "hello" => "Hi there! How can I assist you?",
    "hi" => "Hello! How can I help you today?",
    "how are you?" => "I'm just a bot, but I'm doing well. How about you?",
    "goodbye" => "Goodbye! Have a great day!",
    "thanks" => "You're welcome!",
    "thank you" => "You're welcome!",
    "how can I contact support?" => "You can contact support by emailing support@example.com.",
    "what is your name?" => "I'm just a bot. I don't have a name!",
    "who created you?" => "I was created by a team of developers.",
    "tell me a joke" => "Why did the scarecrow win an award? Because he was outstanding in his field!",
    "what's the weather like today?" => "The weather today is sunny with a high of 75°F.",
    "what time is it?" => "It's currently [current time].",
    "how do I reset my password?" => "You can reset your password by visiting the 'Forgot Password' page on our website.",
    "what is 2 + 2?" => "2 + 2 equals 4.",
    "tell me about yourself" => "I'm just a simple chatbot programmed to assist you with your inquiries.",
    "do you have any siblings?" => "No, I'm an only bot!",
    "can you help me with my homework?" => "I can try! What subject is your homework on?",
    "how do I unsubscribe from your newsletter?" => "You can unsubscribe from our newsletter by clicking the 'Unsubscribe' link at the bottom of any newsletter email.",
    "where can I find your privacy policy?" => "You can find our privacy policy on our website.",
    "what is the meaning of life?" => "The meaning of life is subjective and varies from person to person!",
    "what's your favorite color?" => "I'm partial to binary, but I don't have eyes to see colors!",
    "how do I delete my account?" => "You can delete your account by logging in and navigating to the account settings page.",
    "what is the capital of France?" => "The capital of France is Paris.",
    "how do I change my profile picture?" => "You can change your profile picture by going to your profile settings.",
    "what's the best way to learn programming?" => "The best way to learn programming is by practicing regularly and working on real-world projects.",
    "what's your favorite food?" => "I don't eat, but I've heard good things about RAMen!",
    "what's the difference between HTML and CSS?" => "HTML is used to structure content on a webpage, while CSS is used to style the content and make it visually appealing.",
    "can you recommend a good book?" => "Sure! One of my favorites is 'The Hitchhiker's Guide to the Galaxy' by Douglas Adams.",
    "what's the tallest mountain in the world?" => "The tallest mountain in the world is Mount Everest.",
    "tell me something interesting" => "Did you know that honey never spoils? Archaeologists have found pots of honey in ancient Egyptian tombs that are over 3,000 years old and still perfectly edible!",
    "what's your favorite movie?" => "I don't watch movies, but I've heard 'The Matrix' is popular among bots.",
);

// Default response if no match found
$defaultResponse = "I'm sorry, I didn't understand that.";

// Search for a response based on user input
if (array_key_exists($userInput, $botResponses)) {
    echo $botResponses[$userInput];
} else {
    echo $defaultResponse;
}

// Optionally, you can store the conversation in the database here

*/
?>
