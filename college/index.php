<?php
session_start();
require_once __DIR__ . '/../config.php';


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
    require_once 'php/connection.php';
    require_once SITE_ROOT . "/PHP_process/personDB.php";



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
        // Add session info from the college info
        $query = "SELECT colLogo,colname FROM college WHERE colCode = '$colCode';";
        $result = mysqli_query($mysql_con, $query);
        $data = mysqli_fetch_assoc($result);

        // $colLogo = base64_encode($data['colLogo']);
        $_SESSION['colLogo'] = base64_encode($data['colLogo']);


        // $_SESSION['colLogo'] = $data['colLogo'];
        $_SESSION['colname'] = $data['colname'];


        $_SESSION['profilePicture'] = $profilepicture;


        if (!isset($_SESSION['adminID'])) {
            // Setup the activity log
            require_once './php/logging.php';
            // store the id in session
            $_SESSION['personID'] = $personID;
            $_SESSION['fullName'] = $fullname;
            $_SESSION['adminID'] = $adminID;
            $_SESSION['colCode'] = $colCode;
            $_SESSION['profilePicture'] = $profilepicture;





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




    <!-- End Stylesheets -->

    <!-- Javascript Scripts -->
    <!-- JS Plugins -->
    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Jquery UI -->
    <script src=" https://cdn.jsdelivr.net/npm/jqueryui@1.11.1/jquery-ui.min.js "></script>
    <link href=" https://cdn.jsdelivr.net/npm/jqueryui@1.11.1/jquery-ui.min.css " rel="stylesheet">
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>



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
    <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.6/sorting/datetime-moment.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.2.0/js/dataTables.searchPanes.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

    <!-- JQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>

    <!-- iconify icon -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <!--  description plugin -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>


    <!-- End JS Plugins -->

    <!-- System Tailwind stylesheet -->
    <link rel="stylesheet" href="../css/main.css">

    <!--  -->
    <!-- Utilities stylesheet -->
    <link rel="stylesheet" href="./assets/css/util.css">
    <link rel="stylesheet" href="../style/style.css">

    <!-- System Script -->
    <script src="./scripts/core.js" defer></script>
    <script src="./scripts/utils.js" type="module"></script>
    <!-- End JS Scripts -->

</head>

<body class="">

    <div class="flex flex-row min-h-screen  ">

        <aside class="
        border flex-initial relative w-80 px-5 py-5 transition-all group flex flex-col
        " id="sidebar">
            <header class="my-2 p-2 flex gap-4 items-center justify-start">
                <i class="fa-solid fa-bars " id="toggleSidebarIcon"></i>
                <h1 class="text-lg tracking-wide group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  "><span class="font-bold ">Alumni </span> System</h1>
            </header>
            <!-- TODO make this sticky fixed left-0 top-8 z-0 -->
            <!-- TODO Adjust icons to fill up when changed -->
            <nav class="relative flex-1 flex flex-col">
                <!-- Main Navigation -->
                <ul class="flex flex-col gap-2 mb-6 py-5 w-4/5 font-light text-sm [&>*:hover]:bg-gray-100 ">
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
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">OUTBOX</span>
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

                </ul>

                <!-- Alumni Navigation -->
                <ul class="space-y-2 w-4/5 font-light text-sm [&>*:hover]:bg-gray-100">
                    <li>
                        <div class="daisy-menu-title  my-2 uppercase font-medium text-sm tracking-wider  group-[.is-collapsed]:opacity-0 group-[.is-collapsed]:invisible  transition-all   ">Alumni</div>
                    </li>
                    <li><a data-link="alumni-of-the-month" href="#alumni-of-the-month" class="
                     flex justify-left flex-nowrap rounded p-2">
                            <i class="fa-solid fa-user-graduate  fa-xl"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">ALUMNI OF THE MONTH</span></a></li>
                    <li><a data-link="community" href="#community" class=" flex justify-left flex-nowrap rounded p-2">
                            <i class=" fa-xl fa-solid fa-users"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">COMMUNITY HUB</span></a></li>
                    <li>
                        <a data-link="job-opportunities" href="#job-opportunities" class=" flex justify-left flex-nowrap rounded p-2"> <i class="fa-xl  fa-solid fa-briefcase"></i>
                            <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">JOB OPPORTUNITIES</span>
                        </a>
                    </li>
                </ul>

                <!-- Add padding for the absolute bottom buttons -->
                <!-- Bottom Buttons  -->

                <div class=" w-full grow  flex items-end justify-end flex-col basis-48 ">
                    <ul class="space-y-2 w-full font-light  ">
                        <li>
                            <a data-link="profile" href="#profile" class="  flex justify-left flex-nowrap rounded items-center p-2 group-[.is-collapsed]:p-0">
                                <!-- get the session image  -->
                                <img id="college-logo-profile" src="data:image/jpeg;base64,<?= $_SESSION['colLogo'] ?>" alt="picture of college logo" class="w-9 h-9 rounded-full object-cover  ">
                                <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">
                                    <span class="block font-bold"><?= $_SESSION['colCode'] ?></span>
                                    <span class="font-light"> <?= $fullname ?></span>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a data-link="settings" href="#settings" class="flex justify-left flex-nowrap rounded p-2">
                                <i class="fa-xl fa-solid fa-gear"></i>
                                <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150  ">Settings</span>
                            </a>
                        </li>
                        <li> <button class="btn-accent flex justify-left flex-nowrap rounded p-2  " id="signOutPromptBtn">
                                <i class="fa-xl fa-solid fa-right-from-bracket"></i>
                                <span class="ml-2 group-[.is-collapsed]:hidden  transition-all delay-150 duration-150 ">Sign Out</span>
                            </button></li>
                    </ul>
                </div>

            </nav>

        </aside>



        <main class="flex-1 mx-auto mt-2">
            <div id="main-root" class="max-h-full overflow-auto">

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

    <!-- loading screen -->
    <div id="loadingScreen" class="post modal fixed inset-0 flex flex-col justify-center items-center p-3 z-50 hidden ">
        <span class="loader w-36 h-36"></span>
        <span class="text-lg font-bold text-white my-2 italic">"We promise it's worth the wait!"</span>
    </div>
</body>

</html>