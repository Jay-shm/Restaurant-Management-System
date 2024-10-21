<?php
include '../includes/navbar.php';
include '../includes/database.php';

// Fetch orders from the orders table
$orders = [];
$order_query = $conn->query("SELECT o.order_id, o.order_status, o.total_price, ti.table_id, ti.table_capacity, o.staff_id FROM orders o INNER JOIN table_info ti ON o.table_id = ti.table_id");
while ($row = $order_query->fetch_assoc()) {
    $orders[] = $row;
}

// Fetch staff from the staff table
$staff = [];
$staff_query = $conn->query("SELECT id, fname, lname, gender, email, mobile, role FROM staff WHERE role != 'admin'");
while ($row = $staff_query->fetch_assoc()) {
    $staff[] = $row;
}

// Handle order status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_orders'])) {
    if (isset($_POST['order_ids'])) {
        $order_ids = $_POST['order_ids']; // Get selected order IDs
        
        // Prepare update statement for orders
        $update_order_stmt = $conn->prepare("UPDATE orders SET order_status = 'completed' WHERE order_id = ?");
        $update_table_stmt = $conn->prepare("UPDATE table_info SET table_status = 'available' WHERE table_id = ?");

        foreach ($order_ids as $order_id) {
            // Update the order status
            $update_order_stmt->bind_param("i", $order_id);
            $update_order_stmt->execute();

            // Get the table ID for the completed order
            $table_query = $conn->prepare("SELECT table_id FROM orders WHERE order_id = ?");
            $table_query->bind_param("i", $order_id);
            $table_query->execute();
            $table_query->bind_result($table_id);
            $table_query->fetch();
            $table_query->close();

            // Update the table status to available
            if ($table_id) {
                $update_table_stmt->bind_param("i", $table_id);
                $update_table_stmt->execute();
            }
        }
        $update_order_stmt->close();
        $update_table_stmt->close();

        echo "<script>alert('Selected orders have been updated to completed!');</script>";
        header("Location: admin.php"); // Page reload to reflect changes
        exit();
    } else {
        echo "<script>alert('No orders selected.');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_staff'])) {
    $staff_id = $_POST['staff_id'];
    $new_fname = $_POST['new_fname'];
    $new_lname = $_POST['new_lname'];
    $new_email = $_POST['new_email'];
    $new_mobile = $_POST['new_mobile'];
    $new_role = $_POST['new_role'];

    $update_staff_stmt = $conn->prepare("UPDATE staff SET fname = ?, lname = ?, email = ?, mobile = ?, role = ? WHERE id = ?");
    $update_staff_stmt->bind_param("sssssi", $new_fname, $new_lname, $new_email, $new_mobile, $new_role, $staff_id);

    if($update_staff_stmt->execute()) {
        echo "<script>alert('Staff details updated successfully!')</script>";
    } else {
        echo "<script>alert('Failed to update the details. Please try again.')</script>";
    }
    $update_staff_stmt->close();
    header("Location: admin.php");
    exit();
}

// Handle staff management (hire/fire)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['manage_staff'])) {
    $action = $_POST['manage_staff']; // Use the button name for hire/fire action

    if ($action == 'hire') {
        // Implement hiring logic (insert staff details into the database)
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $passwords = $_POST['passwords'];
        $mobile = $_POST['mobile'];
        $role = $_POST['role'];
        
        $hire_stmt = $conn->prepare("INSERT INTO staff (fname, lname, gender, email, passwords, mobile, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $hire_stmt->bind_param("sssssis", $fname, $lname, $gender, $email, $passwords, $mobile, $role);
        $hire_stmt->execute();
        $hire_stmt->close();

        echo "<script>alert('Staff member hired successfully!');</script>";
        header("Location: admin.php"); // Page reload to reflect changes
        exit();
    } elseif ($action == 'fire') {
        // Implement firing logic (delete staff from the database)
        $staff_id = $_POST['staff_id'];
        $fire_stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
        $fire_stmt->bind_param("i", $staff_id);
        $fire_stmt->execute();
        $fire_stmt->close();

        echo "<script>alert('Staff member fired successfully!');</script>";
        header("Location: admin.php"); // Page reload to reflect changes
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logo.png" type="image/png">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            background-image: url('../IMG/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }

        .admin-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 36px;
            color: #4CAF50;
        }

        h2 {
            font-size: 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
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
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        .button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            width: 100%;
            margin-top: 15px;
            text-align: center;
        }

        .button:hover {
            background-color: #45a049;
        }

        .form-input, select {
            margin-bottom: 10px;
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-input:focus, select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .manage-staff-form {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .manage-staff-form h3 {
            font-size: 24px;
            color: #4CAF50;
            margin-bottom: 15px;
        }

        .staff-list {
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 28px;
            }

            h2 {
                font-size: 24px;
            }

            th, td {
                font-size: 14px;
            }

            .button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Admin Panel</h1>

        <!-- Order Management Section -->
        <h2>Manage Orders</h2>
        <form method="POST" action="admin.php">
            <table>
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Table</th>
                        <th>Staff</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><input type="checkbox" name="order_ids[]" value="<?= $order['order_id'] ?>"></td>
                            <td><?= $order['order_id'] ?></td>
                            <td><?= $order['order_status'] ?></td>
                            <td><?= $order['total_price'] ?></td>
                            <td><?= $order['table_id'] ?> (Capacity: <?= $order['table_capacity'] ?>)</td>
                            <td><?= $order['staff_id'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" name="update_orders" class="button">Mark Selected Orders as Completed</button>
        </form>

        <!-- Staff Management Section -->
        <h2>Manage Staff</h2>

        <form method="POST" action="admin.php" class="manage-staff-form" id="updateStaffForm">
            <h3>Update Staff</h3>
            <select name="staff_id" class="form-input" id="staffSelect" required>
                <option value="" disabled selected>Select Staff to Update</option>
                <?php foreach ($staff as $person): ?>
                    <option value="<?= $person['id'] ?>">
                        <?= $person['fname'] . " " . $person['lname'] ?> - <?= ucfirst($person['role']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <!-- The following fields will be dynamically updated with the selected staff's data -->
            <input type="text" name="new_fname" class="form-input" id="new_fname" placeholder="New First Name" required>
            <input type="text" name="new_lname" class="form-input" id="new_lname" placeholder="New Last Name" required>
            <input type="email" name="new_email" class="form-input" id="new_email" placeholder="New Email" required>
            <input type="text" name="new_mobile" class="form-input" id="new_mobile" placeholder="New Mobile" required>
            <select name="new_role" class="form-input" id="new_role" required>
                <option value="staff">Staff</option>
                <option value="chef">Chef</option>
                <option value="waiter">Waiter</option>
                <option value="manager">Manager</option>
                <option value="cashier">Cashier</option>
            </select>
            <button type="submit" name="update_staff" class="button">Update Staff</button>
        </form>

        <!-- Form for hiring/firing staff -->
        <form method="POST" action="admin.php" class="manage-staff-form">
            <h3>Hire Staff</h3>
            <input type="text" name="fname" class="form-input" placeholder="First Name" required>
            <input type="text" name="lname" class="form-input" placeholder="Last Name" required>
            <select name="gender" class="form-input" required>
                <option value="" disabled selected>Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <input type="email" name="email" class="form-input" placeholder="Email" required>
            <input type="password" name="passwords" class="form-input" placeholder="Password" required>
            <input type="text" name="mobile" class="form-input" placeholder="Mobile" required>
            <select name="role" class="form-input" required>
                <option value="" disabled selected>Select Role</option>
                <option value="staff">Staff</option>
                <option value="chef">Chef</option>
                <option value="waiter">Waiter</option>
                <option value="manager">Manager</option>
                <option value="cashier">Cashier</option>
            </select>
            <button type="submit" name="manage_staff" value="hire" class="button">Hire Staff</button>
        </form>

        <form method="POST" action="admin.php" class="manage-staff-form">
            <h3>Fire Staff</h3>
            <select name="staff_id" class="form-input" required>
                <option value="" disabled selected>Select Staff to Fire</option>
                <?php foreach ($staff as $person): ?>
                    <option value="<?= $person['id'] ?>">
                        <?= $person['fname'] . " " . $person['lname'] ?> - <?= ucfirst($person['role']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="manage_staff" value="fire" class="button">Fire Staff</button>
        </form>

        <!-- Staff List -->
        <h2>Current Staff List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff as $person): ?>
                    <tr>
                        <td><?= $person['id'] ?></td>
                        <td><?= $person['fname'] ?></td>
                        <td><?= $person['lname'] ?></td>
                        <td><?= ucfirst($person['gender']) ?></td>
                        <td><?= $person['email'] ?></td>
                        <td><?= $person['mobile'] ?></td>
                        <td><?= ucfirst($person['role']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    // When the staff is selected, load the details dynamically
    document.getElementById('staffSelect').addEventListener('change', function() {
        var staffId = this.value;

        if (staffId) {
            // Send AJAX request to fetch staff details
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../process/get_staff_details.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);

                    // Populate the form fields with the retrieved data
                    document.getElementById('new_fname').value = response.fname;
                    document.getElementById('new_lname').value = response.lname;
                    document.getElementById('new_email').value = response.email;
                    document.getElementById('new_mobile').value = response.mobile;
                    document.getElementById('new_role').value = response.role;
                }
            };
            xhr.send('staff_id=' + staffId);
        }
    });
    </script>
</body>
</html>
