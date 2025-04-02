<?php
session_start();

// Database connection (Update with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";  // Changed to user_db as per the request

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the order session if it doesn't exist
if (!isset($_SESSION['order'])) {
    $_SESSION['order'] = [];
    $_SESSION['total_price'] = 0;
}

// Handle adding items to the order
if (isset($_POST['add_to_order'])) {
    $itemName = $_POST['item_name'];
    $itemPrice = $_POST['item_price'];

    // Add item to session order
    $_SESSION['order'][] = ['name' => $itemName, 'price' => $itemPrice];
    $_SESSION['total_price'] += $itemPrice;
}

// Redirect to "make an order" page if checkout button is clicked
if (isset($_POST['checkout'])) {
    header("Location: make_order.php");
    exit();
}

// Load the menu items from the JSON file
$menu_file = 'desserts.json';
$menu_items = file_exists($menu_file) ? json_decode(file_get_contents($menu_file), true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desserts and Drinks</title>
    <link rel="stylesheet" href="desserts1.css">
</head>
<body>
    <header>
        <h1>Desserts and Drinks</h1>
        <p>Order your favorite Desserts and Drinks online!</p>
    </header>

    <div class="menu">
        <h2></h2>
        <div class="menu-items">
            <?php
            

            foreach ($menu_items as $item) {
                echo "
                    <div class='menu-item'>
                        <img src='{$item['image']}' alt='{$item['name']}'>
                        <h3>{$item['name']}</h3>
                        <p>Rs {$item['price']}</p>
                        <form method='POST' action=''>
                            <input type='hidden' name='item_name' value='{$item['name']}'>
                            <input type='hidden' name='item_price' value='{$item['price']}'>
                            <button type='submit' name='add_to_order' class='order-btn'>Add to Order</button>
                        </form>
                    </div>
                ";
            }
            ?>
        </div>
    </div>

    <div class="order-summary">
        <h2>Your Order</h2>
        <ul id="order-list">
            <?php
            foreach ($_SESSION['order'] as $order) {
                echo "<li>{$order['name']} - ₹{$order['price']}</li>";
            }
            ?>
        </ul>
        <div id="total-price">Total: ₹<?php echo $_SESSION['total_price']; ?></div>
        <form method="POST" action="">
            <button type="submit" name="checkout" id="checkout-btn">Proceed to Checkout</button>
        </form>
    </div>

    <footer>
        <p>Thank you for choosing our Desserts and Drins!</p>
    </footer>
</body>
</html>
