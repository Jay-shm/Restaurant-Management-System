<?php
include '../includes/database.php';
include '../includes/navbar.php';

// Check if the user is not an admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied. Only admin can access this page.'); window.location.href = '../pages/login.php';</script>";
    exit();
}

// Fetch current menu items from the menu_item table
$menu_items = [];
$menu_query = $conn->query("SELECT m.menu_id, m.name, m.price, c.name AS category_name, c.id AS category_id, m.menu_status FROM menu_item m INNER JOIN category c ON m.category_id = c.id");
while ($row = $menu_query->fetch_assoc()) {
    $menu_items[] = $row;
}

// Fetch categories from the category table
$categories = [];
$category_query = $conn->query("SELECT id, name FROM category");
while ($row = $category_query->fetch_assoc()) {
    $categories[] = $row;
}

// Handle new item addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    $category_id = $_POST['category_id'];

    // Insert new menu item into the database
    $add_item_stmt = $conn->prepare("INSERT INTO menu_item (name, price, category_id, menu_status) VALUES (?, ?, ?, 'Available')");
    $add_item_stmt->bind_param("sdi", $item_name, $item_price, $category_id);
    if ($add_item_stmt->execute()) {
        echo "<script>alert('New menu item added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add menu item. Please try again.');</script>";
    }
    $add_item_stmt->close();
    header("Location: update_menu.php");
    exit();
}

// Handle item updates (all attributes)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_item'])) {
    $menu_id = $_POST['menu_id'];
    $new_name = $_POST['new_name'];
    $new_price = $_POST['new_price'];
    $new_category = $_POST['new_category'];
    $new_status = $_POST['new_status'];

    // Update menu item in the database
    $update_item_stmt = $conn->prepare("UPDATE menu_item SET name = ?, price = ?, category_id = ?, menu_status = ? WHERE menu_id = ?");
    $update_item_stmt->bind_param("sdisi", $new_name, $new_price, $new_category, $new_status, $menu_id);
    if ($update_item_stmt->execute()) {
        echo "<script>alert('Menu item updated successfully!');</script>";
    } else {
        echo "<script>alert('Failed to update menu item. Please try again.');</script>";
    }
    $update_item_stmt->close();
    header("Location: update_menu.php");
    exit();
}

// Handle item deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_item'])) {
    $menu_id = $_POST['menu_id'];

    // Delete menu item from the database
    $delete_item_stmt = $conn->prepare("DELETE FROM menu_item WHERE menu_id = ?");
    $delete_item_stmt->bind_param("i", $menu_id);
    if ($delete_item_stmt->execute()) {
        echo "<script>alert('Menu item deleted successfully!');</script>";
    } else {
        echo "<script>alert('Failed to delete menu item. Please try again.');</script>";
    }
    $delete_item_stmt->close();
    header("Location: update_menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles6.css">
    <title>Update Menu</title>
    <style>
        /* Same styling as before */
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Update Menu</h1>

        <!-- Current Menu Items Section -->
        <h2>Current Menu Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Update/Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menu_items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['menu_id']); ?></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?>₹</td>
                        <td><?php echo htmlspecialchars($item['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($item['menu_status']); ?></td>
                        <td>
                            <!-- Update Form for each item -->
                            <form method="POST" action="update_menu.php" style="display: inline;">
                                <input type="hidden" name="menu_id" value="<?php echo htmlspecialchars($item['menu_id']); ?>">

                                <!-- Editable fields -->
                                <input type="text" name="new_name" value="<?php echo htmlspecialchars($item['name']); ?>" required class="form-input" style="width: 100px;">
                                <input type="number" name="new_price" value="<?php echo htmlspecialchars($item['price']); ?>" required min="0" class="form-input" style="width: 80px;">
                                <select name="new_category" required class="form-input" style="width: 120px;">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category['id']); ?>" <?php if ($item['category_id'] == $category['id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="new_status" required class="form-input" style="width: 120px;">
                                    <option value="Available" <?php if ($item['menu_status'] === 'Available') echo 'selected'; ?>>Available</option>
                                    <option value="Unavailable" <?php if ($item['menu_status'] === 'Unavailable') echo 'selected'; ?>>Unavailable</option>
                                </select>

                                <!-- Submit buttons -->
                                <button type="submit" name="update_item" class="button">Update</button>
                            </form>

                            <!-- Delete Form for each item -->
                            <form method="POST" action="update_menu.php" style="display: inline;">
                                <input type="hidden" name="menu_id" value="<?php echo htmlspecialchars($item['menu_id']); ?>">
                                <button type="submit" name="delete_item" class="button" style="background-color: #f44336;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add New Item Section -->
        <h2>Add New Menu Item</h2>
        <form method="POST" action="update_menu.php" class="add-item-form">
            <input type="text" name="item_name" placeholder="Item Name" required class="form-input">
            <input type="number" name="item_price" placeholder="Item Price" required min="0" class="form-input">
            <select name="category_id" required class="form-input">
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['id']); ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="add_item" class="button">Add Item</button>
        </form>
    </div>
</body>
</html>
