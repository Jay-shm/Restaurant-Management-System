<?php
session_start();

if (!isset($_SESSION['staff_id'])) {
    header('location:login.php');
    exit();
}

$role = $_SESSION['role'];

?>

<nav>
    <div class="logo">
        <a href="../pages/login.php"><img src="../IMG/logo.png" alt="Logo"></a>
    </div>

    <div class="hamburger" onclick="openNav()">&#9776;</div> <!-- Hamburger icon -->
</nav>

<!-- Slide-in Menu -->
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="<?php echo $role == 'admin' ? 'admin.php' : 'staff_dashboard.php'; ?>">Home</a> <!-- Updated Home link -->
    <a href="staff_menu.php">Menu</a>
    <a href="cart.php">Cart</a>

    <?php if ($role == 'admin') { ?>
        <a href="update_menu.php">Update Menu</a>
        <a href="order_history.php">Order History</a>
    <?php } ?>
    <a href="../process/logout.php">Logout</a>
</div>

<style>
    /* Basic Navbar */
    nav {
        background-color: #AAA;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed; /* Make the navbar fixed */
        top: 0;
        width: 100%;
        z-index: 1000; /* Ensure it is on top of other elements */
    }

    .logo img {
        height: 50px;
        width: auto;
    }

    .hamburger {
        font-size: 30px;
        cursor: pointer;
        color: white;
        background-color: #444;
        padding: 5px 10px;
        border-radius: 5px;
    }

    /* Adjust the body to prevent content from hiding behind the navbar */
    body {
        padding-top: 60px; /* Give space for the fixed navbar */
    }

    /* Slide-in Menu (Sidenav) */
    .sidenav {
        height: 100%;
        width: 0;
        position: fixed;
        top: 0;
        right: 0;
        background-color: #444;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    .sidenav a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 25px;
        color: white;
        display: block;
        transition: 0.3s;
    }

    .sidenav a:hover {
        background-color: #555;
    }

    .sidenav .closebtn {
        position: absolute;
        top: 0;
        right: 20px;
        font-size: 40px;
        margin-left: 50px;
    }
</style>

<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
</script>
