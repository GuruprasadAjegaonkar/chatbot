<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "chatbot_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate password strength
    if (!preg_match('/[A-Za-z]{4,}/', $password) || 
        !preg_match('/[\W_]/', $password) || 
        !preg_match('/\d{3,}/', $password)) {
        $error = "Please provide a strong password with at least 4 letters, 1 symbol, and 3 digits.";
    } else {
        // Check if username already exists
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists.";
        } else {
            // If the username doesn't exist, create a new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $hashedPassword);
            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                header('Location: index.php'); // Redirect to chatbot page
                exit();
            } else {
                $error = "Error creating the account. Please try again.";
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('sky.jpg'); /* Path to background image */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: rgba(255, 255, 255, 0.7); /* White with 70% opacity */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .register-container .form-group label {
            font-weight: bold;
        }
        .register-container .btn-animate {
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
        }
        .register-container .btn-animate:hover {
            background-color: #0056b3; /* Darker shade on hover */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Shadow effect on hover */
        }
        .register-container .btn-animate:active {
            background-color: #004494; /* Even darker shade on click */
            transform: scale(0.98); /* Slightly shrinks the button */
        }
        .pulse-animation {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 rgba(0, 0, 0, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 rgba(0, 0, 0, 0.3);
            }
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .spinner-border {
            width: 1rem;
            height: 1rem;
            border: 0.2em solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 0.2em solid #007bff;
            animation: spinner-border .75s linear infinite;
        }
        @keyframes spinner-border {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="register-container pulse-animation">
        <h2>Register</h2>
        <?php if (isset($error)) { echo "<div class='error-message'>$error</div>"; } ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="error-message" id="password-error"></div> <!-- Password strength error message -->
            </div>
            <button type="submit" class="btn btn-primary btn-animate">Register</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        const errorDiv = document.getElementById('password-error');
        const registerButton = document.querySelector('.btn-animate');

        passwordField.addEventListener('input', function() {
            const password = passwordField.value;
            let errorMessage = '';

            if (!/[A-Za-z]{4,}/.test(password)) {
                errorMessage = 'Password must contain at least 4 letters. ';
            } 
            if (!/[\
