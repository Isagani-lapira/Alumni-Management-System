<?php
session_start();



// Check if Logged In
if (
    !isset($_SESSION['username']) ||
    $_SESSION['logged_in'] == false ||
    // TODO check later if they could use UnivAdmin
    $_SESSION['accountType'] != 'ColAdmin'
) {
    // session does not exist 
    header("location: login.php");
    exit();
} else {
    // fetch details and proceed
    require_once '../PHP_process/connection.php';
    require '../PHP_process/personDB.php';

    $username = $_SESSION['username'];

    // TODO and sanitize user input
    //get the person ID of user
    $query = "SELECT coladmin.*
            FROM coladmin
            JOIN user ON coladmin.username = user.username
            WHERE user.username = '$username'";

    $result = mysqli_query($mysql_con, $query);
    if ($result) {
        $data = mysqli_fetch_assoc($result);
        $colCode = $data['colCode'];
        $personID = $data['personID'];
        $adminID = $data['adminID'];

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


        if (!isset($_SESSION['adminID'])) {
            // Setup the activity log
            require_once './php/logging.php';
            // store the id in session
            $_SESSION['personID'] = $personID;
            $_SESSION['fullName'] = $fullname;
            $_SESSION['adminID'] = $adminID;
            $_SESSION['colCode'] = $colCode;
            $action = "signin";
            $details = "signed in";
            setNewActivity($mysql_con, $_SESSION['adminID'], $action, $details);
        }
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

    <!-- Favicon -->
    <link rel="icon" href="../assets/bulsu_connect_img/bulsu_connect_icon.png" type="image/x-icon">
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
    <!-- Utilities stylesheet -->
    <link rel="stylesheet" href="./assets/css/util.css">


    <!-- End Stylesheets -->

    <!-- Javascript Scripts -->
    <!-- JS Plugins -->
    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Jquery UI -->
    <script src=" https://cdn.jsdelivr.net/npm/jqueryui@1.11.1/jquery-ui.min.js "></script>
    <link href=" https://cdn.jsdelivr.net/npm/jqueryui@1.11.1/jquery-ui.min.css " rel="stylesheet">
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Lodash Utility Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js" integrity="sha512-WFN04846sdKMIP5LKNphMaWzU7YpMyCU245etK3g/2ARYbPK9Ub18eG+ljU96qKRCWh+quCY7yefSmlkQw1ANQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Date Range Picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Moment JS (For better date parsing) -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

    <!-- Sweet Alert Notification Plugin -->
    <script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.all.min.js "></script>
    <link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css " rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- End JS Plugins -->
    <!-- System Script -->
    <script src="./scripts/core.js" defer></script>
    <script src="./scripts/utils.js" type="module"></script>
    <!-- End JS Scripts -->

</head>

<body class="">

    <div class="flex flex-row min-h-screen ">

        <aside class="
        border flex-initial relative w-80 px-5 py-5 transition-all group 
        " id="sidebar">
            <header class="my-2 p-2 flex gap-4 items-center justify-start">
                <i class="fa-solid fa-bars " id="toggleSidebarIcon"></i>
                <h1 class="text-lg tracking-wide group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  "><span class="font-bold ">Alumni </span> System</h1>
            </header>
            <!-- TODO make this sticky fixed left-0 top-8 z-0 -->
            <!-- TODO Adjust icons to fill up when changed -->
            <nav class="">
                <!-- Main Navigation -->
                <ul class="flex flex-col gap-2 mb-6 py-5 w-4/5 font-light text-sm ">

                    <li><a data-link="dashboard" href="#dashboard" class=" flex justify-left flex-nowrap rounded-lg p-2  font-bold bg-accent text-white ">
                            <svg class="block" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M13 3v6h8V3m-8 18h8V11h-8M3 21h8v-6H3m0-2h8V3H3v10Z"></path>
                            </svg>
                            <span class="group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">DASHBOARD</span></a></li>
                    <li><a data-link="make-post" href="#make-post" class=" flex justify-left flex-nowrap rounded-lg p-2">
                            <i class="fa-solid fa-bullhorn  fa-xl"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">MAKE POST</span>
                        </a></li>
                    <li><a data-link="announcements" href="#announcements" class=" flex justify-left flex-nowrap rounded-lg p-2">
                            <i class="fa-solid fa-message  fa-xl"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150   animate">ANNOUNCEMENTS</span>

                        </a></li>
                    <li><a data-link="email" href="#email" class=" flex justify-left flex-nowrap rounded p-2 ">

                            <i class="fa-solid fa-envelope fa-xl ">

                            </i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">EMAIL</span>
                        </a></li>
                    <!-- <li><a data-link="student-record" href="#student-record" class=" flex justify-left flex-nowrap rounded-lg p-2">
                            <i class="fa-solid fa-folder-open fa-xl "></i>

                            STUDENT RECORDS</a></li> -->
                    <li><a data-link="records" href="#records" class=" flex justify-left flex-nowrap rounded-lg p-2">
                            <i class="fa-solid fa-folder-open fa-xl "></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">RECORDS</span>

                        </a></li>
                    <li><a data-link="event" href="#event" class=" flex justify-left flex-nowrap rounded p-2 ">
                            <i class="fa-solid fa-calendar-check  fa-xl"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">EVENT</span>

                        </a></li>
                    <li><a data-link="forms" href="#forms" class=" flex justify-left flex-nowrap rounded p-2">
                            <i class="fa-brands fa-wpforms fa-xl "></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">TRACER FORM</span>

                        </a></li>
                    <li><a data-link="profile" href="#profile" class=" flex justify-left flex-nowrap rounded p-2">
                            <i class="fa-solid fa-circle-user  fa-xl"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">PROFILE</span>

                        </a></li>
                </ul>

                <!-- Alumni Navigation -->
                <div class="my-2 uppercase font-normal text-sm tracking-wider  group-[.is-collapsed]:opacity-0 group-[.is-collapsed]:invisible  transition-all   ">Alumni</div>
                <ul class="space-y-2 w-4/5 font-light text-sm">
                    <li><a data-link="alumni-of-the-month" href="#alumni-of-the-month" class="
                     flex justify-left flex-nowrap rounded p-2">
                            <i class="fa-solid fa-user-graduate  fa-xl"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">ALUMNI OF THE MONTH</span></a></li>
                    <li><a data-link="community" href="#community" class=" flex justify-left flex-nowrap rounded p-2">
                            <i class=" fa-xl fa-solid fa-users"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">COMMUNITY HUB</span></a></li>
                    <li><a data-link="job-opportunities" href="#job-opportunities" class=" flex justify-left flex-nowrap rounded p-2">
                            <i class="fa-xl  fa-solid fa-briefcase"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">JOB OPPORTUNITIES</span></a></li>
                </ul>

            </nav>
            <!-- Sign out Button -->
            <button class="btn-accent absolute bottom-2" id="signOutPromptBtn"><i class="px-2 fa-solid fa-right-from-bracket"></i><span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150 ">Sign Out</span></button>
        </aside>

        <main class="flex-1 mx-auto mt-10">
            <div id="main-root">

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