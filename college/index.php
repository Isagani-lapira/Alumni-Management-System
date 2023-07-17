<?php
session_start();

// Check if Logged In
if (
    !isset($_SESSION['username']) ||
    $_SESSION['logged_in'] == false ||
    // todo check later if they could use UnivAdmin
    $_SESSION['accountType'] != 'ColAdmin'
) {
    // session does not exist 
    header("location: login.php");
    exit();
} else {
    // fetch details and proceed
    require_once '../PHP_process/connection.php';
    require '../PHP_process/personDB.php';

    // todo validate and sanitize user input
    $username = $_SESSION['username'];

    //get the person ID of user
    $query = "SELECT coladmin.personID
            FROM coladmin
            JOIN user ON coladmin.username = user.username
            WHERE user.username = '$username'";

    $result = mysqli_query($mysql_con, $query);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        $personID = $data['personID'];

        //get person details
        $personObj = new personDB();
        $personDataJSON = $personObj->readPerson($personID, $mysql_con);
        $personData = json_decode($personDataJSON, true);

        $fullname = $personData['fname'] . ' ' . $personData['lname'];
        $age = $personData['age'];
        $address = $personData['address'];
        $bday = $personData['bday'];
        $gender = ucfirst($personData['gender']);
        $contactNo = $personData['contactNo'];
        $personal_email = $personData['personal_email'];
        $bulsu_email = $personData['bulsu_email'];
        $profilepicture = $personData['profilepicture'];

        $_SESSION['personID'] = $personID;
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>


    <!-- Stylesheets -->
    <!--   Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <!-- Font-awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- System Tailwind stylesheet -->
    <link rel="stylesheet" href="../css/main.css">
    <!-- End Stylesheets -->

    <!-- Javascript Scripts -->
    <!-- JS Plugins -->
    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Lodash Utility Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- End JS Plugins -->
    <!-- System Script -->
    <script src="./assets/js/core.js" defer></script>
    <!-- End JS Scripts -->

</head>

<body class="">

    <div class="flex flex-row min-h-screen ">

        <aside class="
        border flex-initial relative w-80 px-5 py-5
        ">
            <header class="my-2">
                <h1 class="text-lg tracking-wide"><span class="font-bold ">Alumni </span> System</h1>
            </header>
            <!-- TODO make this sticky fixed left-0 top-8 z-0 -->
            <!-- TODO Adjust icons to fill up when changed -->
            <nav class="">
                <!-- Main Navigation -->
                <ul class="space-y-2 mb-6 py-5 w-4/5">
                    <li><a data-link="dashboard" href="#dashboard" class="flex rounded-lg p-2  font-bold bg-accent text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-pie-filled" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9.883 2.207a1.9 1.9 0 0 1 2.087 1.522l.025 .167l.005 .104v7a1 1 0 0 0 .883 .993l.117 .007h6.8a2 2 0 0 1 2 2a1 1 0 0 1 -.026 .226a10 10 0 1 1 -12.27 -11.933l.27 -.067l.11 -.02z" stroke-width="0" fill="currentColor" />
                                <path d="M14 3.5v5.5a1 1 0 0 0 1 1h5.5a1 1 0 0 0 .943 -1.332a10 10 0 0 0 -6.11 -6.111a1 1 0 0 0 -1.333 .943z" stroke-width="0" fill="currentColor" />
                            </svg>
                            Dashboard</a></li>
                    <li><a data-link="announcements" href="#announcements" class="flex rounded-lg p-2">
                            <i class="fa-solid fa-bullhorn mr-2 fa-xl"></i>
                            Announcements</a></li>
                    <li><a data-link="email" href="#email" class="flex rounded p-2 ">
                            <i class="fa-solid fa-envelope fa-xl mr-2"></i> Email
                        </a></li>
                    <li><a data-link="reports" href="#reports" class="flex rounded-lg p-2">
                            <i class="fa-solid fa-folder-open fa-xl mr-2"></i>

                            Student Records</a></li>
                    <li><a data-link="event" href="#event" class="flex rounded p-2 ">
                            <i class="fa-solid fa-calendar-check mr-2 fa-xl"></i>
                            Event</a></li>
                    <li><a data-link="forms" href="#forms" class="flex rounded p-2">
                            <i class="fa-brands fa-wpforms fa-xl mr-2"></i>
                            Tracer Form</a></li>
                    <li><a data-link="profile" href="#profile" class="flex rounded p-2">
                            <i class="fa-solid fa-circle-user mr-2 fa-xl"></i>
                            Profile</a></li>
                </ul>

                <!-- Alumni Navigation -->
                <div class="my-2 uppercase font-normal text-sm tracking-wider">Alumni</div>
                <ul class="space-y-2 w-4/5">
                    <li><a data-link="alumni-of-the-month" href="#alumni-of-the-month" class="
                    flex rounded p-2">
                            <i class="fa-solid fa-user-graduate mr-2 fa-xl"></i>
                            Alumni of the Month</a></li>
                    <li><a data-link="community" href="#community" class="flex rounded p-2">
                            <i class="mr-2 fa-xl fa-solid fa-users"></i>
                            Communitity</a></li>
                    <li><a data-link="job-oppurtunities" href="#job-oppurtunities" class="flex rounded p-2">
                            <i class="fa-xl mr-2 fa-solid fa-briefcase"></i>
                            Job Oppurtunities</a></li>
                </ul>

            </nav>
            <!-- Sign out Button -->
            <button class="btn-accent absolute bottom-2" id="signOutPromptBtn"><i class="px-2 fa-solid fa-right-from-bracket"></i>Sign Out</button>
        </aside>

        <main class="flex-1 mx-auto mt-10">
            <div id="main-root">
                <?php require 'pages/dashboard.php'; ?>
            </div>

        </main>
    </div>
    <!-- Modals -->
    <div id="sign-out-prompt" class="modal-bg fixed inset-0 h-full w-full flex items-center justify-center 
      text-grayish hidden ">

        <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3">
            <p class="text-center font-medium text-greyish_black mb-7 mt-3">Are you sure you want to sign out?</p>
            <div class="flex gap-2 justify-end">
                <button id="cancelSignoutBtn" class="btn-tertiary text-gray-800 ">Cancel</button>
                <button id="signoutBtn" class="btn-primary">Sign out</button>

            </div>
        </div>

    </div>


</body>

</html>