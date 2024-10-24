<?php
include 'koneksi.php';
session_start();

$user_id = $_SESSION['user_id'];


$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();


$events_query = "
    SELECT events.name, events.date, events.location 
    FROM registrations 
    JOIN events ON registrations.event_id = events.id 
    WHERE registrations.user_id = $user_id";
$events_result = $conn->query($events_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
</head>
<body class="font-[Poppins] bg-gradient-to-t from-[#E0EAF9] to-[#F4F7FC] min-h-screen text-gray-900 flex flex-col">
    <!-- Header Template Ke -->
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

<div class="container mx-auto mt-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold mb-4">My Profile</h1>
        <h3 class="text-gray-600"><strong>Username:</strong> <?php echo $user['username']; ?></h3>
        <p class="text-gray-600"><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p class="text-gray-600"><strong>Description: </strong> <?php echo htmlspecialchars($user['description']); ?></p>
    <p>
        <strong>Profile Image:</strong> <br>
        <?php if ($user['image']): ?>
            <img src="<?php echo htmlspecialchars($user['image']); ?>" alt="Profile Image" width="150">
        <?php else: ?>
            No profile image uploaded.
        <?php endif; ?>
    </p>
        <a href="edit_profile.php" class="mt-4 inline-block bg-purple-600 text-white py-2 px-4 rounded">Edit Profile</a>
    </div>


    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h2 class="text-2xl font-bold mb-4">History</h2>
        <?php if ($events_result->num_rows > 0): ?>
            <ul class="space-y-4">
                <?php while($event = $events_result->fetch_assoc()): ?>
                    <li class="border-b pb-2">
                        <h3 class="text-xl font-semibold"><?php echo $event['name']; ?></h3>
                        <p class="text-gray-600">Date: <?php echo $event['date']; ?></p>
                        <p class="text-gray-600">Location: <?php echo $event['location']; ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-600">You have not registered for any events yet.</p>
        <?php endif; ?>
    </div>
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
