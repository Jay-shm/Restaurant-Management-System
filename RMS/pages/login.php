<?php
include '../includes/database.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id, passwords, role FROM staff WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password == $row['passwords']) {
            $_SESSION['staff_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            if ($_SESSION['role'] == 'admin') {
                header('location:admin.php');
            } else {
                header('location:staff_menu.php');
            }
            exit();
        } else {
            $_SESSION['error_message'] = 'Invalid password';
        }
    } else {
        $_SESSION['error_message'] = 'Invalid email';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../IMG/logo.png" type="image/png">
    <link rel="stylesheet" href="../css/styles.css"> <!-- Make sure you have your CSS linked -->
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
        <div class="logo">
            <img src="../IMG/logo.png" alt="Logo"> <!-- Ensure the logo image path is correct -->
        </div>
            <h2>Login</h2>

            <!-- Display error message if it exists -->
            <?php if (isset($_SESSION['error_message'])) { ?>
                <p class="error-message"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
            <?php } ?>

            <form method="POST" action="">
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
