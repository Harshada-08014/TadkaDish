<?php
// Start the session to preserve messages across page reloads
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['resetEmail'];

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Here, normally we would generate a token and send an email with a reset link
        $message = "<p class='success-message'>Password reset link sent to your email!</p>";
    } else {
        $message = "<p class='error-message'>No user found with this email!</p>";
    }

    // Store the message in a session variable to display it after redirect
    $_SESSION['message'] = $message;

    // Redirect to the same page to prevent form resubmission on refresh
    header("Location: forgot-password.php");
    exit();
}

$conn->close();

// Check if there's a message stored in the session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the session message after it's been displayed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body>

  <div class="form_container">
    <!-- Forgot Password form container -->
    <div class="login_form" id="loginForm">
      <h3>Forgot Password</h3>
      <form action="forgot-password.php" method="POST">
        <div class="input_box">
          <label for="resetEmail">Enter your email address</label>
          <input type="email" id="resetEmail" name="resetEmail" placeholder="Email" required />
        </div>
        <button type="submit">Submit</button>
      </form>      
      
      <!-- Display the message after form submission -->
      <?php echo $message; ?>
    </div>
  </div>

  <script src="login.js"></script>
</body>
</html>
