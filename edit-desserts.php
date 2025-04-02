<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dessert Item</title>
    <link rel="stylesheet" href="edit-food.css">
</head>
<body>
    <header>
        <h1>Edit Dessert Item</h1>
    </header>

    <div class="menu">
        <!--<h2>Edit Dessert Item</h2>-->

        <?php
        // Load the menu items from the JSON file (nonveg.json)
        $menu_file = 'desserts.json'; 
        if (file_exists($menu_file)) {
            $menu_items = json_decode(file_get_contents($menu_file), true);
        } else {
            $menu_items = [];  // If the file doesn't exist, start with an empty array
        }

        // Get the food item details from the query string
        if (isset($_GET['name'], $_GET['price'], $_GET['image'])) {
            $name = $_GET['name'];
            $price = $_GET['price'];
            $image = $_GET['image'];

            // Update item when the form is submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $updated_name = $_POST['item_name'];
                $updated_price = $_POST['item_price'];
                $updated_image = $_POST['item_image'];

                // Update the item in the menu
                foreach ($menu_items as &$item) {
                    if ($item['name'] === $name) {
                        $item['name'] = $updated_name;
                        $item['price'] = $updated_price;
                        $item['image'] = $updated_image;
                    }
                }

                // Save the updated menu back to the JSON file
                file_put_contents($menu_file, json_encode($menu_items, JSON_PRETTY_PRINT));

                // Redirect back to manage-nonveg.php to reflect changes
                header('Location: manage-desserts.php');
                exit;
            }
        } else {
            echo "<p>Food item details not found.</p>";
            exit;
        }
        ?>

        <!-- Edit form -->
        <form method="POST">
            <label for="item_name">Food Item Name:</label>
            <input type="text" id="item_name" name="item_name" value="<?php echo $name; ?>" required>

            <label for="item_price">Price (in INR):</label>
            <input type="number" id="item_price" name="item_price" value="<?php echo $price; ?>" required>

            <label for="item_image">Food Item Image URL:</label>
            <input type="text" id="item_image" name="item_image" value="<?php echo $image; ?>" required>

            <button type="submit" class="order-btn">Save Changes</button>
        </form>
    </div>
</body>
</html>
