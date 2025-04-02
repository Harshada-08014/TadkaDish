<?php
// Start the session
session_start();

// Initialize a variable for the status message
$statusMessage = "";

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input data
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // Prepare the SQL query to insert data into the contact_form table in user_db
    $sql = "INSERT INTO contact_form (name, email, message) VALUES ('$name', '$email', '$message')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Success message
        $statusMessage = "<p class='success-message'>Message sent successfully!</p>";
    } else {
        // Error message
        $statusMessage = "<p class='error-message'>Error: " . $conn->error . "</p>";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tadka</title>
    <link rel="stylesheet" href="home2.css">
</head>
<body>
    <!-- Navigation -->
    <header>
        <div class="logo">
            <h1>Tadka</h1>
        </div>
        <nav>
            <ul id="menu">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#menu">Our Menu</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="logout.php">Logout</a></li> <!-- Logout Button -->
            </ul>
            <div class="menu-toggle" onclick="toggleMenu()">&#9776;</div>
        </nav>
    </header>
    
    <!-- Hero Section (Opening Image) -->
    <section class="hero">
        <div class="hero-text">
            <h2 class="jumping-text">Welcome to Tadka</h2> <!-- Added class for jumping effect -->
            <b>Where quality and taste meet</b>
        </div>
    </section>

    <!-- Menu Section -->
    <section class="menu" id="menu">
        <h2>Our Menu</h2>
        <b>Where every flavor tells a story</b>
        <div class="menu-items">
            <div class="menu-item">
            <a href="veg.php">  <!-- Added this link to redirect to veg.php -->
                <img src='Image/veg1.png' alt="veg">
                <h3>Vegeterian Meals</h3>
                <p>A delicious meal that will leave you wanting more.</p>
                <p>Fresh, healthy, and absolutely tasty!</p>
            </a>
            </div>
            <div class="menu-item">
            <a href="nonveg.php">  <!-- Added this link to redirect to veg.php -->
                <img src='Image/nonveg.png' alt="non-veg">
                <h3>Non-Veg Meals</h3>
                <p>Perfectly crafted to satisfy your cravings.</p>
                <p>A taste you'll remember.</p>
            </a>
            </div>
            <div class="menu-item">
            <a href="desserts.php">  <!-- Added this link to redirect to veg.php -->
                <img src='Image/sweets.jpg' alt="dessert">
                <h3>Desserts & Drinks</h3>
                <p>Taking sweet moments to another level.</p>
                <p>Serving up some sweet happiness.</p>
            </a>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about" id="about">
        <h2>ABOUT Us</h2>
        <p>Welcome to Tadka, where we bring the vibrant flavors of India to your table with every dish.</p><br>
        <p>Our menu features authentic Indian cuisine, prepared with the finest ingredients and traditional cooking methods.</p>
        <p>From sizzling tandoori to rich curries and freshly made naan, we offer a diverse range of regional specialties.</p>
        <p>At Tadka, we focus on delivering an exceptional dining experience with fresh, high-quality food, warm service, and a welcoming atmosphere.</p>
        <p>Whether it's a casual meal or a special celebration, we promise to make every visit memorable.</p><br>
        <marquee>Come experience the true essence of Indian cuisine at Tadka!</marquee>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2>Contact Us</h2>
        <p>Have a question? Reach out to us!</p>

        <!-- Display the status message here -->
        <?php echo $statusMessage; ?>

        <form action="home.php" method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Tadka. All Rights Reserved.</p>
    </footer>

    <script src="home.js"></script>
    <script>
        function toggleMenu() {
            const menu = document.getElementById("menu");
            menu.style.display = menu.style.display === "flex" ? "none" : "flex";
        }
    </script>
</body>
</html>
