function sendMessage() {
    var userInput = document.getElementById("user-input").value;
    if (userInput.trim() !== "") {
        appendMessage("user", userInput);
        getBotResponse(userInput);
        document.getElementById("user-input").value = "";
    }
}

function appendMessage(sender, message) {
    var chatBox = document.getElementById("chat-box");
    var messageElement = document.createElement("div");
    messageElement.className = "chat-message " + sender + "-message";
    messageElement.innerHTML = message;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function getBotResponse(userInput) {
    // Send user input to PHP script for processing
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "bot.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var botResponse = xhr.responseText;
            appendMessage("bot", botResponse);
            // Optionally, you can store the conversation in the database here
        }
    };
    xhr.send("userInput=" + userInput);
}
