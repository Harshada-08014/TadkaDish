<?php
// Connect to the existing client-side database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch dashboard data: total orders, total users, total sales, etc.

// Fetch total orders
$total_orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$result = $conn->query($total_orders_query);
$total_orders = $result->fetch_assoc()['total_orders'];

// Fetch total users
$total_users_query = "SELECT COUNT(*) AS total_users FROM users";
$result = $conn->query($total_users_query);
$total_users = $result->fetch_assoc()['total_users'];

// Fetch total sales in INR (convert from USD or other currencies to INR)
$total_sales_query = "SELECT SUM(total_price) AS total_sales FROM orders WHERE status = 'completed'";
$result = $conn->query($total_sales_query);
$total_sales_in_usd = $result->fetch_assoc()['total_sales'];
$total_sales_in_inr = $total_sales_in_usd ; // Conversion rate: 1 USD = 82 INR (change if needed)

// Fetch recent orders (limit to 5 most recent orders)
$recent_orders_query = "SELECT * FROM orders ORDER BY order_id DESC LIMIT 5";
$recent_orders_result = $conn->query($recent_orders_query);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard1.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>Tadka</h2>
            </div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage-orders.php">Manage Orders</a></li>
                <li><a href="manage-users.php">Manage Users</a></li>
                <li><a href="manage-menu.php">Manage Menu</a></li>
                <!--<li><a href="analytics.php">Analytics / Reports</a></li>-->
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Admin Dashboard</h1>
            </header>

            <!-- Dashboard summary -->
            <div class="dashboard-summary">
                <div class="card">
                    <h3>Total Orders</h3>
                    <p><?php echo $total_orders; ?></p>
                </div>
                <div class="card">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="card">
                    <h3>Total Sales</h3>
                    <p>₹<?php echo number_format($total_sales_in_inr, 2); ?></p> <!-- Displaying total sales in INR -->
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="recent-orders">
                <h3>Recent Orders</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Total Price (INR)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $recent_orders_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $order['order_id']; ?></td>
                                <td><?php echo $order['name']; ?></td>
                                <td>₹<?php echo number_format($order['total_price'] ); ?></td> <!-- Displaying price in INR -->
                                <td><?php echo ucfirst($order['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Analytics/Reports Section 
            <div class="analytics">
                <h3>Sales Overview</h3>
                <canvas id="sales-chart"></canvas>
            </div> -->
        </div>
    </div>

    <!-- Chart.js for Sales Overview -->
    <script>
        var ctx = document.getElementById('sales-chart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales (INR)',
                    data: [82000, 120000, 150000, 180000, 200000, 220000], // Example data in INR
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
