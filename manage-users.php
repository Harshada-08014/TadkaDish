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

// Fetch user details
$sql = "SELECT id, full_name, email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage-users.css">
</head>
<body>

    <!-- Header Section -->
    <header>
        <div class="header-content">
            <h1>Manage Users</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="manage-orders.php">Manage Orders</a></li>
                    <li><a href="manage-menu.php">Manage Menu</a></li>
                    
                    <li><a href="admin_logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content Section -->
    <div class="container">
        <!-- User Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <button class="action-btn view-btn">
                                <a href="view-user.php?id=<?php echo $user['id']; ?>">View</a>
                            </button>
                            <button class="action-btn edit-btn">
                                <a href="edit-user.php?id=<?php echo $user['id']; ?>">Edit</a>
                            </button>
                            <button class="action-btn delete-btn">
                                <a href="delete-user.php?id=<?php echo $user['id']; ?>">Delete</a>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>

</body>
</html>
