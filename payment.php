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

// Check if 'order_id' is passed via URL, and ensure it's valid
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0; // Cast to integer to prevent SQL injection

// If order_id is invalid, redirect or show an error
if ($order_id <= 0) {
    echo "Invalid order ID!";
    exit();
}

// Fetch order details from the database using a prepared statement
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "Order not found!";
    exit();
}

// Process the payment (In a real-world scenario, you would integrate a payment gateway)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure payment amount and method are set
    $payment_amount = isset($_POST['amount']) ? $_POST['amount'] : 0;
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

    // Validate the amount and method
    if ($payment_amount <= 0 || empty($payment_method)) {
        echo "Invalid payment details!";
        exit();
    }

    // Insert payment record into the database using a prepared statement
    $stmt = $conn->prepare("INSERT INTO payments (order_id, payment_amount, payment_method, payment_status) VALUES (?, ?, ?, 'Completed')");
    $stmt->bind_param("ids", $order_id, $payment_amount, $payment_method);
    
    if ($stmt->execute()) {
        // Update order status to Completed after payment using a prepared statement
        $stmt = $conn->prepare("UPDATE orders SET status = 'Completed' WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        // After successful payment, redirect to index.php
        header("Location: index.php");
        exit();  // Make sure to stop further execution
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="payment2.css">
</head>
<body>
    <h2>Payment for Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h2>

    <h3>Order Details</h3>
    <p><strong>Total Price:</strong> ₹<?php echo htmlspecialchars($order['total_price']); ?></p>

    <!-- Payment Form -->
    <form method="POST" action="">
        <label for="amount">Amount to Pay:</label><br>
        <input type="number" name="amount" value="<?php echo htmlspecialchars($order['total_price']); ?>" readonly><br><br>

        <label for="payment_method">Payment Method:</label><br>
        <select name="payment_method" required>
            <option value="Credit Card">Credit Card</option>
            <option value="Debit Card">Debit Card</option>
            <option value="Net Banking">Net Banking</option>
            <option value="UPI">UPI</option>
        </select><br><br>

        <button type="submit">Make Payment</button>
    </form>
</body>
</html>
