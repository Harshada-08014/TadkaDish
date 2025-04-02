<?php
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

// Fetch orders based on filters
$filter_sql = "SELECT * FROM orders";
$order_filter = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['order_status'])) {
        $order_filter .= " WHERE status = '".$_GET['order_status']."'";
    }
    if (!empty($_GET['order_type'])) {
        $order_filter .= !empty($order_filter) ? " AND " : " WHERE ";
        $order_filter .= "order_type = '".$_GET['order_type']."'";
    }
    if (!empty($_GET['date_range'])) {
        $order_filter .= !empty($order_filter) ? " AND " : " WHERE ";
        $order_filter .= "order_date >= DATE_SUB(NOW(), INTERVAL ".$_GET['date_range']." DAY)";
    }
}

// SQL query to get orders data
$sql = "SELECT * FROM orders" . $order_filter;
$result = $conn->query($sql);

// Fetch all orders from the database
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
} else {
    $orders = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="manage-orders1.css">
</head>
<body>

<!-- Header and Navigation -->
<header>
    <h1>Manage Orders</h1>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage-users.php">Manage Users</a></li>
            <li><a href="manage-menu.php">Manage Menu</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    
</header>

<!-- Search and Filters -->
<section class="filters">
    <form action="manage-orders.php" method="GET">
        <!--<input type="text" name="search" placeholder="Search orders...">-->
        <select name="order_status">
            <option value="">Order Status</option>
            <option value="Pending">Pending</option>
            <option value="Delivered">Delivered</option>
            <option value="Completed">Completed</option>
            <!--<option value="Cancelled">Cancelled</option>-->
        </select>
        <select name="order_type">
            <option value="">Order Type</option>
            <option value="Delivery">Delivery</option>
            <!--<option value="Pickup">Pickup</option>-->
        </select>
        <select name="date_range">
            <option value="">Date Range</option>
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
            <option value="365">Last year</option>
        </select>
        <button type="submit">Apply Filters</button>
    </form>
</section>

<!-- Order Table -->
<section class="order-table">
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Items Ordered</th>
                <th>Order Date-Time</th>
                <th>Total Price (₹)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['name']; ?></td>
                    <td>
                        <?php
                        // Decode the food_items JSON string into an associative array
                        $food_items = json_decode($order['food_items'], true);

                        // Extract item names using array_map
                        $item_names = array_map(function($item) {
                            return $item['name']; // Adjust this if the property name is different
                        }, $food_items);

                        // Implode the item names into a comma-separated string
                        echo implode(', ', $item_names);
                        ?>
                    </td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td><?php echo '₹ ' . number_format($order['total_price'], 2); ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <!-- View Button -->
                        <form action="view-order.php" method="GET" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <button type="submit">View</button>
                        </form>

                        <!-- Update Status Button -->
                        <form action="update-order-status.php" method="GET" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

</body>
</html>
