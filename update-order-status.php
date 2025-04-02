<?php 
// Include your database connection
$host = 'localhost'; // Your database host
$user = 'root';      // Your database username
$password = '';      // Your database password (empty for XAMPP default)
$dbname = 'user_db'; // Your database name

// Create a new database connection using MySQLi
$db = new mysqli($host, $user, $password, $dbname);

// Check for connection errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


// Fetch the order ID from the URL parameter (GET method)
$order_id = $_GET['order_id']; // Example: update-order-status.php?order_id=123

// Fetch order details from the database
$query = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $order_id); // Using prepared statements to prevent SQL injection
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc(); // Fetch order data as associative array

// Handle the form submission for updating the status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];

    // Update the order status in the database
    $update_query = "UPDATE orders SET status = ? WHERE order_id = ?";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bind_param("si", $new_status, $order_id);
    $update_stmt->execute();
    
    // Redirect to the order details page after successful status update
    header("Location: view-order.php?order_id=" . $order_id);
    exit();
}

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Order Status</title>
  <link rel="stylesheet" href="update-order-status1.css"> <!-- External CSS file for styling -->
</head>
<body>
  <div class="container">
    <h1>Update Order #<?php echo $order['order_id']; ?> - Status</h1>
    
    <div class="order-info">
      <h2>Customer Information</h2>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>

      <h2>Order Details</h2>
      <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
      <p><strong>Current Status:</strong> <span class="status"><?php echo htmlspecialchars($order['status']); ?></span></p>
      <p><strong>Total Price:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p>

      <h2>Food Items</h2>
      <ul class="food-items">
        <?php
        // Decode the JSON string into an array
        $food_items = json_decode($order['food_items'], true); // Decode JSON into an associative array

        // Check if food items are available
        if ($food_items) {
          // Loop through the food items and display them
          foreach ($food_items as $item) {
            // Display only the name and price of each food item
            echo "<li><strong>" . htmlspecialchars($item['name']) . ":</strong> ₹" . htmlspecialchars($item['price']) . "</li>";
          }
        } else {
          echo "<li>No food items found</li>"; // If no items are found
        }
        ?>
      </ul>

      <!-- Form to update order status -->
      <h2>Update Status</h2>
      <form action="update-order-status.php?order_id=<?php echo $order['order_id']; ?>" method="POST">
        <label for="status">Select New Status:</label>
        <select name="status" id="status">
          <option value="Pending" <?php echo ($order['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
          <option value="Completed" <?php echo ($order['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
          <option value="Delivered" <?php echo ($order['status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
        </select>
        <br><br>
        <button type="submit" class="btn update-btn">Update Status</button>
      </form>
      
      <a href="manage-orders.php" class="btn back-btn">Back to Orders</a>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
