<?php
// Start the session to manage session variables
session_start();

// Define correct credentials
$correct_username = "admin";
$correct_password = "admin123";

// Initialize an error message variable
$error_message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the username and password from the POST data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the credentials
    if ($username === $correct_username && $password === $correct_password) {
        // Credentials are correct, set the session variable and redirect to the dashboard
        $_SESSION['logged_in'] = true;  // Indicate that the user is logged in
        header("Location: dashboard.php"); // Redirect to the dashboard page
        exit(); // Ensure no further code is executed
    } else {
        // If the credentials are incorrect, display an error message
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin1.css">
    <script>
        // JavaScript function to validate the login credentials (same as before)
        function validateLogin(event) {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            
            // Check if the username and password are correct
            if (username !== "admin" || password !== "admin123") {
                event.preventDefault();  // Prevent form submission
                alert("Invalid username or password");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Admin Login</h2>
            <!-- Form submission is handled by this PHP file (login.php) -->
            <form action="admin.php" method="POST" id="login-form" onsubmit="return validateLogin(event)">
                <div class="textbox">
                    <input type="text" placeholder="Username" name="username" required id="username">
                </div>
                <div class="textbox">
                    <input type="password" placeholder="Password" name="password" required id="password">
                </div>
                <?php if (!empty($error_message)) { ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php } ?>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>

    <!--<script src="admin.js"></script>-->
</body>
</html>
