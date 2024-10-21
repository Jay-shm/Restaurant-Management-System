<?php 
include '../includes/navbar.php'; 
include '../includes/database.php'; 

// Fetch available tables from the table_info database
$tables = [];
$table_query = $conn->query("SELECT table_id, table_capacity, table_status FROM table_info WHERE table_status = 'available'"); // Adjust this condition based on your business logic
while ($row = $table_query->fetch_assoc()) {
    $tables[] = $row; // Store the whole row for more info
}

// Calculate total price
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// Calculate tax (assuming a tax rate of 10% for example)
$tax = $total_price * 0.10; // You can adjust the tax rate as needed
$total_with_tax = $total_price + $tax;

// Handle order finalization
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    $table_id = filter_input(INPUT_POST, 'table_id', FILTER_VALIDATE_INT);
    if ($table_id === false) {
        echo "<script>alert('Invalid table selection. Please select a valid table.');</script>";
        exit; // Prevent further execution
    }
    
    // Prepare to insert order details into the database
    $staff_id = $_SESSION['staff_id']; // Assuming staff_id is stored in the session upon login
    $order_status = 'Pending'; // Initial status of the order
    $order_date = date('Y-m-d H:i:s'); // Current date and time

    // Insert order into the orders table, including total price
    $stmt = $conn->prepare("INSERT INTO orders (TAX, order_status, table_id, staff_id, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("dssis", $tax, $order_status, $table_id, $staff_id, $total_with_tax);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // Get the last inserted order ID
        
        // Prepare to insert items into order_menu
        $insert_menu_stmt = $conn->prepare("INSERT INTO order_menu (order_id, menu_id, quantity) VALUES (?, ?, ?)");
        
        foreach ($_SESSION['cart'] as $item) {
            // Fetch the menu_id from the menu table based on the item name
            $item_name = $item['name']; // Assuming 'name' holds the item name
            $menu_query = $conn->prepare("SELECT menu_id FROM menu_item WHERE name = ? LIMIT 1");
            $menu_query->bind_param("s", $item_name);
            $menu_query->execute();
            $menu_query->bind_result($menu_id);
            $menu_query->fetch();
            $menu_query->close(); // Close the prepared statement for menu

            if ($menu_id) { // Check if a valid menu_id was retrieved
                $quantity = $item['quantity'];
                $insert_menu_stmt->bind_param("iii", $order_id, $menu_id, $quantity);
                $insert_menu_stmt->execute();
            } else {
                // Handle case where menu_id could not be found
                echo "<script>alert('Menu item not found: $item_name');</script>";
            }
        }
        $insert_menu_stmt->close(); // Close the prepared statement for order_menu

        // Update the selected table's status to unavailable
        $update_table_stmt = $conn->prepare("UPDATE table_info SET table_status = 'unavailable' WHERE table_id = ?");
        $update_table_stmt->bind_param("i", $table_id);
        $update_table_stmt->execute();
        $update_table_stmt->close(); // Close the prepared statement for updating table status

        // Order saved successfully
        unset($_SESSION['cart']); // Clear the cart after finalizing the order
        echo "<script>alert('Order has been finalized successfully!'); window.location.href='staff_menu.php';</script>";
    } else {
        // Error handling
        echo "<script>alert('Failed to finalize the order. Please try again.');</script>";
    }
    
    $stmt->close(); // Close the prepared statement for orders
    exit; // Redirect after finalizing
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles3.css">
    <title>Order Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .order-summary-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .finalize-order-btn, .go-to-cart-btn, .go-to-menu-btn {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #4CAF50; /* Green */
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .available-tables {
            margin-top: 20px;
            text-align: center;
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .table-list {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            transition: transform 0.3s ease;
        }

        .table-list:hover {
            transform: scale(1.05);
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="order-summary-container">
        <h1>Order Summary</h1>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr class="order-item">
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['price']); ?>₹</td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($item['price'] * $item['quantity']); ?>₹</td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3">Subtotal</td>
                    <td><?php echo htmlspecialchars($total_price); ?>₹</td>
                </tr>
                <tr class="tax-row">
                    <td colspan="3">Tax (10%)</td>
                    <td><?php echo htmlspecialchars($tax); ?>₹</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Total</td>
                    <td><?php echo htmlspecialchars($total_with_tax); ?>₹</td>
                </tr>
            </tbody>
        </table>

        <form method="POST" action="order_summary.php">
            <label for="table_id">Select Table ID:</label>
            <select name="table_id" id="table_id" required>
                <?php foreach ($tables as $table): ?>
                    <option value="<?php echo htmlspecialchars($table['table_id']); ?>">
                        Table <?php echo htmlspecialchars($table['table_id']); ?> (Capacity: <?php echo htmlspecialchars($table['table_capacity']); ?>, Status: <?php echo htmlspecialchars($table['table_status']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="finalize-order-btn">Finalize Order</button>
        </form>
        <a href="cart.php" class="go-to-cart-btn">Go to Cart</a>
        <a href="staff_menu.php" class="go-to-menu-btn">Go to Menu</a>
    </div>

    <div class="available-tables">
        <h2>Available Tables</h2>
        <div class="table-list">
            <ul>
                <?php foreach ($tables as $table): ?>
                    <li>Table ID: <?php echo htmlspecialchars($table['table_id']); ?> (Capacity: <?php echo htmlspecialchars($table['table_capacity']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
