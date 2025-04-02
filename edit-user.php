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

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    // Update the user details in the users table
    $sql = "UPDATE users SET full_name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $fullname, $email, $user_id);
    $stmt->execute();

    header('Location: manage-users.php');
}

// Fetch user details from the users table
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="edit-user.css">
</head>
<body>
    <div class="container">
        <h1>Edit User Information</h1>
        <form method="POST" class="user-form">
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <button type="submit" class="submit-btn">Update</button>
        </form>
    </div>
    
    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
