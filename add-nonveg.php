<?php
// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

        // Only allow image extensions
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($image_ext), $allowed_exts)) {
            $upload_dir = 'uploads/';
            $new_image_name = time() . '.' . $image_ext;  // Unique name for the image
            move_uploaded_file($image_tmp, $upload_dir . $new_image_name);
        } else {
            echo "Invalid image format.";
        }
    } else {
        echo "Image is required.";
    }

    // Load the existing menu items from the JSON file
    $menu_file = 'nonveg.json';
    $menu_items = file_exists($menu_file) ? json_decode(file_get_contents($menu_file), true) : [];

    // Add the new item to the menu array
    $new_item = [
        'name' => $name,
        'price' => $price,
        'image' => $upload_dir . $new_image_name
    ];
    $menu_items[] = $new_item;

    // Save the updated menu back to the JSON file
    file_put_contents($menu_file, json_encode($menu_items, JSON_PRETTY_PRINT));

    // Redirect back to the manage-nonveg.php page to reflect the changes
    header('Location: manage-nonveg.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Item</title>
    <link rel="stylesheet" href="add-item.css">
</head>
<body>
    <header>
        <h1>Add Food Item</h1>
        <a href="manage-nonveg.php">Back to Menu</a> <!-- Link to manage-nonveg.php -->
    </header>

    <form method="POST" enctype="multipart/form-data">
        <label for="name">Food Item Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" required><br><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <button type="submit">Add Item</button>
    </form>
</body>
</html>
