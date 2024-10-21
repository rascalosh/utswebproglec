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
    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>

</head>
<body class="font-[Poppins] bg-gradient-to-t from-[#fbc2eb] to-[#a6c1ee] min-h-screen text-gray-900 flex flex-col justify-between">
<header class="bg-[#FFE1FF] py-3 fixed top-0 left-0 w-full z-50">
    <nav class="flex justify-between items-center w-[92%] mx-auto">
        <div>
            <img class="w-20" src="assets/baileo3.png" alt="...">
        </div>
        <div class="nav-links duration-500 md:static absolute bg-[#FFE1FF] md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5">
            <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                <li>
                    <a class="text-gray-700 hover:text-pink-900 transition duration-100 ease-in-out" href="indexadmin.php">Dashboard</a>
                </li>
                <li>
                    <a class="text-gray-700 hover:text-pink-900 transition duration-100 ease-in-out" href="create_event.php">Create Event</a>
                </li>
                <li>
                    <a class="text-gray-700 hover:text-pink-900 transition duration-100 ease-in-out" href="user_management.php">Manage Users</a>
                </li>
            </ul>
        </div>
        <div class="flex items-center gap-6"> 
            <a href="/eventuser/logout.php" class="bg-[#7E60BF] text-white px-5 py-2 rounded-full hover:bg-[#CDC1FF]">Log Out</a>
            <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-2xl cursor-pointer md:hidden"></ion-icon>
        </div>
    </nav>
</header>
    <script>
        const navLinks = document.querySelector('.nav-links')
        function onToggleMenu(e){
            e.name = e.name === 'menu' ? 'close' : 'menu'
            navLinks.classList.toggle('top-[9%]')
        }
    </script>
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl mx-auto mt-20">
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
                        <?php if($row['username'] == 'admin'): ?>
                            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 cursor-not-allowed">Delete</button>
                        <?php else :?>
                        <form action="user_management.php" method="get" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Delete</button>
                        </form>
                        <?php endif;?>
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
    <footer class="bg-[#FFE1FF] text-gray-700 py-6 mt-4">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-center md:text-left">
                <p class="text-sm">&copy; <?php echo date("Y"); ?> Baileo Event Organizer. All rights reserved.</p>
            </div>

            <div class="flex gap-4 mt-4 md:mt-0">
                <a href="#" class="text-gray-600 hover:text-gray-900"><ion-icon name="logo-facebook" class="text-xl"></ion-icon></a>
                <a href="#" class="text-gray-600 hover:text-gray-900"><ion-icon name="logo-twitter" class="text-xl"></ion-icon></a>
                <a href="#" class="text-gray-600 hover:text-gray-900"><ion-icon name="logo-instagram" class="text-xl"></ion-icon></a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
