<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Failed to delete user.";
    }

    header("Location: user_management.php");
    exit();
}
?>
