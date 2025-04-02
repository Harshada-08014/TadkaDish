// Handle showing the Sign-Up form and hiding the Login form
document.getElementById("showSignup").addEventListener("click", function(event) {
  event.preventDefault();
  document.getElementById("loginForm").style.display = "none";  // Hide login form
  document.getElementById("signupForm").style.display = "block";  // Show sign-up form
});

// Handle showing the Login form and hiding the Sign-Up form
document.getElementById("showLogin").addEventListener("click", function(event) {
  event.preventDefault();
  document.getElementById("signupForm").style.display = "none";  // Hide sign-up form
  document.getElementById("loginForm").style.display = "block";  // Show login form
});

// Handle showing the Forgot Password form and hiding the Login form
document.querySelector("#forgotPasswordLink").addEventListener("click", function(event) {
  event.preventDefault();
  document.getElementById("loginForm").style.display = "none";
  document.getElementById("forgotPasswordForm").style.display = "block";
});

// Handle showing the Login form and hiding the Forgot Password form
document.getElementById("backToLogin").addEventListener("click", function(event) {
  event.preventDefault();
  document.getElementById("forgotPasswordForm").style.display = "none";  // Hide forgot password form
  document.getElementById("loginForm").style.display = "block";  // Show login form
});

// Handle countdown and redirect after successful sign-up
// Check if the countdown container exists on the page (which indicates successful registration)
if (document.getElementById("countdownContainer")) {
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
}
