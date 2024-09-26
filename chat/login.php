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

    // Fetch the user from the database
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($userId, $hashedPassword);
    $stmt->fetch();

    if ($userId && password_verify($password, $hashedPassword)) {
        // If the username and password match, set the session and redirect
        $_SESSION['user_id'] = $userId;
        header('Location: index.php'); // Redirect to chatbot page
        exit();
    } else {
        $error = "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .login-container {
            background-color: rgba(255, 255, 255, 0.7); /* White with 70% opacity */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-container .form-group label {
            font-weight: bold;
        }
        .login-container .btn-animate {
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
        }
        .login-container .btn-animate:hover {
            background-color: #0056b3; /* Darker shade on hover */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Shadow effect on hover */
        }
        .login-container .btn-animate:active {
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
        .register-link {
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
    <div class="login-container pulse-animation">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<div class='error-message'>$error</div>"; } ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-animate">Login</button>
        </form>
        <div class="register-link">
            <p>New here? <a href="register.php">Register here</a>.</p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.querySelector('form');
        const loginButton = document.querySelector('.btn-animate');

        loginForm.addEventListener('submit', function() {
            loginButton.innerHTML = '<i class="spinner-border spinner-border-sm"></i> Logging in...'; // Change button text and add spinner
            loginButton.disabled = true; // Disable the button to prevent multiple clicks
        });
    });
    </script>
</body>
</html>
