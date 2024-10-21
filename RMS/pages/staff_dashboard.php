<?php
include '../includes/navbar.php'; // Include navigation bar
include '../includes/database.php'; // Include database connection

session_start();

// Check if staff is logged in
if (!isset($_SESSION['staff_id'])) {
    header('location:login.php');
    exit();
}

// Fetch the staff ID from session
$staff_id = $_SESSION['staff_id'];

// Fetch orders assigned to this staff member
$orders_query = $conn->prepare("SELECT o.order_id, o.table_id, o.order_status, o.total_price FROM orders o WHERE o.staff_id = ?");
$orders_query->bind_param('i', $staff_id);
$orders_query->execute();
$orders_result = $orders_query->get_result();

// Calculate total sales made by this staff
$total_sales_query = $conn->prepare("SELECT SUM(o.total_price) AS total_sales FROM orders o WHERE o.staff_id = ? AND o.order_status = 'completed'");
$total_sales_query->bind_param('i', $staff_id);
$total_sales_query->execute();
$total_sales_result = $total_sales_query->get_result();
$total_sales = $total_sales_result->fetch_assoc()['total_sales'] ?? 0; // Handle case where no sales are made yet

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../css/styles3.css"> <!-- Include custom styles if any -->
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            background-image: url('../IMG/background-dashboard.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            font-size: 36px;
            margin-bottom: 40px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .total-sales {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
        }

        .total-sales strong {
            color: #4CAF50;
        }

        h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #4CAF50;
            color: white;
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

        .no-orders {
            text-align: center;
            color: #888;
            font-size: 18px;
        }

        .view-details {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        .view-details:hover {
            text-decoration: underline;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 20px;
            }

            h1 {
                font-size: 30px;
            }

            h2 {
                font-size: 24px;
            }

            th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Staff Dashboard</h1>

        <!-- Display total sales -->
        <div class="total-sales">
            <strong>Total Sales:</strong> ₹<?php echo number_format($total_sales, 2); ?>
        </div>

        <!-- Orders assigned to the logged-in staff -->
        <h2>Your Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Table ID</th>
                    <th>Status</th>
                    <th>Total Amount (₹)</th>
                    <th>Actions</th> <!-- Added Actions column -->
                </tr>
            </thead>
            <tbody>
                <?php if ($orders_result->num_rows > 0): ?>
                    <?php while ($order = $orders_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['table_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                            <td><?php echo number_format($order['total_price'], 2); ?>₹</td>
                            <td>
                                <a href="order_details.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>" class="view-details">View Details</a>
                            </td> <!-- View Details link -->
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-orders">No orders assigned to you yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$orders_query->close();
$total_sales_query->close();
$conn->close();
?>
