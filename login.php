<?php
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, verify password
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set success message
            $message = "<p class='success-message'>Successful Login!</p>";

            // Redirect to "Our Menu" section of home.php
            header("Location: home.php#menu");
            exit(); // Make sure no further code is executed after the redirect
        } else {
            // Set error message for incorrect password
            $message = "<p class='error-message'>Invalid password!</p>";
        }
    } else {
        // Set error message for no user found
        $message = "<p class='error-message'>No user found with this email!</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Form</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body>
  
  <div class="form_container">
    <!-- Login form container -->
    <div class="login_form" id="loginForm">
      <form action="login.php" method="POST">
        <h3>Log in with</h3>

        <!-- Email input box -->
        <div class="input_box">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" placeholder="Enter email address" required />
        </div>

        <!-- Password input box -->
        <div class="input_box">
          <div class="password_title">
            <label for="password">Password</label>
            <a href="#" id="forgotPasswordLink">Forgot Password?</a>
          </div>
          <input type="password" name="password" id="password" placeholder="Enter your password" required />
        </div>

        <!-- Login button -->
        <button type="submit">Log In</button>

        <p class="sign_up">Don't have an account? <a href="#" id="showSignup">Sign up</a></p>
      </form>

      <!-- Display login message -->
      <?php echo $message; ?>

    </div>

    <!-- Sign up form container (Initially hidden) -->
    <div class="signup_form" id="signupForm" style="display: none;">
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

        <p class="sign_in">Already have an account? <a href="#" id="showLogin">Log in</a></p>
      </form>
    </div>
    <!-- Forgot Password Modal -->
  <div id="forgotPasswordModal" class="modal" style="display: none;">
    <div class="modal_content">
      <span id="closeModal" class="close">&times;</span>
      <h3>Forgot Password</h3>
      <form action="forgot-password.php" method="POST">
        <div class="input_box">
          <label for="resetEmail">Enter your email address</label>
          <input type="email" id="resetEmail" name="resetEmail" placeholder="Email" required />
        </div>
        <button type="submit">Submit</button>
      </form>      
    </div>
  </div>
 </div>

  <script src="login.js"></script>
  <script>
    // JavaScript to handle the modal for forgot password
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const closeModal = document.getElementById('closeModal');

    // Show the forgot password modal when the link is clicked
    forgotPasswordLink.addEventListener('click', function(event) {
      event.preventDefault();
      forgotPasswordModal.style.display = 'block';
    });

    // Close the modal when the close button is clicked
    closeModal.addEventListener('click', function() {
      forgotPasswordModal.style.display = 'none';
    });

    // Close the modal if the user clicks outside of it
    window.addEventListener('click', function(event) {
      if (event.target === forgotPasswordModal) {
        forgotPasswordModal.style.display = 'none';
      }
    });

    // Toggle the visibility of login and signup forms
    const showSignup = document.getElementById('showSignup');
    const showLogin = document.getElementById('showLogin');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');

    // Show the signup form
    showSignup.addEventListener('click', function(event) {
      event.preventDefault();
      loginForm.style.display = 'none';
      signupForm.style.display = 'block';
    });

    // Show the login form
    showLogin.addEventListener('click', function(event) {
      event.preventDefault();
      signupForm.style.display = 'none';
      loginForm.style.display = 'block';
    });
  </script>
</body>
</html>
