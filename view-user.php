<?php
// Database connection
$host = 'localhost'; 
$username = 'root';   
$password = '';       
$dbname = 'user_db';  

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_GET['id']; 

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>

    <!-- Embedded CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Times New Roman;
            background-color: lightgrey;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        p {
            font-size: 1.1em;
            margin: 10px 0;
            color: #555;
        }

        .container {
            background-color: grey;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: translateY(-10px);
        }

        h2 {
            text-align: center;
            font-size: 1.7rem;
            margin-bottom: 20px;
            color: #333;
            font-family: Algerian;
            font-weight: normal;
        }

        p {
            font-size: 1rem;
            margin: 10px 0;
            color: #555;
        }

        strong {
            color: #333;
        }

        .user-details {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .user-details:hover {
            transform: scale(1.05);
        }

        .order {
            background-color:rgb(218, 216, 216);
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .order:hover {
            transform: scale(1.03);
        }

        h3 {
            font-size: 1.5rem;
            color: #333;
            margin-top: 20px;
            margin-bottom: 15px;
            text-align: center;
            font-family: Algerian;
            font-weight: normal;
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 1.8em;
            }

            h3 {
                font-size: 1.5em;
            }

            p {
                font-size: 1em;
            }
        }
    </style>

</head>
<body>
    

<?php
if ($user) {
    echo "<div class='container'>";
    echo "<div class='user-details'>";
    echo "<h2>User Details</h2>";
    echo "<p><strong>Name:</strong> " . htmlspecialchars($user['full_name']) . "</p>";  
    echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";

    // Fetch payment status (Completed/Not Completed) from the payments table for the latest order
    $sql_payment_status = "SELECT payment_status FROM payments WHERE order_id = ? ORDER BY payment_id DESC LIMIT 1";
    $stmt_payment_status = $conn->prepare($sql_payment_status);
    $stmt_payment_status->bind_param('i', $user_id);
    $stmt_payment_status->execute();
    $payment_result = $stmt_payment_status->get_result();
    
    if ($payment_result->num_rows > 0) {
        $payment = $payment_result->fetch_assoc();
        echo "<b><strong>Payment Status:</strong> " . htmlspecialchars($payment['payment_status']) . "</b>";
    } else {
        echo "<b><strong>Payment Status:</strong> Not available.</b>";
    }

    $sql_status = "SELECT status FROM orders WHERE email = ? ORDER BY order_date DESC LIMIT 1";
    $stmt_status = $conn->prepare($sql_status);
    $stmt_status->bind_param('s', $user['email']);
    $stmt_status->execute();
    $status_result = $stmt_status->get_result();
    
    if ($status_result->num_rows > 0) {
        $order = $status_result->fetch_assoc();
        echo "<p><strong>Status:</strong> " . htmlspecialchars($order['status']) . "</p>";
    } else {
        echo "<p>No order status available.</p>";
    }

    $sql_orders = "SELECT * FROM orders WHERE email = ?";
    $stmt_orders = $conn->prepare($sql_orders);
    $stmt_orders->bind_param('s', $user['email']);
    $stmt_orders->execute();
    $orders_result = $stmt_orders->get_result();

    if ($orders_result->num_rows > 0) {
        echo "<h3>Order History</h3>";
        while ($order = $orders_result->fetch_assoc()) {
            echo "<div class='order'>";
            echo "<p><strong>Order ID:</strong> " . htmlspecialchars($order['order_id']) . "</p>";

            // Decode the JSON food_items string into an array
            $food_items = json_decode($order['food_items'], true);
            
            if ($food_items) {
                echo "<p><strong>Food Items:</strong><br>";
                foreach ($food_items as $item) {
                    // Display the food item name and price
                    echo "<strong>" . htmlspecialchars($item['name']) . ":</strong> ₹" . htmlspecialchars($item['price']) . "<br>";
                }
                echo "</p>";
            } else {
                echo "<p><strong>Food Items:</strong> No food items available.</p>";
            }

            echo "<p><strong>Total Price:</strong> ₹" . htmlspecialchars($order['total_price']) . "</p>";
            echo "<p><strong>Order Date:</strong> " . htmlspecialchars($order['order_date']) . "</p>";
            echo "<p><strong>Status:</strong> " . htmlspecialchars($order['status']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No orders found for this user.</p>";
    }
    echo "</div>";
    echo "</div>";
} else {
    echo "<p>User not found.</p>";
}

$conn->close();
?>

</body>
</html>
