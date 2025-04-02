<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <link rel="stylesheet" href="manage-menu.css"> <!-- External CSS for styles -->
</head>

<body>
    <!-- Header Section -->
    <header>
        <h1>Menu Management</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage-orders.php">Manage Orders</a></li>
                <li><a href="manage-users.php">Manage Users</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content Section -->
    <div class="container">
        <h2>Categories</h2>
        <div class="category-buttons">
            <button onclick="window.location.href='manage-veg.php'">Veg</button>
            <button onclick="window.location.href='manage-nonveg.php'">Non-Veg</button>
            <button onclick="window.location.href='manage-desserts.php'">Desserts</button>
        </div>
    </div>
</body>

</html>
