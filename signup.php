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
$showCountdown = false;  // Flag to show the countdown

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['newEmail'];
    $password = password_hash($_POST['newPassword'], PASSWORD_DEFAULT); // Hash password

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $message = "<p class='error-message'>Email is already registered!</p>";
    } else {
        $sql = "INSERT INTO users (full_name, email, password) VALUES ('$fullname', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $message = "<p class='success-message'>New record created successfully!</p>";
            $showCountdown = true;  // Set the flag to show countdown
        } else {
            $message = "<p class='error-message'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }

    // Store the message in a session variable to display it after redirect
    $_SESSION['message'] = $message;
    $_SESSION['showCountdown'] = $showCountdown; // Store the flag in session

    // Redirect to the same page to prevent form resubmission on refresh
    header("Location: signup.php");
    exit();
}

$conn->close();

// Check if there's a message stored in the session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Clear the session message after it's been displayed
}

if (isset($_SESSION['showCountdown']) && $_SESSION['showCountdown'] === true) {
    $showCountdown = true;
    unset($_SESSION['showCountdown']); // Clear the flag
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up Form</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body>

  <div class="form_container">
    <!-- Sign up form container -->
    <div class="signup_form" id="signupForm">
      <form action="signup.php" method="POST">
        <h3>Create a New Account</h3>

        <!-- Full Name input box -->
        <div class="input_box">
          <label for="fullname">Full Name</label>
          <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" required />
        </div>

        <!-- Email input box (for sign-up) -->
        <div class="input_box">
          <label for="newEmail">Email</label>
          <input type="email" name="newEmail" id="newEmail" placeholder="Enter your email" required />
        </div>

        <!-- Password input box -->
        <div class="input_box">
          <label for="newPassword">Password</label>
          <input type="password" name="newPassword" id="newPassword" placeholder="Create a password" required />
        </div>

        <!-- Confirm Password input box -->
        <div class="input_box">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" id="confirmPassword" placeholder="Confirm your password" required />
        </div>

        <!-- Sign-up button -->
        <button type="submit">Sign Up</button>

        <p class="sign_in">Already have an account? <a href="login.php" id="showLogin">Log in</a></p>
      </form>

      <!-- Display the message after form submission -->
      <?php echo $message; ?>

      <!-- Countdown message -->
      <?php if ($showCountdown): ?>
        <div id="countdownContainer">
          <p>Registration successful! You will be redirected in <span id="countdown">3</span> seconds...</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <script>
    <?php if ($showCountdown): ?>
      let countdown = 3;
      const countdownElement = document.getElementById("countdown");

      // Countdown logic
      const interval = setInterval(function() {
        countdown--;
        countdownElement.textContent = countdown;

        if (countdown === 0) {
          clearInterval(interval);
          window.location.href = "login.php"; // Redirect to login page after countdown
        }
      }, 1000);
    <?php endif; ?>
  </script>

</body>
</html>
