<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the order ID from the URL parameter
$order_id = $_GET['order_id'];

// Fetch order details from the database
$sql = "SELECT * FROM orders WHERE order_id = $order_id";
$result = $conn->query($sql);
$order = $result->fetch_assoc();

if (!$order) {
    echo "Order not found!";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>
    <link rel="stylesheet" href="order_status1.css">
</head>
<body>
    <h2>Order Status</h2>

    <h3>Order ID: <?php echo $order['order_id']; ?></h3>
    <p><strong>Name:</strong> <?php echo $order['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
    <p><strong>Address:</strong> <?php echo $order['address']; ?></p>

    <h3>Food Items:</h3>
    <ul>
        <?php
        $food_items = json_decode($order['food_items'], true);
        foreach ($food_items as $item) {
            echo "<li>{$item['name']} - ₹{$item['price']}</li>";
        }
        ?>
    </ul>

    <p><strong>Total Price:</strong> ₹<?php echo $order['total_price']; ?></p>

    <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
    
    <p><strong>Order placed successfully!</strong></p>

    <!-- Make Payment Button -->
    <h4>Proceed to Payment</h4>
    <form method="GET" action="payment.php">
        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
        <button type="submit">Make Payment</button>
    </form>
</body>
</html>
