<?php
include 'koneksi.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Event ID not provided.");
}

$event_id = $_GET['id'];

$stmt = $conn->prepare("SELECT u.username, u.email, e.name, r.registration_date FROM users u 
                        JOIN registrations r ON u.id = r.user_id
                        JOIN events e ON e.id = r.event_id
                        WHERE r.event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$registrants = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['export'])) {
    $fileType = $_POST['file_type'];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'Username');
    $sheet->setCellValue('B1', 'Email');
    $sheet->setCellValue('C1', 'Event');
    $sheet->setCellValue('D1', 'Registration Date');

    $rowNumber = 2;
    foreach ($registrants as $registrant) {
        $sheet->setCellValue("A$rowNumber", $registrant['username']);
        $sheet->setCellValue("B$rowNumber", $registrant['email']);
        $sheet->setCellValue("C$rowNumber", $registrant['name']);
        $sheet->setCellValue("D$rowNumber", $registrant['registration_date']);
        $rowNumber++;
    }

    if ($fileType == 'excel') {
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="registrants.xlsx"');
        $writer->save('php://output');
    } elseif ($fileType == 'csv') {
        $writer = new Csv($spreadsheet);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="registrants.csv"');
        $writer->save('php://output');
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Registrants</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        }
    </script>

    <!-- Main Content -->
    <div class="container mx-auto py-8 flex-grow">
        <h1 class="text-2xl font-bold mb-4 text-center">Event Registrants</h1>

        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Username</th>
                    <th class="py-2 px-4 border-b">Email</th>
                    <th class="py-2 px-4 border-b">Event</th>
                    <th class="py-2 px-4 border-b">Registration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrants as $registrant): ?>
                    <tr>
                        <td class="py-2 px-4 border-b"><?php echo $registrant['username']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $registrant['email']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $registrant['name']; ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $registrant['registration_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <form method="post" class="mt-4">
            <label for="file_type" class="mr-2">Export as:</label>
            <select name="file_type" id="file_type" class="border px-2 py-1">
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
            </select>
            <button type="submit" name="export" class="bg-blue-500 text-white px-4 py-2 rounded">Export</button>
        </form>

        <a href="indexadmin.php" class="text-blue-600 mt-4 block">Back to Dashboard</a>
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
