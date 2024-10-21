<?php
session_start(); // Start the session
include '../includes/database.php'; // Include database connection
include '../includes/navbar.php'; // Include navigation bar

// Check if the user is logged in and is an admin
if (!isset($_SESSION['staff_id']) || $_SESSION['role'] !== 'admin') {
    header('location:login.php'); // Redirect to login if not logged in or not an admin
    exit();
}

// Initialize variables
$totalTax = 0;
$totalEarnings = 0;
$orderHistory = [];
$ordersPerPage = 25; // Number of orders to display per page

// Get the current page from the URL, if not present default to 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $ordersPerPage;

// Query to get total number of orders
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders";
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalOrders = $totalOrdersResult->fetch_assoc()['total_orders'];

// Calculate total pages
$totalPages = ceil($totalOrders / $ordersPerPage);

// Query to get order history with pagination
$sql = "SELECT * FROM orders LIMIT $ordersPerPage OFFSET $offset";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch all orders
    while ($row = $result->fetch_assoc()) {
        $orderHistory[] = $row;
        $totalEarnings += $row['total_price']; // Assuming there is a total_price field
        $totalTax += $row['TAX']; // Assuming there is a tax field
    }
} else {
    echo "No orders found.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* General page styling */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            background-image: url('../IMG/background.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        .container {
            max-width: 1000px;
            margin: 80px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        h1 {
            text-align: center;
            color: #4CAF50;
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f7fa; /* Light teal background on hover */
            transition: background-color 0.3s ease;
        }

        .totals {
            text-align: center;
            margin-top: 20px;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin: 10px 0;
            font-weight: bold;
        }

        .totals h2 span {
            color: #4CAF50;
            font-weight: bold;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            margin: 5px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .view-details {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        .view-details:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            table, th, td {
                font-size: 14px;
            }

            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Order History</h1>

    <?php if (!empty($orderHistory)) { ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Price</th>
                    <th>Tax</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Actions</th> <!-- Added Actions column -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderHistory as $order) { ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                        <td>₹<?php echo number_format($order['TAX'], 2); ?></td>
                        <td><?php echo date("d-m-Y", strtotime($order['order_date'])); ?></td>
                        <td><?php echo ucfirst($order['order_status']); ?></td>
                        <td>
                            <a href="order_details.php?order_id=<?php echo $order['order_id']; ?>" class="view-details">View Details</a>
                        </td> <!-- View Details link -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="totals">
            <h2>Total Earnings: <span>₹<?php echo number_format($totalEarnings, 2); ?></span></h2>
            <h2>Total Tax: <span>₹<?php echo number_format($totalTax, 2); ?></span></h2>
        </div>

        <div class="pagination">
            <?php if ($page > 1) { ?>
                <a href="?page=<?php echo $page - 1; ?>">Previous</a>
            <?php } ?>
            
            <?php if ($page < $totalPages) { ?>
                <a href="?page=<?php echo $page + 1; ?>">Next</a>
            <?php } ?>
        </div>

    <?php } else { ?>
        <p>No order history available.</p>
    <?php } ?>
</div>

</body>
</html>
