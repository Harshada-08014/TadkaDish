<?php
// Database credentials
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
$order_id = $_GET['order_id']; // Example: view-order.php?order_id=123

// Fetch order details from the database
$query = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $order_id); // Using prepared statements to prevent SQL injection
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc(); // Fetch order data as associative array

// Fetch payment status from the payments table
$query_payment_status = "SELECT payment_status FROM payments WHERE order_id = ?";
$stmt_payment = $db->prepare($query_payment_status);
$stmt_payment->bind_param("i", $order_id);
$stmt_payment->execute();
$payment_result = $stmt_payment->get_result();
$payment = $payment_result->fetch_assoc(); // Fetch payment data as associative array

// Close the statement and connection
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Order</title>
  <link rel="stylesheet" href="view-order.css"> <!-- External CSS file for styling -->
</head>
<body>
  <div class="container">
    <h1>Order <?php echo $order['order_id']; ?> - Details</h1>
    
    <div class="order-info">
      <h2>Customer Information</h2>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>

      <h2>Order Details</h2>
      <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
      <p><strong>Status:</strong> <span class="status"><?php echo htmlspecialchars($order['status']); ?></span></p>
      
      <!-- Change here for displaying Indian Rupee symbol -->
      <p><strong>Total Price:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p> <!-- Changed to ₹ -->

       <!-- Display Payment Status -->
       <p><strong>Payment Status:</strong> <span class="status"><?php echo htmlspecialchars($payment['payment_status']); ?></span></p>

      <h2>Food Items</h2>
      <ul class="food-items">
        <?php
        // Decode the JSON string into an array
        $food_items = json_decode($order['food_items'], true); // Decoding JSON into an associative array
        
        // Check if food items are available
        if ($food_items) {
          foreach ($food_items as $item) {
            // Display only the name and price of each food item
            echo "<li><strong>" . htmlspecialchars($item['name']) . ":</strong> ₹" . htmlspecialchars($item['price']) . "</li>";
          }
        } else {
          echo "<li>No food items found</li>";
        }
        ?>
      </ul>
      
      <a href="manage-orders.php" class="btn back-btn">Back to Orders</a>
    </div>
  </div>

  <!-- Optional JavaScript (e.g., for any interactivity or popups) -->
  <script src="script.js"></script>
</body>
</html>
