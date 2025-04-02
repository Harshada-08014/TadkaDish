<?php
// Database connection (inserted directly here)
$host = 'localhost';  // your DB host
$username = 'root';   // your DB username
$password = '';       // your DB password
$dbname = 'user_db';  // your DB name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from URL
$user_id = $_GET['id'];

// Fetch the user details to display the name or other info
$sql = "SELECT full_name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user exists
if ($user) {
    // If the form is submitted, delete the user
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // First, delete the user's orders if needed (optional, based on your requirements)
        $sql_orders = "DELETE FROM orders WHERE email IN (SELECT email FROM users WHERE id = ?)";
        $stmt_orders = $conn->prepare($sql_orders);
        $stmt_orders->bind_param('i', $user_id);
        $stmt_orders->execute();

        // Now, delete the user from the users table
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        // Redirect to the manage-users page after deletion
        header('Location: manage-users.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link rel="stylesheet" href="delete-user.css">
</head>
<body>
    <div class="container">
        <h2>Are you sure you want to delete the following user?</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

        <form method="POST">
            <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Yes, Delete</button>
            <a href="manage-users.php">Cancel</a>
        </form>
    </div>
</body>
</html>

<?php
} else {
    echo "<p>User not found.</p>";
}
?>
