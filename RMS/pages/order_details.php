<?php
session_start();
include '../includes/database.php'; // Include database connection
include '../includes/navbar.php'; // Include navigation bar

// Check if the user is logged in
if (!isset($_SESSION['staff_id'])) {
    header('location:login.php'); // Redirect to login if not logged in
    exit();
}

// Initialize variables
$orderItems = [];

// Get the order ID from the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Query to get order details
    $sql = "SELECT oi.menu_id, oi.quantity, i.price, i.name
            FROM order_menu oi
            INNER JOIN menu_item i ON oi.menu_id = i.menu_id
            WHERE oi.order_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        // Fetch all order items
        while ($row = $result->fetch_assoc()) {
            $orderItems[] = $row;
        }
    } else {
        echo "No items found for this order.";
    }

    $stmt->close();
} else {
    echo "No order ID provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logo.png" type="image/png">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            background-image: url('../IMG/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            color: #333;
        }

        .container {
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
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #000;
        }

        .back-button {
            display: block;
            text-align: center;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Details</h1>

        <?php if (!empty($orderItems)) { ?>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No items found for this order.</p>
        <?php } ?>

        <a href="admin.php" class="back-button">Back to Admin Panel</a>
        <a href="staff_dashboard.php" class="back-button">Back to Staff Dashboard</a>
    </div>
</body>
</html>
