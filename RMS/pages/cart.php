<?php 
include '../includes/navbar.php'; 
include '../includes/database.php'; 

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize cart if it doesn't exist
}

// Handle item removal
if (isset($_GET['remove'])) {
    $item_id = $_GET['remove'];
    unset($_SESSION['cart'][$item_id]);
}

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'], $_POST['quantity'])) {
    $item_id = $_POST['item_id'];
    $quantity = (int)$_POST['quantity'];

    // Update the quantity in the cart if the item exists
    if (isset($_SESSION['cart'][$item_id])) {
        if ($quantity > 0) { // Ensure quantity is positive
            $_SESSION['cart'][$item_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$item_id]); // Remove item if quantity is zero or less
        }
    }
}

// Calculate total price
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles3.css">
    <title>Your Cart</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            background-image: url('../IMG/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .cart-container {
            max-width: 1000px;
            margin: 80px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 32px;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Button styles */
        .update-btn, .remove-btn, .finalize-order-btn, .go-to-menu-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px 0;
            transition: background-color 0.3s ease;
        }

        .update-btn {
            background-color: #4CAF50;
            color: white;
        }

        .update-btn:hover {
            background-color: #45a049;
        }

        .remove-btn {
            background-color: #f44336;
            color: white;
        }

        .remove-btn:hover {
            background-color: #d32f2f;
        }

        .finalize-order-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
        }

        .finalize-order-btn:hover {
            background-color: #0056b3;
        }

        .go-to-menu-btn {
            background-color: #4CAF50;
            color: white;
        }

        .go-to-menu-btn:hover {
            background-color: #45a049;
        }

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            h1 {
                font-size: 24px;
            }

            th, td {
                font-size: 14px;
            }

            .update-btn, .remove-btn, .finalize-order-btn, .go-to-menu-btn {
                font-size: 12px;
            }

            input[type="number"] {
                width: 50px;
            }
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h1>Your Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
        <a href="staff_menu.php" class="go-to-menu-btn">Go to Menu</a>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item_id => $item): ?>
                    <tr class="cart-item">
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?>₹</td>
                        <td>
                            <form method="POST" action="cart.php">
                                <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                <input type="number" name="quantity" min="1" value="<?php echo $item['quantity']; ?>" required>
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                        </td>
                        <td><?php echo htmlspecialchars($item['price'] * $item['quantity']); ?>₹</td>
                        <td>
                            <a href="cart.php?remove=<?php echo $item_id; ?>" class="remove-btn">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td><?php echo htmlspecialchars($total_price); ?>₹</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <a href="order_summary.php" class="finalize-order-btn">Finalize Order</a>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
