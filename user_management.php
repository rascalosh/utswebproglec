<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';
$showModal = false; 

if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];


    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $message = "User deleted successfully!";
            $showModal = true; 
        } else {
            $message = "Error deleting User: " . $stmt->error;
            $showModal = true; 
        }
    } else {
        $message = "User not found.";
        $showModal = true;
    }

    $stmt->close();
}


$stmt = $conn->prepare("SELECT u.id, u.username, u.email, GROUP_CONCAT(e.name SEPARATOR ', ') AS registered_events
                        FROM users u
                        LEFT JOIN registrations r ON u.id = r.user_id
                        LEFT JOIN events e ON r.event_id = e.id
                        GROUP BY u.id");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }
    </script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl">
        <h1 class="text-2xl font-bold mb-4 text-center">Registered Users</h1>

        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left text-gray-600">Username</th>
                    <th class="py-2 px-4 border-b text-left text-gray-600">Email</th>
                    <th class="py-2 px-4 border-b text-left text-gray-600">Registered Events</th>
                    <th class="py-2 px-4 border-b text-left text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border-b"><?php echo $row['username']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $row['email']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $row['registered_events']; ?></td>
                        <td class="py-2 px-4 border-b">
                        <form action="user_management.php" method="get" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Delete</button>
                        </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php if ($showModal): ?>
        <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-80">
                <h2 class="text-lg font-bold mb-2">Notification</h2>
                <p class="mb-4"><?php echo $message; ?></p>
                <div class="flex justify-end">
                    <button onclick="closeModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Close</button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="mt-4 text-center">
            <a href="indexadmin.php" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Back</a>
        </div>
    </div>
</body>
</html>
