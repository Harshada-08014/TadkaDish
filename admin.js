
function validateLogin(event) {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    // Check if the username and password are correct
    if (username !== "admin" || password !== "admin123") {
        event.preventDefault();  // Prevent form submission
        alert("Invalid username or password");
        return false;
    }
    return true;  // Proceed with form submission if credentials are valid
}
