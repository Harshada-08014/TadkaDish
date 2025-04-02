<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage NonVeg Food</title>
    <link rel="stylesheet" href="manage-nonveg1.css">
    <script>
        // Function to confirm deletion
        function confirmDelete() {
            return confirm("Are you sure you want to delete this item?");
        }
    </script>
    <style>
        /* Add custom styles for the button positioning */
        .add-item-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .add-item-btn:hover {
            background-color: #45a049;
        }

        header {
            position: relative;
            padding: 20px;
            background-color: #f4f4f4;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Manage NonVeg Menu</h1>
        <!-- Redirect to add-nonveg.php when clicked -->
        <a href="add-nonveg.php" class="add-item-btn">Add Item</a> <!-- Add Item button -->
    </header>

    <div class="menu">
        <div class="menu-items">
            <?php
            // Load the menu items from the JSON file
            $menu_file = 'nonveg.json'; 
            if (file_exists($menu_file)) {
                $menu_items = json_decode(file_get_contents($menu_file), true);
            } else {
                $menu_items = [];
            }

            // Handle the delete action
            if (isset($_POST['delete'])) {
                $item_name_to_delete = $_POST['item_name'];
                // Remove the item from the array
                $menu_items = array_filter($menu_items, function($item) use ($item_name_to_delete) {
                    return $item['name'] !== $item_name_to_delete;
                });
                $menu_items = array_values($menu_items);  // Reindex the array after removal

                // Save the updated array back to the JSON file
                file_put_contents($menu_file, json_encode($menu_items, JSON_PRETTY_PRINT));
            }

            // Display the menu items
            foreach ($menu_items as $item) {
                echo "
                    <div class='menu-item'>
                        <img src='{$item['image']}' alt='{$item['name']}' style='width: 100%; height: auto;' />
                        <h3>{$item['name']}</h3>
                        <p>Rs {$item['price']}</p>
                        <form method='POST' action='' onsubmit='return confirmDelete();'>
                            <input type='hidden' name='item_name' value='{$item['name']}' />
                            <input type='hidden' name='item_price' value='{$item['price']}' />
                            
                            <!-- Edit button -->
                            <a href='edit-nonveg.php?name={$item['name']}&price={$item['price']}&image={$item['image']}' class='order-btn'>Edit</a>

                            <!-- Delete button -->
                            <button type='submit' name='delete' class='order-btn'>Delete</button>
                        </form>
                    </div>
                ";
            }
            ?>
        </div>
    </div>
</body>
</html>
