<?php
include 'koneksi.php';
require 'vendor/autoload.php';

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
<body class="font-[Poppins] bg-gradient-to-t from-[#fbc2eb] to-[#a6c1ee] min-h-screen text-gray-900 flex flex-col">

    <!-- Header -->
    <header class="bg-[#FFE1FF] py-3">
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
                <a href="login.php" class="bg-[#7E60BF] text-white px-5 py-2 rounded-full hover:bg-[#CDC1FF]">Log Out</a>
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
    <footer class="bg-[#FFE1FF] text-gray-700 py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left">
                    <p class="text-sm">&copy; <?php echo date("Y"); ?> Baileo Event Organizer. All rights reserved.</p>
                </div>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <ion-icon name="logo-facebook" class="text-xl"></ion-icon>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <ion-icon name="logo-twitter" class="text-xl"></ion-icon>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900">
                        <ion-icon name="logo-instagram" class="text-xl"></ion-icon>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
