<?php
include 'koneksi.php';
session_start();

$showModal = false;

if (isset($_POST['cancel_registration'])) {
    $event_id = $_POST['cancel_registration'];
    $user_id = $_SESSION['user_id'];

    // Check if the registration exists
    $stmt = $conn->prepare("SELECT * FROM registrations WHERE event_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $event_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the registration if it exists
        $stmt = $conn->prepare("DELETE FROM registrations WHERE event_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $event_id, $user_id);

        if ($stmt->execute()) {
            $message = "Registration cancelled successfully!";
            $showModal = true;
        } else {
            $message = "Error deleting registration: " . $stmt->error;
            $showModal = true;
        }
    } else {
        $message = "Registration not found.";
        $showModal = true;
    }

    $stmt->close();
}

// Get the events that the user has registered for
$user_id = $_SESSION['user_id'];
$query = "SELECT e.* FROM events e INNER JOIN registrations r ON e.id = r.event_id WHERE r.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script>
        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }
    </script>
</head>
<body class="font-[Poppins] bg-gradient-to-t from-[#E0EAF9] to-[#F4F7FC] min-h-screen text-gray-900 flex flex-col">
<header class="bg-[#3A3D99] py-3 shadow-lg">
    <nav class="flex justify-between items-center w-[92%] mx-auto">
        <div>
            <a href="index.php"><img class="w-20" src="assets/baileo3.png" alt="..."></a>
        </div>
        <div class="nav-links duration-500 md:static absolute bg-[#3A3D99] md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5"> <!-- Changed to solid blue -->
            <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8">
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="index.php">Events</a>
                </li>
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="registered_events.php">Registered Event</a>
                </li>
                <li>
                    <a class="text-white hover:text-yellow-300 transition duration-200 ease-in-out" href="profile.php">My Profile</a>
                </li>
            </ul>
        </div>

        <div class="flex items-center gap-6"> 
            <a href="logout.php" class="bg-gradient-to-r from-[#243c9a] via-[#7e22ce] to-[#ec4899] text-white px-6 py-3 rounded-full shadow-md text-lg font-bold tracking-wide uppercase transition-transform transform hover:scale-105 hover:shadow-lg hover:from-[#1e3a8a] hover:via-[#6b21a8] hover:to-[#db2777] duration-300 ease-in-out">
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
        }
    </script>

<div class="container mx-auto mt-8 mb-8 text-center">
<h1 class="text-4xl font-bold text-gray-800 mb-4 relative inline-block">
        <span class="relative z-10 transform -skew-y-6">Your Registered Events</span>
    </h1>
    <p class="text-lg text-gray-600 mb-3">Check your registrations below and manage your events easily.</p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if ($result->num_rows === 0): ?>
            <p class="text-center text-gray-600">No events registered yet.</p>
        <?php else: ?>
            <?php while ($event = $result->fetch_assoc()) { ?>
                <div class="bg-white rounded-lg shadow-lg p-6 transition-transform duration-300 hover:scale-105 hover:shadow-2xl">
                    <img src="<?php echo './admin/', $event['image_path']; ?>" alt="Event Image" class="w-full h-48 object-cover rounded-t-lg transition-opacity duration-300 hover:opacity-80">
                    <h2 class="text-xl font-semibold mt-4"><?php echo $event['name']; ?></h2>
                    <p class="text-gray-600"><?php echo $event['description']; ?></p>
                    <p class="text-sm text-blue-900 uppercase"><ion-icon name="pin"></ion-icon> <?php echo strtoupper($event['location']); ?></p>
                    <p class="text-sm text-blue-900"><ion-icon name="calendar"></ion-icon><span class="ml-1"><?php $date = $event['date']; $date_name = date('j F Y', strtotime($date)); echo $date_name; ?></span></p>
                    <form action="registered_events.php" method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                        <input type="hidden" name="cancel_registration" value="<?php echo $event['id']; ?>">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-transform duration-300 transform hover:scale-105 mt-3">Delete</button>
                    </form>
                </div>
            <?php } ?>
        <?php endif; ?>
    </div>
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
