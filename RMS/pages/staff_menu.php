<?php
include '../includes/navbar.php'; // This will include the existing navbar without changes
include '../includes/database.php'; 

$staff_id = $_SESSION['staff_id'];
$role = $_SESSION['role'];

// Fetch menu items from the database
$query = "SELECT menu_id, name, price, menu_status FROM menu_item WHERE menu_status = 'available'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles2.css">
    <title>Staff Menu</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            background-image: url('../IMG/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .menu-container {
            max-width: 1000px;
            margin: 80px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .menu-items {
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
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
        .add-to-cart-btn {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #45a049;
        }

        /* Admin action section */
        .admin-actions {
            text-align: center;
            margin-top: 20px;
        }

        .admin-actions a {
            display: inline-block;
            margin: 10px 15px;
            padding: 12px 25px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .admin-actions a:hover {
            background-color: #d73d31;
        }

        /* General link styles */
        #l {
            display: inline-block;
            margin: 10px 15px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        #l:hover {
            background-color: #0069d9;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            th, td {
                font-size: 14px;
            }

            .add-to-cart-btn, .admin-actions a, a {
                padding: 8px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="menu-container">
    <h1>Staff Menu</h1>

    <div class="menu-items">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="menu-item">
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td>₹<?php echo htmlspecialchars($row['price']); ?></td>
                            <td>
                                <form method="POST" action="../process/add_to_cart.php">
                                    <input type="hidden" name="item_id" value="<?php echo $row['menu_id']; ?>">
                                    <input type="number" name="quantity" min="1" value="1" required style="width: 50px; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                                    <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No menu items available.</p>
        <?php endif; ?>
    </div>

    <?php if ($role == 'admin'): ?>
        <div class="admin-actions">
            <h2>Admin Actions</h2>
            <a id="l" href="admin.php">Hire Staff</a>
            <a id="l" href="admin.php">Fire Staff</a>
            <a id="l" href="order_history.php">View Orders</a>
        </div>
    <?php endif; ?>

    <a id="l" href="cart.php">View Cart</a>
    <a id="l" href="../process/logout.php">Logout</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
