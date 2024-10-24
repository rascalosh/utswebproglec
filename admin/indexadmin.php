<?php
session_start();
require_once 'koneksi.php'; 


$message = '';
$showModal = false; 

if (isset($_GET['delete_id'])) {
    $event_id = $_GET['delete_id'];


    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $event_id);

        if ($stmt->execute()) {
            $message = "Event deleted successfully!";
            $showModal = true; 
        } else {
            $message = "Error deleting event: " . $stmt->error;
            $showModal = true; 
        }
    } else {
        $message = "Event not found.";
        $showModal = true;
    }

    $stmt->close();
}

$total_events_query = "SELECT COUNT(*) AS total FROM events";
$total_result = $conn->query($total_events_query);
$total_row = $total_result->fetch_assoc();
$total_events = $total_row['total'];


if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$results_per_page = 5;


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;


$total_query = "SELECT COUNT(*) AS total FROM events";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_events = $total_row['total'];
if($total_events == 0){
    $total_pages = 1;
} else
$total_pages = ceil($total_events / $results_per_page);


$query = "
    SELECT events.id, events.name, events.date, events.time, events.location, events.status,
           events.image_path, events.banner_path, events.max_participants,
           COUNT(registrations.id) AS total_registrants
    FROM events
    LEFT JOIN registrations ON events.id = registrations.event_id
    GROUP BY events.id
    LIMIT $start_from, $results_per_page
";

$result = $conn->query($query);

if (!$result) {
    echo "Error: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
<body class="font-[Poppins] bg-gradient-to-t from-[#E0EAF9] to-[#F4F7FC] min-h-screen text-gray-900 flex flex-col">
           <!-- Header Template Ke -->
<header class="bg-[#3A3D99] py-3 shadow-lg mb-10">
    <nav class="flex justify-between items-center w-[92%] mx-auto">
        <div>
            <a href="indexadmin.php"><img class="w-20" src="assets/baileo3.png" alt="..."></a>
        </div>
        <div class="nav-links duration-500 md:static absolute bg-[#3A3D99] md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5"> <!-- Changed to solid blue -->
            <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="indexadmin.php">Dashboard</a>
                </li>
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="create_event.php">Create Event</a>
                </li>
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="user_management.php">Manage Users</a>
                </li>
            </ul>
        </div>

        <div class="flex items-center gap-6"> 
            <a href="../logout.php" class="bg-gradient-to-r from-[#243c9a] via-[#7e22ce] to-[#ec4899] text-white px-6 py-3 rounded-full shadow-md text-lg font-bold tracking-wide uppercase transition-transform transform hover:scale-105 hover:shadow-lg hover:from-[#1e3a8a] hover:via-[#6b21a8] hover:to-[#db2777] duration-300 ease-in-out">
                Log Out
            </a>
            <ion-icon onclick="onToggleMenu(this)" name="menu" class="text-2xl text-white cursor-pointer md:hidden"></ion-icon>
        </div>
    </nav>
</header>
    <script>
        const navLinks = document.querySelector('.nav-links')
        function onToggleMenu(e){
            e.name = e.name === 'menu' ? 'close' : 'menu'
            navLinks.classList.toggle('top-[9%]')
            navLinks.classList.toggle('z-50')
        }
    </script>
    <div class="container mx-auto px-4">
        <div class="text-center my-8">
            <h2 class="text-3xl font-bold text-blue-900 mb-2 flex items-center justify-center">
                <ion-icon name="calendar" class="mr-2 text-xl"></ion-icon>
                Available Events
            </h2>
            <p class="text-gray-600 mb-4">Manage and Oversee Events</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                <thead>
                    <tr class="bg-gradient-to-r from-purple-500 to-purple-300 text-left text-gray-800 uppercase text-sm leading-normal">
                        <th class="py-3 px-6">Event Name</th>
                        <th class="py-3 px-6">Date</th>
                        <th class="py-3 px-6">Time</th>
                        <th class="py-3 px-6">Location</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Total Registrants</th>
                        <th class="py-3 px-6">Image</th>
                        <th class="py-3 px-6">Banner</th>
                        <th class="py-3 px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($event = $result->fetch_assoc()) : ?>
                            <tr class="border-b border-gray-200 hover:bg-purple-50 transition duration-150 ease-in-out">
                                <td class="py-3 px-6"><?php echo htmlspecialchars($event['name']); ?></td>
                                <td class="py-3 px-6"><?php echo htmlspecialchars($event['date']); ?></td>
                                <td class="py-3 px-6"><?php echo htmlspecialchars($event['time']); ?></td>
                                <td class="py-3 px-6"><?php echo htmlspecialchars($event['location']); ?></td>
                                <td class="py-3 px-6"><?php echo htmlspecialchars($event['status']); ?></td>
                                <td class="py-3 px-6"><?php echo htmlspecialchars($event['total_registrants']) . '/' . htmlspecialchars($event['max_participants']); ?></td>
                                <td class="py-3 px-6">
                                    <img src="<?php echo htmlspecialchars($event['image_path']); ?>" class="w-24 h-auto rounded-lg shadow-sm" alt="Event Image">
                                </td>
                                <td class="py-3 px-6">
                                    <img src="<?php echo htmlspecialchars($event['banner_path']); ?>" class="w-24 h-auto rounded-lg shadow-sm" alt="Event Banner">
                                </td>
                                <td class="py-3 px-6">
                                    <div class="flex items-center space-x-3">
                                        <form action="edit_event.php" method="get" class="inline">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                            <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition duration-150">
                                                Edit
                                            </button>
                                        </form>
                                        <form action="indexadmin.php" method="get" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                            <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-150">Delete</button>
                                        </form>
                                        <form action="view_participant.php" method="get" class="inline">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-150">
                                                View Participant
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-500">No events found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
</div>
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
        <div class="mt-4 flex justify-between items-center">
            <?php if ($page > 1 && $total_pages > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-lg">
                    Previous
                </a>
            <?php else: ?>
                <span class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg cursor-not-allowed">Previous</span>
            <?php endif; ?>

            <span class="text-gray-800 font-semibold">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>

            <?php if ($page < $total_pages && $total_pages > 1): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-lg transition duration-300 transform hover:scale-105 hover:shadow-lg">
                    Next
                </a>
            <?php else: ?>
                <span class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg cursor-not-allowed">Next</span>
            <?php endif; ?>
        </div>
        <div class="flex-col space-y-1 mt-5">
            <div class="mb-5">
                
            </div>
        </div>
    </div>
<!-- Footer -->
    <footer class="bg-[#3A3D99] text-gray-100 py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left">
                    <p class="text-sm">&copy; <?php echo date("Y"); ?> Baileo Event Organizer. All rights reserved.</p>
                </div>

                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-300 hover:text-white"><ion-icon name="logo-facebook" class="text-xl"></ion-icon></a>
                    <a href="#" class="text-gray-300 hover:text-white"><ion-icon name="logo-twitter" class="text-xl"></ion-icon></a>
                    <a href="#" class="text-gray-300 hover:text-white"><ion-icon name="logo-instagram" class="text-xl"></ion-icon></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
