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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user details from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $food_items = json_encode($_SESSION['order']);
    $total_price = $_SESSION['total_price'];
    
    // Insert order into the database
    $sql = "INSERT INTO orders (food_items, total_price, name, email, address, status) 
            VALUES ('$food_items', '$total_price', '$name', '$email', '$address', 'Order Placed')";
    
    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id; // Get the inserted order's ID
        $_SESSION['order_id'] = $order_id; // Save it for future reference (e.g., order status page)
        
        // Clear the session after successful order
        $_SESSION['order'] = [];
        $_SESSION['total_price'] = 0;

        // Redirect to the order status page
        header("Location: order_status.php?order_id=$order_id");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make an Order</title>
    <link rel="stylesheet" href="make_order.css">

</head>
<body>
    <h2>YOUR ORDER</h2>
    <ul>
        <?php
        foreach ($_SESSION['order'] as $order) {
            echo "<li>{$order['name']} - ₹{$order['price']}</li>";
        }
        ?>
    </ul>
    <p>Total Price: ₹<?php echo $_SESSION['total_price']; ?></p>
    
    <h3>Enter your details:</h3>
    <form method="POST" action="">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="address">Address:</label><br>
        <textarea id="address" name="address" required></textarea><br><br>

        <button type="submit">Place Order</button>
    </form>
</body>
</html>
