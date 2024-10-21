<?php
session_start();
include '../includes/database.php'; 

// Check if the cart session exists, if not, initialize it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'], $_POST['quantity'])) {
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];

    // Fetch item details from the database
    $query = $conn->prepare("SELECT name, price FROM menu_item WHERE menu_id = ?");
    $query->bind_param("i", $item_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();

        // Check if the item is already in the cart
        if (isset($_SESSION['cart'][$item_id])) {
            // Update quantity if item already exists in the cart
            $_SESSION['cart'][$item_id]['quantity'] += $quantity;
        } else {
            // Add new item to cart
            $_SESSION['cart'][$item_id] = [
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $quantity
            ];
        }

        // Redirect to the cart page
        header('Location: ../pages/staff_menu.php');
        exit();
    } else {
        // Item not found in the database
        $_SESSION['error'] = "Item not found.";
        header('Location: ../pages/staff_menu.php');
        exit();
    }
} else {
    // Invalid request
    $_SESSION['error'] = "Invalid request.";
    header('Location: ../pages/staff_menu.php');
    exit();
}

?>
