<?php
session_start();
if (
  !isset($_SESSION['username']) ||
  $_SESSION['logged_in'] != true ||
  $_SESSION['accountType'] != 'UnivAdmin'
) {
  header("location: loginAdmin.php");
  exit();
} else {
  require_once '../PHP_process/connection.php';
  require '../PHP_process/personDB.php';

  $username = $_SESSION['username'];

  //get the person ID of that user
  $query = "SELECT univadmin.personID, univadmin.adminID
            FROM univadmin
            JOIN user ON univadmin.username = user.username
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
    $bday = dateInText($personData['bday']);
    $gender = ucfirst($personData['gender']);
    $contactNo = $personData['contactNo'];
    $personal_email = $personData['personal_email'];
    $bulsu_email = $personData['bulsu_email'];
    $profilepicture = $personData['profilepicture'];
    $_SESSION['personID'] = $personID;
    $_SESSION['univAdminID'] = $data['adminID'];
  }
}

function dateInText($date)
{
  $year = substr($date, 0, 4);
  $month = intval(substr($date, 5, 2));
  $day = substr($date, 8, 2);
  $months = [
    '', 'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];

  //convert date month to text format
  $month = $months[$month];
  //return in a formatted date
  return $month . ' ' . $day . ', ' . $year;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="../css/main.css" rel="stylesheet" />
  <link href="../style/style.css" rel="stylesheet" />
  <link href="../style/tracer.css" rel="stylesheet" />
  <link href="../style/logstyle.css" rel="stylesheet" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Corinthia&family=Dancing+Script:wght@500&family=Exo+2:wght@700&family=Fasthand&family=Freehand&family=Montserrat:ital,wght@0,400;0,700;1,400;1,600;1,700;1,800&family=Poppins:ital,wght@0,400;0,700;1,400&family=Roboto:wght@300;400;500&family=Source+Sans+Pro:ital@1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js" integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/js-md5@0.7.3/build/md5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <!-- Include DataTables CSS and JS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>


  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="icon" href="../assets/bulsu_connect_img/bulsu_connect_icon.png" type="image/x-icon">
  <title>University Admin</title>
</head>

<body>
  <div class="relative">
    <div id="promptMsg" class="w-full absolute top-1 hidden">
      <div class="promptMsg mx-auto shadow-lg rounded-md w-1/4 p-5 mt-2">
        <p id="message" class="text-accent font-semibold text-center text-sm "></p>
      </div>
    </div>
    <?php
    echo '<input id="accountUN" type="hidden" value="' . $username . '"/>';
    ?>
    <span id="promptMsgComment" class="hidden rounded-md slide-bottom fixed bottom-28 px-4 py-2 z-50 bg-accent text-white rounded-sm font-bold">Comment successfully added</span>
    <?php
    echo '<p class="profilePicVal hidden">' . $profilepicture . '</p>';
    echo '<input type="hidden" id="accPersonID" value="' .  rawurlencode($personID) . '">';
    ?>
    <div id="tabs" class="flex font-Montserrat text-greyish_black">
      <aside id="listOfPanels" class="w-3/12 top-0 h-screen p-5 border border-r-gray-300 relative">
        <div class="h-full relative">
          <div class="flex justify-start gap-2 items-center">
            <iconify-icon id="burgerBtn" icon="mdi:hamburger-menu" style="color: #991b1b;" width="28" height="28"></iconify-icon>
            <h1 class="font-extrabold my-5">
              Alumni <span class="font-normal">System</span>
            </h1>
          </div>

          <ul class="w-3/4 text-sm">

            <!-- DASHBOARD -->
            <li id="dashboardLi" class="rounded-lg p-2"><a href="#dashboard-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M13 3v6h8V3m-8 18h8V11h-8M3 21h8v-6H3m0-2h8V3H3v10Z" />
                </svg>
                <span>DASHBOARD</span></a>
            </li>

            <!-- MAKE POST -->
            <li id="announcementLI" class="rounded-lg p-2"><a href="#announcement-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M12 8H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h1v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h3l5 4V4l-5 4m9.5 4c0 1.71-.96 3.26-2.5 4V8c1.53.75 2.5 2.3 2.5 4Z" />
                </svg>
                <span>MAKE POST</span></a>
            </li>

            <!-- announcement -->
            <li id="newsAndUpdate" class="rounded-lg p-2">
              <a href="#newsAndUpdate-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                  <mask id="ipSAnnouncement0">
                    <g fill="none" stroke-linejoin="round" stroke-width="4">
                      <rect width="40" height="26" x="4" y="15" fill="#fff" stroke="#fff" rx="2" />
                      <path fill="#fff" stroke="#fff" stroke-linecap="round" d="m24 7l-8 8h16l-8-8Z" />
                      <path stroke="#000" stroke-linecap="round" d="M12 24h18m-18 8h8" />
                    </g>
                  </mask>
                  <path fill="currentColor" d="M0 0h48v48H0z" mask="url(#ipSAnnouncement0)" />
                </svg>
                <span>ANNOUNCEMENT</span>
              </a>
            </li>

            <!-- EMAIL -->
            <li id="emailLi" class="rounded-lg p-2"><a href="#email-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 16" width="24" height="24">
                  <path d="M 18 0 H 2 C 0.9 0 0.00999999 0.9 0.00999999 2 L 0 14 C 0 15.1 0.9 16 2 16 H 18 C 19.1 16 20 15.1 20 14 V 2 C 20 0.9 19.1 0 18 0 Z M 18 4 L 10 9 L 2 4 V 2 L 10 7 L 18 2 V 4 Z" />
                </svg>
                <span>OUTBOX</span></a>
            </li>

            <!-- ALUMNI RECORD-->
            <li id="alumniLi" class="rounded-lg p-2"><a href="#alumnRecord-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512">
                  <path d="M219.3.5c3.1-.6 6.3-.6 9.4 0l200 40C439.9 42.7 448 52.6 448 64s-8.1 21.3-19.3 23.5L352 102.9V160c0 70.7-57.3 128-128 128S96 230.7 96 160v-57.1l-48-9.6v65.1l15.7 78.4c.9 4.7-.3 9.6-3.3 13.3S52.8 256 48 256H16c-4.8 0-9.3-2.1-12.4-5.9s-4.3-8.6-3.3-13.3L16 158.4V86.6C6.5 83.3 0 74.3 0 64c0-11.4 8.1-21.3 19.3-23.5l200-40zM111.9 327.7c10.5-3.4 21.8.4 29.4 8.5l71 75.5c6.3 6.7 17 6.7 23.3 0l71-75.5c7.6-8.1 18.9-11.9 29.4-8.5c65 20.9 112 81.7 112 153.6c0 17-13.8 30.7-30.7 30.7H30.7C13.8 512 0 498.2 0 481.3c0-71.9 47-132.7 111.9-153.6z" />
                </svg>
                <span>ALUMNI RECORD</span>
              </a>
            </li>

            <!-- COLLEGES -->
            <li id="collegeLi" class="rounded-lg p-2"><a href="#colleges-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M14 11q1.25 0 2.125-.875T17 8q0-1.25-.875-2.125T14 5q-1.25 0-2.125.875T11 8q0 1.25.875 2.125T14 11Zm-6 7q-.825 0-1.413-.588T6 16V4q0-.825.588-1.413T8 2h12q.825 0 1.413.588T22 4v12q0 .825-.588 1.413T20 18H8Zm-4 4q-.825 0-1.413-.588T2 20V7q0-.425.288-.713T3 6q.425 0 .713.288T4 7v13h13q.425 0 .713.288T18 21q0 .425-.288.713T17 22H4Zm4-6h12q-1.1-1.475-2.65-2.238T14 13q-1.8 0-3.35.763T8 16Z" />
                </svg>
                <span>COLLEGES</span> </a>
            </li>

            <!-- FORMS -->
            <li id="formLi" class="rounded-lg p-2 "><a href="#forms-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1536 1536">
                  <path d="M515 783v128H263V783h252zm0-255v127H263V528h252zm758 511v128H932v-128h341zm0-256v128H601V783h672zm0-255v127H601V528h672zm135 860V148q0-8-6-14t-14-6h-32L978 384L768 213L558 384L180 128h-32q-8 0-14 6t-6 14v1240q0 8 6 14t14 6h1240q8 0 14-6t6-14zM553 278l185-150H332zm430 0l221-150H798zm553-130v1240q0 62-43 105t-105 43H148q-62 0-105-43T0 1388V148Q0 86 43 43T148 0h1240q62 0 105 43t43 105z" />
                </svg>
                <span>TRACER FORM</span></a>
            </li>

            <!-- PROFILE -->
            <li id="profileTabAdmin" class="rounded-lg p-2 "><a href="#profile-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <g fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M16 9a4 4 0 1 1-8 0a4 4 0 0 1 8 0Zm-2 0a2 2 0 1 1-4 0a2 2 0 0 1 4 0Z" />
                    <path d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11s11-4.925 11-11S18.075 1 12 1ZM3 12c0 2.09.713 4.014 1.908 5.542A8.986 8.986 0 0 1 12.065 14a8.984 8.984 0 0 1 7.092 3.458A9 9 0 1 0 3 12Zm9 9a8.963 8.963 0 0 1-5.672-2.012A6.992 6.992 0 0 1 12.065 16a6.991 6.991 0 0 1 5.689 2.92A8.964 8.964 0 0 1 12 21Z" />
                  </g>
                </svg>
                <span>PROFILE</span></a>
            </li>

            <br class="my-10">
            <span class="mt-4">ALUMNI</span>

            <!-- Alumni of the year -->
            <li id="aoyLi" class="rounded-lg p-2 "><a href="#alumnYear-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
                  <path fill="currentColor" d="M256 25c-11.594 0-23 12.8-23 31s11.406 31 23 31s23-12.8 23-31s-11.406-31-23-31zm-103.951 2.975l-16.098 8.05c15.092 30.185 51.37 56.81 82.188 74.442L232.334 295H247V192h18v103h14.666l14.195-184.533c30.818-17.632 67.096-44.257 82.188-74.442l-16.098-8.05c-19.91 29.9-44.891 49.148-71.334 57.77C281.311 97.28 269.75 105 256 105c-13.75 0-25.31-7.72-32.617-19.256c-26.443-8.62-51.424-27.87-71.334-57.77zM169 313v96H25v78h462v-30H343V313H169z" />
                </svg>
                <span>ALUMNI OF THE YEAR</span></a>
            </li>

            <!-- ALUMNI OF THE MONTH -->
            <li id="aomLi" class="rounded-lg p-2"><a href="#alumnMonth-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
                  <path d="M256 89.61L22.486 177.18L256 293.937l111.22-55.61l-104.337-31.9A16 16 0 0 1 256 208a16 16 0 0 1-16-16a16 16 0 0 1 16-16l-2.646 8.602l18.537 5.703a16 16 0 0 1 .008.056l27.354 8.365L455 246.645v12.146a16 16 0 0 0-7 13.21a16 16 0 0 0 7.293 13.406C448.01 312.932 448 375.383 448 400c16 10.395 16 10.775 32 0c0-24.614-.008-87.053-7.29-114.584A16 16 0 0 0 480 272a16 16 0 0 0-7-13.227v-25.42L413.676 215.1l75.838-37.92L256 89.61zM119.623 249L106.5 327.74c26.175 3.423 57.486 18.637 86.27 36.627c16.37 10.232 31.703 21.463 44.156 32.36c7.612 6.66 13.977 13.05 19.074 19.337c5.097-6.288 11.462-12.677 19.074-19.337c12.453-10.897 27.785-22.128 44.156-32.36c28.784-17.99 60.095-33.204 86.27-36.627L392.375 249h-6.25L256 314.063L125.873 249h-6.25z" />
                </svg>
                <span>ALUMNI OF THE MONTH</span></a>
            </li>

            <!-- Community Hub -->
            <li id="communityLi" class="rounded-lg p-2 "><a href="#community-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                  <path d="M15 17a6 6 0 1 0 0-12a6 6 0 0 0 0 12Zm18 0a6 6 0 1 0 0-12a6 6 0 0 0 0 12ZM4 22.446A3.446 3.446 0 0 1 7.446 19h9.624A7.963 7.963 0 0 0 16 23a7.98 7.98 0 0 0 2.708 6h-2.262a5.444 5.444 0 0 0-4.707 2.705c-3.222-.632-5.18-2.203-6.32-3.968C4 25.54 4 23.27 4 22.877v-.43ZM31.554 29a5.444 5.444 0 0 1 4.707 2.705c3.222-.632 5.18-2.203 6.32-3.968C44 25.54 44 23.27 44 22.877v-.43A3.446 3.446 0 0 0 40.554 19H30.93A7.963 7.963 0 0 1 32 23a7.98 7.98 0 0 1-2.708 6h2.262ZM30 23a6 6 0 1 1-12 0a6 6 0 0 1 12 0ZM13 34.446A3.446 3.446 0 0 1 16.446 31h15.108A3.446 3.446 0 0 1 35 34.446v.431c0 .394 0 2.663-1.419 4.86C32.098 42.033 29.233 44 24 44s-8.098-1.967-9.581-4.263C13 37.54 13 35.27 13 34.877v-.43Z" />
                </svg>
                <span>COMMUNITY HUB</span></a>
            </li>

            <!-- Job Opportunities -->
            <li id="jobLI" class="rounded-lg p-2 "><a href="#jobOpportunities-tab">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M4 21q-.825 0-1.413-.588T2 19V8q0-.825.588-1.413T4 6h4V4q0-.825.588-1.413T10 2h4q.825 0 1.413.588T16 4v2h4q.825 0 1.413.588T22 8v11q0 .825-.588 1.413T20 21H4Zm6-15h4V4h-4v2Z" />
                </svg>
                <span>JOB OPPORTUNITIES</span></a>
            </li>
          </ul>

          <p id="signout" class="text-center absolute bottom-0 cursor-pointer px-3">
            <i class="fa-solid fa-right-from-bracket"></i>
            Sign out
          </p>
        </div>

      </aside>

      <!-- <div class="w-3/12 top-0 h-screen p-5 border border-r-gray-300"></div> -->
      <main id="mainDiv" class=" flex-1 p-3">

        <!-- dashboard content -->
        <div id="dashboard-tab">
          <div class="content" id="dashboard-content">
            <div class="flex m-10 h-2/3 p-2">
              <div class="flex-1">
                <!-- welcome part -->
                <div class="relative rounded-lg h-max p-10 bg-gradient-to-r from-accent to-darkAccent">
                  <img class="absolute -left-2 " src="../images/standing-2.png" style="top: -55px;" alt="" srcset="" />
                  <span class="block text-lg text-white text-right">
                    Welcome Back <br />
                    <span class="font-semibold text-lg">
                      Mr.
                      <?php
                      echo '<span id="userFullname">' . $fullname . '</span>';
                      ?>
                    </span>
                  </span>
                </div>

                <div class="flex flex-wrap columns-3 mt-2 gap-2 text-xs font-medium">
                  <!-- total user -->
                  <div class="center-shadow flex-1 rounded-lg p-2 relative">
                    <img class="inline" src="../images/check-icon.svg" alt="" />
                    <span class="text-textColor text relative">
                      TOTAL USERS
                      <br />
                      REGISTERED</span>

                    <?php
                    require_once '../PHP_process/connection.php';
                    $query = "SELECT * FROM user WHERE `accounType` = 'User'";
                    $result = mysqli_query($mysql_con, $query);
                    $rows = mysqli_num_rows($result);

                    echo '<p class="text-accent text-4xl mt-2 font-bold relative bottom-0">' . $rows . '</p>';
                    ?>

                  </div>

                  <!-- Job Posting -->
                  <div class="center-shadow flex-1 rounded-lg p-2 relative">
                    <span class="text-textColor"><img class="inline mr-1" src="../images/work-icon.svg" />JOB
                      POSTING</span>
                    <?php
                    require_once '../PHP_process/connection.php';
                    $query = "SELECT * FROM `career`";
                    $result = mysqli_query($mysql_con, $query);
                    $rows = mysqli_num_rows($result);

                    echo '<p class="text-accent text-4xl mt-2 font-bold absolute bottom-2">' . $rows . '</p>';
                    ?>
                  </div>

                  <!-- colleges -->
                  <div class="center-shadow flex-1 rounded-lg p-2 relative">
                    <span class="text-textColor">
                      <img class="inline mr-1" src="../images/graduate-cap.svg" />
                      COLLEGES
                    </span>
                    <p id="totalColNo" class="text-accent font-bold text-4xl mt-2 absolute bottom-2"></p>
                  </div>

                </div>
              </div>

              <!-- Recent announcement -->
              <div class="flex-1">
                <!-- tracer status part -->
                <div class="w-4/5 mx-auto center-shadow p-5 rounded-lg">
                  <p class="mb-2 font-boldc text-accent font-semibold">RECENT ACTIVITIES
                    <img class="inline" src="../images/pencil-box-outline.png" alt="" srcset="">
                  </p>

                  <div id="recentActWrapper" class="flex flex-col items-start gap-2"></div>
                  <p id="btnViewMoreLog" class="text-sm text-accent font-semibold mt-3 text-end cursor-pointer">View more</p>
                </div>

              </div>
            </div>

            <div class="flex ps-10 font-semibold">
              <!-- Response by year -->
              <div class="flex-1">
                <p class="ps-10 text-accent font-semibold">RESPONSE BY YEAR </p>
                <canvas class="h-full w-full" id="responseByYear"></canvas>
              </div>

              <!-- summary -->
              <div class="flex-1">
                <div>
                  <div class="w-4/5 p-5 rounded-lg ms-3">
                    <p class="text-accent font-semibold">TRACER STATUS </p>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                      <p class="font-normal text-greyish_black">Already Answered</p>
                      <span id="alreadyAnswer" class="text-accent"></span>
                    </div>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                      <p class="font-normal text-greyish_black">Haven't answer yet</p>
                      <span id="notYetAnswering" class="text-accent"></span>
                    </div>
                  </div>
                </div>

                <div>
                  <div class="w-4/5 p-5 rounded-lg ms-3">
                    <p class="text-accent font-semibold">Personal Logs</p>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                      <p class="font-normal text-greyish_black">Total no. of posted announcement</p>
                      <?php
                      require_once '../PHP_process/connection.php';
                      $query = "SELECT * FROM `post` WHERE `username`= '$username' AND `status` = 'available'";
                      $result = mysqli_query($mysql_con, $query);
                      $row = mysqli_num_rows($result);
                      echo '<span id="totalPosted" class="text-accent">' . $row . '</span>';
                      ?>
                    </div>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                      <p class="font-normal text-greyish_black">Total no. of email sent</p>

                      <?php
                      require_once '../PHP_process/connection.php';
                      $query = 'SELECT * FROM `email` WHERE `personID` = "' . $_SESSION['personID'] . '"';
                      $result = mysqli_query($mysql_con, $query);
                      $row = mysqli_num_rows($result);
                      echo '<span class="text-accent">' . $row . '</span>';
                      ?>

                    </div>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                      <p class="font-normal text-greyish_black">Total no. of posted job</p>
                      <?php
                      require_once '../PHP_process/connection.php';
                      $query = "SELECT * FROM `career` WHERE `personID` = '" . $_SESSION["personID"] . "'";
                      $result = mysqli_query($mysql_con, $query);
                      $row = mysqli_num_rows($result);
                      echo '<span class="text-accent">' . $row . '</span>';
                      ?>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>

        <!-- POST CONTENT -->
        <div id="announcement-tab" class="px-5 hidden">
          <h1 class="text-xl font-extrabold">POST</h1>
          <p class="text-grayish">Here you can check all the post you have and can create new post</p>
          <div class="mt-5 text-end">
            <button id="btnAnnouncement" class="bg-accent font-light block text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg">CREATE
              NEW
              POST
            </button>

          </div>
          <hr class="h-px my-3 bg-grayish border-0 dark\:bg-gray-700" />

          <div class="flex items-center">

            <div class="m-2 p-1">
              <span class="font-semibold">Total Post</span>
              <p class="totalPost text-5xl font-bold"></p>
            </div>

            <div class="m-2 p-1">
              <p class="text-sm font-thin">College</p>
              <!-- college selection -->
              <select name="college" id="announcementCol" class="w-full border border-grayish p-2 rounded-lg">
                <option value="" selected>All</option>
                <?php
                require_once '../PHP_process/connection.php';
                $query = "SELECT * FROM `college`";
                $result = mysqli_query($mysql_con, $query);
                $rows = mysqli_num_rows($result);

                if ($rows > 0) {
                  while ($data = mysqli_fetch_assoc($result)) {
                    $colCode = $data['colCode'];
                    $colName = $data['colname'];

                    echo '<option value="' . $colCode . '">' . $colName . '</option>';
                  }
                } else echo '<option>No college available</option>';
                ?>
              </select>
            </div>


            <div class="m-2 p-1">
              <p>Show post (from - to)</p>
              <div class="w-full flex border border-grayish p-2 rounded-lg">
                <input type="text" name="daterange" id="daterange" value="Select a date" />
                <label class="" for="daterange">
                  <img class="h-5 w-5" src="../assets/icons/calendar.svg" alt="">
                </label>
              </div>

            </div>

          </div>

          <!-- recent post -->
          <div id="announcementContainer" class="w-full text-xs ">
            <table id="postTable" class="w-full center-shadow">
              <thead>
                <tr class="bg-accent text-white">
                  <th class="rounded-tl-lg">College code</th>
                  <th>No. of likes</th>
                  <th>No. of comments</th>
                  <th>Date posted</th>
                  <th class="rounded-tr-lg">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>


        </div>

        <!-- NEWS AND UPDATE -->
        <div id="newsAndUpdate-tab" class="p-5 hidden">
          <h1 class="text-xl font-extrabold">NEWS AND UPDATE</h1>
          <p class="text-grayish">Here you can make announcement that everyone can see, it can be news or events</p>
          <div class="flex justify-end py-2 border-b border-gray-300">
            <button id="newsBtn" class="text-sm text-white rounded-md bg-accent p-2">Make announcement</button>
          </div>
          <table class="w-full text-sm mt-5 center-shadow announcementTable">
            <thead>
              <tr class="bg-accent text-white">
                <th class=" rounded-tl-lg">Title</th>
                <th>Description</th>
                <th>Date Posted</th>
                <th class=" rounded-tr-lg">Action</th>
              </tr>
            </thead>
            <tbody id="announcementList" class="text-xs"></tbody>
          </table>
          <p id="noAvailMsgAnnouncement" class="text-center text-blue-400 text-lg hidden">No available data</p>
        </div>

        <!-- Email content -->
        <div id="email-tab" class="p-5 hidden">
          <h1 class="text-xl font-extrabold">EMAIL</h1>
          <p class="text-grayish">Here you can check all the email that have been sent</p>
          <div class="mt-5 text-end">
            <button id="btnEmail" class="bg-accent font-light block text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg">CREATE
              NEW
              EMAIL
            </button>
          </div>
          <hr class="h-px my-3 bg-grayish border-0 dark\:bg-gray-700" />

          <div class="flex items-center">

            <div class="m-2 p-1">
              <span class="font-semibold">Total Emailed</span>
              <?php
              require_once '../PHP_process/connection.php';
              $query = 'SELECT * FROM `email` WHERE `personID` = "' . $_SESSION['personID'] . '" ';
              $result = mysqli_query($mysql_con, $query);
              $row = mysqli_num_rows($result);
              echo '<p class="text-5xl font-bold" id="totalEmailed">' . $row . '</p>';
              ?>
            </div>

            <div class="m-2 p-1">
              <p class="text-sm font-thin">College</p>
              <!-- college selection -->
              <select name="college" id="emCol" class="w-full border border-grayish p-2 rounded-lg">
                <option value="" selected>All</option>
                <?php
                require_once '../PHP_process/connection.php';
                $query = "SELECT * FROM `college`";
                $result = mysqli_query($mysql_con, $query);
                $rows = mysqli_num_rows($result);

                if ($rows > 0) {
                  while ($data = mysqli_fetch_assoc($result)) {
                    $colCode = $data['colCode'];
                    $colName = $data['colname'];

                    echo '<option value="' . $colCode . '">' . $colName . '</option>';
                  }
                } else echo '<option>No college available</option>';
                ?>
              </select>
            </div>


            <div class="m-2 p-1">
              <p>Show email (from - to)</p>
              <div class="w-full flex items-center border border-grayish p-2 rounded-lg">
                <input type="text" name="emDateRange" id="emDateRange" value="Select a date" />
                <label class="" for="emDateRange">
                  <img class="h-5 w-5" src="../assets/icons/calendar.svg" alt="">
                </label>
              </div>

            </div>

          </div>


          <!-- recent email -->
          <p class="mt-5 font-semibold text-greyish_black">Recent Email</p>
          <table id="emailTable" class="table-auto w-full text-xs font-thin text-greyish_black center-shadow">
            <thead class="bg-accent text-white">
              <tr>
                <th class="text-start rounded-tl-md">EMAIL ADDRESS</th>
                <th class="text-start">COLLEGE</th>
                <th class="text-start">DATE</th>
                <th class="text-start rounded-tr-md">VIEW</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

        </div>

        <!-- alumni record content -->
        <div id="alumnRecord-tab" class="p-5 hidden">
          <h1 class="text-xl font-extrabold">ALUMNI RECORD</h1>
          <div class="flex justify-end text-xs text-greyish_black">
            <!-- EXPORT PDF -->
            <button class="p-2 px-4 m-2 border border-accent rounded-md 
            bg-accent text-white hover:bg-darkAccent hidden">Export as PDF
            </button>

          </div>

          <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700" />

          <div class="flex justify-evenly text-sm">

            <!-- batch selection -->
            <select id="batchAlumRecord" class="w-full p-1">
              <option selected disabled>Batch</option>
            </select>

            <!-- college selection -->
            <select id="alumniCollege" class="w-full p-1">
              <option selected disabled>Course</option>
              <option value="">All</option>
              <?php
              require_once '../PHP_process/connection.php';
              $query = "SELECT * FROM `college`";
              $result = mysqli_query($mysql_con, $query);
              $rows = mysqli_num_rows($result);

              if ($rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                  $colCode = $data['colCode'];
                  $colName = $data['colname'];

                  echo '<option value="' . $colCode . '">' . $colName . '</option>';
                }
              } else echo '<option>No college available</option>';
              ?>
            </select>

            <!-- employment status selection -->
            <select id="employmentStat" class="w-full p-1">
              <option selected value="" disabled>Employment Status</option>
              <option value="">All</option>
              <option value="Employed">Employed</option>
              <option value="Unemployed">Unemployed</option>
              <option value="Self-employed">Self-employed</option>
              <option value="Retired">Retired</option>
            </select>

          </div>


          <!-- record of name-->
          <table id="alumRecord" class="table-auto w-full mt-10 text-xs font-thin center-shadow">
            <thead>
              <tr class="bg-accent text-white">
                <th class="rounded-tl-lg">Student Number</th>
                <th>NAME</th>
                <th>COLLEGE</th>
                <th>BATCH</th>
                <th class="rounded-tr-lg">Employment Status</th>
              </tr>
            </thead>
            <tbody class="text-sm">
            </tbody>
          </table>

        </div>

        <!-- college content -->
        <div id="colleges-tab" class="h-full hidden">
          <div class="college-content">
            <h1 class="text-xl font-extrabold">COLLEGE</h1>
            <p class="text-grayish">Here you can check all colleges available in the University</p>

            <div class="flex justify-between mt-4">

              <div>
                <p class="font-medium">Total Colleges</p>
                <p id="totalCol" class="font-bold text-5xl"></p>
              </div>

              <div>
                <button id="btnNewCol" style="margin-left: auto; margin-right: 10px" class="block rounded-lg  text-white bg-accent p-2 hover:bg-darkAccent">Create new college
                </button>
              </div>

            </div>


            <hr class="border-1 border-greyish_black" />

            <div class="grid grid-cols-4 gap-4 p-7">
              <?php
              require_once '../PHP_process/connection.php';
              $query = "SELECT * FROM `college`";
              $result = mysqli_query($mysql_con, $query);
              $rows = mysqli_num_rows($result);

              if ($rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                  $colName = $data['colname'];
                  $colLogo = $data['colLogo'];

                  $logo = base64_encode($colLogo);

                  echo '
                    <div class="college center-shadow col-span-1 flex flex-col justify-center p-3" data-colname="' . $colName . '">
                      <img src="data:image/jpeg;base64,' . $logo . '"class="flex-auto h-1/5" alt="">
                      <p class="text-xs text-center text-greyish_black font-medium">' . $colName . '</p>
                    </div>';
                }
              } else echo '<p>No college available</p>';
              ?>

            </div>
          </div>

          <div class="individual-col h-full hidden">
            <h1 class="text-xl font-extrabold">
              <span>
                <img class="inline hover:cursor-pointer back-icon" src="../images/back.png" alt="">
              </span> PROFILE
            </h1>
            <div class="px-10 overflow-y-auto colDetailContainer">
              <div class="grid grid-cols-2 h-max p-5">
                <img id="colLogo" class="w-1/2 block mx-auto" src="" alt="">

                <div class="college-info text-xs">
                  <h1 id="colName" class="text-2xl font-extrabold"></h1>
                  <p class="text-gray-600 mt-3 font-medium">Number</p>
                  <p id="colContact" class="text-greyish_black text-sm font-semibold"></p>

                  <p class="text-gray-600 mt-2 font-medium">Email Address</p>
                  <p id="colEmail" class="text-greyish_black text-sm font-semibold"></p>

                  <p class="text-gray-600 mt-2 font-medium">Website</p>
                  <a id="colWebLink" target="_blank" class="text-sm text-blue-600 font-semibold"></a>
                </div>

              </div>

              <div class="flex justify-center gap-5 my-7">
                <div class="dean">
                  <div class="text-center">
                    <img id="deanImg" class="w-32 h-32 mx-auto rounded-md" alt="">
                    <p id="colDean" class="text-accent font-medium"></p>
                    <p class="text-gray-500 text-sm">DEAN, <span class="collegeCodeVal"></span></p>
                  </div>
                </div>

                <div class="coordinator">
                  <div class="text-center">
                    <img id="adminImg" class="w-32 h-32  mx-auto rounded-md" alt="">
                    <p id="colAdminName" class="text-accent font-medium"></p>
                    <p class="text-gray-500 text-sm">Alumni Coordinator, <span class="collegeCodeVal"></span></p>
                  </div>
                </div>

              </div>

              <div class="description mt-3 w-9/12">
                <h1 class="text-xl font-extrabold ">ABOUT US <span id="collegeCode"></span></h1>
                <P class="py-3"></P>
              </div>


              <div class="courses-offered my-10 w-8/12">
                <h2 class="text-xl font-extrabold mb-5">Courses Offered</h2>
                <div></div>
                <p class="text-gray-500">No available course set</p>
              </div>
            </div>

          </div>
        </div>

        <!-- forms content -->
        <div id="forms-tab" class="p-5 h-full hidden">
          <h1 class="text-xl font-extrabold">Alumni Tracer Form</h1>
          <p class="text-grayish">See the relevant information that are gathered</p>

          <div class="flex gap-2 justify-end mb-2">
            <select id="ddTracerform" class="px-2 py-2 rounded-md border border-gray-300">
              <option value="" disabled selected>Download Tracer Form</option>
            </select>

            <button id="tracerbtn" class="text-white hover:bg-blue-400 bg-blue-300 px-3 py-2 rounded-md">Tracer form</button>
            <button id="deployTracerBtn" class="px-3 py-2 bg-green-400 hover:bg-green-500 text-white rounded-md font-bold">Deploy Tracer</button>
          </div>

          <div id="formReport" class="border border-t-grayish h-full overflow-y-auto">
            <div class="flex gap-2 justify-evenly">
              <div class="h-2/5 w-1/2 p-5 flex flex-col">
                <h1 class="text-lg font-extrabold">Completion Chart</h1>
                <canvas class="w-full h-full" id="completionChart"></canvas>
              </div>

              <div class="h-2/5 w-1/2 p-5 flex flex-col">
                <h1 class="text-lg font-extrabold px-5">College Alumni Chart</h1>
                <canvas class="w-full h-5/6" id="respondentPerCol"></canvas>
              </div>
            </div>

            <div class="center-shadow rounded-lg p-3 m-2">
              <h3 class="font-semibold text-lg text-greyish_black">GET THE GRAPH OF A SPECIFIC QUESTION</h3>

              <div class="flex mt-2 items-center">
                <div class=" w-3/4 flex">
                  <!-- category -->
                  <div class="text-sm text-gray-500 flex flex-col w-1/2">
                    <label for="categorySelection" class="px-2">CATEGORY</label>
                    <select id="categorySelection" class="border border-gray-400 rounded-lg p-2 m-2">
                      <option value="" selected>--Your Choice--</option>
                    </select>
                  </div>
                  <!-- question -->
                  <div class="text-sm text-gray-500 flex flex-col w-1/2">
                    <label for="questionSelection" class="px-2">QUESTION</label>
                    <select id="questionSelection" class="border border-gray-400 rounded-lg p-2 m-2">
                      <option value="" selected>--Your Choice--</option>
                    </select>
                  </div>

                  <!-- graph type -->
                  <div class="text-sm text-gray-500 flex flex-col w-1/2">
                    <label for="typeChartSelection" class="px-2">GRAPH/CHART</label>
                    <select id="typeChartSelection" class="border border-gray-400 rounded-lg p-2 m-2">
                      <option value="" selected>--Your Choice--</option>
                      <option value="bar">Bar Type</option>
                      <option value="bubble">Bubble Type</option>
                      <option value="doughnut">Doughnut Type</option>
                      <option value="pie">Pie Type</option>
                      <option value="line">Line Type</option>
                    </select>
                  </div>

                </div>

                <div class="w-1/2 flex items-center justify-end">
                  <button id="displayChart" class="px-4 py-2 rounded-lg off">Display</button>
                </div>

              </div>

              <p class="text-sm italic text-gray-500">Note: Select a category first</p>
            </div>

            <div class="center-shadow rounded-lg p-3 m-2 h-full flex justify-center">
              <canvas id="chartPerQuestion"></canvas>
            </div>
          </div>

          <!-- repository -->
          <div id="tracerRepo" class="h-full border border-t-grayish p-5 w-full hidden">
            <div class="flex justify-end my-2">
              <div id="previewFormBtn" class="w-max cursor-pointer hover:font-bold hover:text-accent flex items-center gap-2">
                <iconify-icon icon="icon-park-outline:preview-open" width="24" height="24"></iconify-icon>
                <span>Preview</span>
              </div>
            </div>

            <div id="TracerWrapper" class="flex w-full h-full mx-auto p-2 gap-2">

              <div id="categoryWrapper" class="flex flex-col gap-2 w-1/3 p-1  h-full"></div>
              <!-- question set -->
              <div class="flex-1 h-full center-shadow p-3 relative">
                <input id="categoryName" class="w-full py-2 text-xl text-greyish_black border-b border-gray-400 font-semibold mb-3" disabled />
                <span id="btnSaveChanges" class="absolute top-5 right-2 text-gray-400 text-xs flex items-center gap-2 hidden" id="savedChanges">
                  <iconify-icon icon="dashicons:saved" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                </span>
                <div id="questionSetContainer" class="overflow-y-auto flex flex-col gap-2 py-2"></div>
              </div>

            </div>
          </div>

          <!-- preview -->
          <div id="previewForm" class="p-3 flex flex-col border-t border-gray-500 h-full overflow-y-auto hidden">
            <div>
              <div id="backFromPreviewBtn" class="flex items-center gap-3 w-max">
                <iconify-icon icon="fluent-mdl2:back" class="text-gray-800 cursor-pointer" width="24" height="24"></iconify-icon>
                <span class="font-semibold">Back</span>
              </div>

            </div>

            <h2 class="font-bold text-center text-lg">Alumni Tracer Form</h2>
            <div id="previewContainer" class="flex flex-col p-5 gap-3 border border-gray-300 rounded-md">
              <h3 id="categoryNamePrev" class="font-bold text-lg text-start"></h3>
            </div>

            <div class="mt-2">
              <button id="previousPreviewBtn" class="hidden text-gray-500 hover:text-lg">Previous</button>
              <button id="nextPreviewBtn" class="mt-2 w-max py-2 px-5 rounded-md bg-accent text-white hover:bg-darkAccent">Next</button>
            </div>

          </div>

        </div>

        <!-- profile content -->
        <div id="profile-tab" class="hidden">
          <div class="p-3 rounded-md bg-accent flex items-center">
            <img class="profilePic h-36 w-36 rounded-full border-2 border-white" alt="">
            <div class="ms-6">
              <p class="text-lg text-white font-bold">
                <?php
                echo $fullname;
                ?>
              </p>
              <p id="editProf" class="text-blue-300 hover:cursor-pointer hover:text-blue-500">Edit Profile</p>
            </div>
          </div>
          <div class="flex text-greyish_black">
            <div class="w-1/3 my-2">
              <p class="font-bold text-accent text-base my-3">About</p>
              <div class="flex flex-col gap-2">
                <!-- gender -->
                <span class="flex items-center text-sm gap-2">
                  <img src="../assets/icons/person.png" alt="">
                  <?php
                  echo $gender;
                  ?>
                </span>

                <!-- birthday -->
                <span class="flex items-center text-sm gap-2">
                  <img src="../assets/icons/cake.png" alt="">
                  <?php
                  echo $bday;
                  ?>
                </span>

                <!-- location -->
                <span class="flex items-center text-sm gap-2">
                  <img class="ps-1 messageIcon" src="../assets/icons/Location.png" alt="">
                  <?php
                  echo $address;
                  ?>
                </span>

                <!-- email -->
                <span class="flex items-center text-sm gap-2">
                  <img class="ps-1 " src="../assets/icons/Message.png" alt="">
                  <?php
                  echo $personal_email;
                  ?>
                </span>

                <!-- contact -->
                <span class="flex items-center text-sm gap-2">
                  <img class="ps-1" src="../assets/icons/Call.png" alt="">
                  <?php
                  echo $contactNo;
                  ?>
                </span>
              </div>
            </div>

            <div id="feedContainer" class="flex flex-col gap-2 w-full no-scrollbar z-0 ">
              <div class="flex gap-2 text-greyish_black text-sm my-2 border-b border-gray-300 p-3">
                <button id="availablePostBtn" class="activeBtn rounded-md px-5 py-1">Post</button>
                <button id="archievedBtnProfile" class="rounded-md px-5 py-1">Archived</button>
              </div>

              <div class="loadingProfile w-full h-full flex justify-center">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
              </div>
            </div>
          </div>

        </div>

        <!-- alumni of the year content -->
        <div id="alumnYear-tab" class="p-5 hidden h-full">
          <h1 class="text-xl font-extrabold">Alumni of the Year</h1>
          <p class="text-grayish">Make post for a newly awarded alumni of the year</p>
          <div class="flex justify-end w-full">
            <button id="assigningAOYbtn" class="bg-postButton hover:bg-postHoverButton text-white rounded-md p-2">Assign Alumni of the year</button>
          </div>

          <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700" />

          <!-- alumni of the year table -->
          <div id="aoydata" class="h-full">
            <select id="aoyCollege" class="rounded-md border border-gray-400 py-2"></select>
            <table id="AOYID" class="w-full text-sm center-shadow">
              <thead class="bg-accent text-white">
                <tr>
                  <th class="rounded-tl-lg">Full name</th>
                  <th>College</th>
                  <th>Year</th>
                  <th class="rounded-tr-lg">Action</th>
                </tr>
              </thead>
              <tbody class="text-xs"></tbody>
            </table>
          </div>

          <!-- alumni -->
          <div id="aoyRecord" class="h-full hidden">
            <div class="flex items-center justify-between">
              <h3 class=" text-greyish_black font-semibold">Choose Alumni To View Details</h3>
              <select id="aomSelection" class="border border-gray-400 rounded-md p-2">
                <option value="">Select Alumni of the month</option>
              </select>
            </div>

            <div id="aomRecord" class="p-3 overflow-y-auto flex flex-col items-center gap-2">
              <img id="aomCover" alt="" class="rounded-md object-fill h-2/5 w-1/2 hidden">
              <h2 class="text-xl aomFullname font-semibold text-greyish_black w-1/2 text-center"></h2>
              <span id="aomQuotation" class="italic text-gray-500 w-1/2 text-center text-sm"></span>
              <span class="italic text-sm text-blue-400 aomFullname"></span>

              <!-- testimonials -->
              <h2 class="w-1/2 text-greyish_black font-bold text-xl text-center mt-10 subtitle hidden">Testimonials</h2>
              <div class="testimonyContainer w-full h-max flex gap-5 flex-row flex-wrap justify-center mb-10 hidden"></div>

              <!-- achievements -->
              <h2 class="w-1/2 text-greyish_black font-bold text-xl text-center mt-5 subtitle hidden">Achievements</h2>
              <div class="achievementsContainer w-full h-max flex flex-row flex-wrap justify-center mb-10  p-5 gap-5 hidden"></div>

              <!-- skills and education -->
              <div class="w-1/2 h-max flex hidden">
                <!-- skills -->
                <div class="w-1/2">
                  <h2 class="text-greyish_black font-bold text-xl mb-5">Skills that I have:</h2>
                  <div id="skillContainer" class="flex flex-col gap-2"></div>
                </div>

                <!-- education -->
                <div class="w-1/2">
                  <h2 class="text-greyish_black font-bold text-xl mb-5">Connect with me:</h2>
                  <div id="socMedContainer" class="flex flex-col gap-2"></div>
                </div>

              </div>

              <div class="flex justify-end w-full hidden">
                <button class="confirmAOY rounded-md px-4 py-2 text-white bg-postButton hover:bg-postHoverButton">Confirm</button>
              </div>
            </div>

          </div>

        </div>

        <!-- alumni of the month content -->
        <div id="alumnMonth-tab" class="p-5 hidden">
          <h1 class="text-xl font-extrabold">Alumni of the Month</h1>
          <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700" />

          <div class="flex gap-2 my-2">
            <!-- batch -->
            <select id="aomMonth" class=" border border-gray-300 rounded-md py-3">
              <option value="" selected>Month</option>
            </select>

            <!-- year filter -->
            <select id="aomYr" class=" border border-gray-300 rounded-md px-3">
              <option value="" selected>Year</option>
            </select>

            <!-- college -->
            <select id="aomCollege" class=" border border-gray-300 rounded-md ">
              <option value="" selected>College</option>
              <?php
              require_once '../PHP_process/connection.php';
              $query = "SELECT * FROM `college`";
              $result = mysqli_query($mysql_con, $query);
              $rows = mysqli_num_rows($result);

              if ($rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                  $colCode = $data['colCode'];
                  $colName = $data['colname'];

                  echo '<option value="' . $colCode . '">' . $colName . '</option>';
                }
              } else echo '<option>No college available</option>';
              ?>
            </select>

          </div>

          <!-- record of name-->
          <table id="aomTable" class="table-auto w-full mt-16 text-xs font-thin text-greyish_black center-shadow">
            <thead>
              <tr class="bg-accent text-white">
                <th class="text-start rounded-tl-lg w-12"></th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>STUDENT NUMBER</th>
                <th class=" rounded-tr-lg">COLLEGE</th>
              </tr>
            </thead>
            <tbody class="text-sm text-gray-500 rounded-b-lg"></tbody>
          </table>
        </div>

        <!--community content -->
        <div id="community-tab" class="p-5 hidden">
          <div class="flex">
            <div class="w-4/6">
              <!-- college -->
              <select id="communityCollege" class="w-1/2 p-2 my-5 outline-none">
                <option value="" selected>College</option>
                <?php
                require_once '../PHP_process/connection.php';
                $query = "SELECT * FROM `college`";
                $result = mysqli_query($mysql_con, $query);
                $rows = mysqli_num_rows($result);

                if ($rows > 0) {
                  while ($data = mysqli_fetch_assoc($result)) {
                    $colCode = $data['colCode'];
                    $colName = $data['colname'];

                    echo '<option value="' . $colCode . '">' . $colName . '</option>';
                  }
                } else echo '<option>No college available</option>';
                ?>
              </select>

              <!-- report number -->
              <select id="communityReportFilter" class="outline-none w-1/3 p-2  my-5">
                <option value="" selected>All</option>
                <option value="Nudity">Nudity</option>
                <option value="Violence">Violence</option>
                <option value="Terrorism">Terrorism</option>
                <option value="Hate Speech">Hate Speech</option>
                <option value="False Information">False Information</option>
                <option value="Suicide or Self-injury">Suicide or Self-injury</option>
                <option value="Harassment">Harassment</option>
              </select>

              <div id="communityContainer" class="p-5 flex flex-col gap-3 no-scrollbar"></div>
              <p id="noPostMsgCommunity" class="text-blue-400 text-center hidden">No available post</p>
            </div>
            <!-- report graph -->
            <div class=" w-2/5 border-l border-gray-300">
              <p class="text-center font-bold text-xl">Report Graph</p>
              <canvas id="reportChart"></canvas>
            </div>
          </div>

        </div>

        <!-- job opportunities content -->
        <div id="jobOpportunities-tab" class="p-5 h-full hidden">
          <img class="jobPostingBack inline cursor-pointer hidden" src="../images/back.png" alt="">
          <h1 class="text-xl font-extrabold">Job Opportunities </h1>
          <p class="text-grayish  ">Check all the pending job post to be posted</p>
          <div id="jobList">
            <div class="flex mt-5 items-center justify-between w-full">
              <div>
                <button id="addNewbtn" class="px-3 py-1 text-white bg-accent rounded-md hover:bg-darkAccent">Add
                  New</button>
                <p id="jobMyPost" class="inline cursor-pointer text-sm hover:underline hover:text-accent">My post</p>
              </div>

              <div class=" items-center flex">

                <select id="authorFilter">
                  <option value="all" selected>All</option>
                  <option value="admin">Admin</option>
                  <option value="alumni">Alumni</option>
                </select>

              </div>

            </div>

            <table id="jobTable" class="w-full mt-10 center-shadow">
              <thead class="bg-accent text-sm text-white p-3">
                <tr>
                  <th class="rounded-tl-lg">Company</th>
                  <th>Title</th>
                  <th>Author</th>
                  <th>College</th>
                  <th>Date Posted</th>
                  <th class="rounded-tr-lg">Action</th>
                </tr>
              </thead>

              <tbody class="text-sm"></tbody>
            </table>

          </div>

          <!-- job posting -->
          <div id="jobPosting" class="mt-10 w-full hidden">
            <form id="jobForm" enctype="multipart/form-data">
              <div class="flex text-greyish_black">
                <!-- left side -->
                <div class="w-1/2">

                  <!-- college -->
                  <div class="mb-3">
                    <label for="collegeJob" class="font-bold text-greyish_black block">College</label>
                    <!-- college selection -->
                    <select name="collegeJob" id="collegeJob" class=" border border-gray-400 p-2 rounded-lg w-4/5 outline-none text-gray-400 jobField">
                      <option value="" selected disabled hidden>All</option>
                      <?php
                      require_once '../PHP_process/connection.php';
                      $query = "SELECT * FROM `college`";
                      $result = mysqli_query($mysql_con, $query);
                      $rows = mysqli_num_rows($result);

                      if ($rows > 0) {
                        while ($data = mysqli_fetch_assoc($result)) {
                          $colCode = $data['colCode'];
                          $colName = $data['colname'];

                          echo '<option value="' . $colCode . '">' . $colName . '</option>';
                        }
                      } else echo '<option>No college available</option>';
                      ?>
                    </select>
                  </div>

                  <!-- job title -->
                  <div>
                    <label class="font-bold text-greyish_black" for="jobTitle">Job Title</label>
                    <input id="jobTitle" name="jobTitle" class="jobField block p-2 border border-gray-400 w-4/5 outline-none rounded-lg mb-3" type="text" placeholder="e.g Software Engineer">
                  </div>

                  <!-- job description -->
                  <div>
                    <label class="font-bold text-greyish_black mt-5" for="projOverviewTxt">Project Description</label>
                    <textarea class="block message-area jobField border border-solid border-gray-400 h-40 w-4/5 mb-5 resize-none  
                      rounded-lg focus:outline-none text-greyish_black text-sm p-3" name="projDescriptTxt" id="projOverviewTxt" placeholder="Describe the person or provide other information you want to share to other people...."></textarea>
                  </div>

                  <!-- company logo -->
                  <div>
                    <label class="font-bold text-greyish_black mt-5 block my-2" for="projOverviewTxt">Company Logo</label>
                    <label class="bg-accent p-2 rounded-lg text-white" for="jobLogoInput">
                      Choose logo
                      <input id="jobLogoInput" name="jobLogoInput" class="jobField hidden" type="file">
                    </label>
                    <span id="jobFileName" class="mx-3 text-sm">file chosen</span>
                  </div>

                </div>

                <!-- right side -->
                <div class="w-1/2">

                  <!-- company name -->
                  <div>
                    <label class="font-bold text-greyish_black text-sm mt-5" for="jobCompany">Company Name</label>
                    <input id="jobCompany" name="companyName" class="jobField block p-2 border border-gray-400 w-4/5 outline-none rounded-lg mb-3" type="text" placeholder="e.g Accenture">
                  </div>

                  <!-- location -->
                  <div>
                    <label class="font-bold text-greyish_black text-sm mt-5" for="jobLocation">Location</label>
                    <input id="jobLocation" name="jobLocation" class="jobField block p-2 border border-gray-400 w-4/5 outline-none rounded-lg mb-3" type="text" placeholder="e.g Accenture">
                  </div>

                  <!-- job qualification -->
                  <div>
                    <label class="font-bold text-greyish_black text-sm mt-5" for="qualificationTxt">Qualification</label>
                    <textarea class="jobField block message-area border border-solid border-gray-400 h-40 w-4/5 rounded-lg mb-5
                      resize-none p-3 focus:outline-none text-greyish_black text-sm" name="qualificationTxt" id="qualificationTxt"></textarea>
                  </div>

                  <!-- salary -->
                  <div>
                    <label class="font-bold text-greyish_black text-sm mt-5 block" for="minSalary">Salary Range</label>
                    <div class="flex gap-2 mt-2 items-center">
                      <div class="w-1/4 p-2 border border-grayish rounded-md flex items-center gap-2">
                        <i class="fa-solid fa-peso-sign" style="color: #727274;"></i>
                        <input class="jobField w-full" type="number" name="minSalary" id="minSalary" value="0">
                      </div>
                      -
                      <div class="w-1/4 p-2 border border-grayish rounded-md flex items-center gap-2">
                        <i class="fa-solid fa-peso-sign" style="color: #727274;"></i>
                        <input class="jobField w-full" type="number" name="maxSalary" id="maxSalary" value="0">
                      </div>
                    </div>
                  </div>

                </div>

              </div>

              <!-- tags -->
              <div>
                <label class="font-bold text-greyish_black text-sm mt-5 block" for="inputSkill">Tags</label>
                <div id="skillDiv" class="flex flex-wrap">
                  <div>
                    <img class="h-12 w-12 inline cursor-pointer" src="../assets/icons/add-circle.png">
                    <input id="inputSkill" class="inputSkill skillInput jobField" type="text" placeholder="Add skill/s that needed">
                  </div>
                  <div>
                    <img class="h-12 w-12 inline cursor-pointer" src="../assets/icons/add-circle.png">
                    <input id="inputSkill2" class="inputSkill skillInput jobField" type="text" placeholder="Add skill/s that needed">
                  </div>
                  <div>
                    <img class="h-12 w-12 inline cursor-pointer" src="../assets/icons/add-circle.png">
                    <input id="inputSkill3" class="inputSkill skillInput jobField" type="text" placeholder="Add skill/s that needed">
                  </div>
                </div>
              </div>

              <div class="flex justify-start">
                <button type="submit" class="bg-postButton px-4 py-2 mt-5 hover:bg-postHoverButton text-white rounded-md text-sm">Make
                  a post</button>
              </div>
            </form>
          </div>

          <!-- admin job post -->
          <div id="adminJobPost" class="mt-10 w-full hidden h-4/5 overflow-y-auto">
            <div id="adminJobPostCont" class="grid grid-cols-4 gap-4 p-7"></div>
          </div>

        </div>
      </main>
    </div>


    <!-- modal -->
    <div id="modal" class="modal fixed inset-0 h-full w-full flex items-center justify-center 
      text-grayish  top-0 left-0 hidden">
      <div class="modal-container w-1/3 h-2/3 bg-white rounded-lg p-3">
        <div class="modal-header py-5">
          <h1 class="text-accent text-2xl text-center font-bold">Create New Post</h1>
        </div>
        <div class="modal-body px-3">

          <!-- header part -->
          <p class="font-semibold text-sm">College</p>
          <div class="w-full border border-gray-400 rounded flex px-5 py-2">
            <select name="collegePost" id="collegePost" class="w-full outline-none">
              <option value="all" selected>All colleges</option>
              <?php
              require_once '../PHP_process/connection.php';
              $query = "SELECT * FROM `college`";
              $result = mysqli_query($mysql_con, $query);
              $rows = mysqli_num_rows($result);

              if ($rows > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                  $colCode = $data['colCode'];
                  $colName = $data['colname'];

                  echo '<option value="' . $colCode . '"class="w-full">' . $colName . '</option>';
                }
              } else echo '<option>No college available</option>';
              ?>
            </select>
          </div>

          <!-- body part -->
          <p class="font-semibold text-sm mt-2">Description</p>
          <div class="modal-descript relative w-full h-2/3 border border-gray-400 rounded p-3">
            <div class="flex flex-col h-full">
              <textarea id="TxtAreaAnnouncement" class="rar outline-none w-full h-5/6 p-1" type="text" placeholder="Say something here..."></textarea>
              <div id="imgContPost" class=" hidden flex overflow-x-scroll w-full"></div>
              <p class="text-sm text-red-400 hidden" id="errorMsg">Sorry we only allow images that has file extension of
                jpg,jpeg,png</p>
            </div>

            <label for="fileGallery">
              <span id="galleryLogo" class="absolute bottom-1 left-1">
                <svg width="22" height="22" viewBox="0 0 22 22" fill="blue" xmlns="http://www.w3.org/2000/svg">
                  <path d="M17 7C17 7.53043 16.7893 8.03914 16.4142 8.41421C16.0391 8.78929 15.5304 9 15 9C14.4696 9 13.9609 8.78929 13.5858 8.41421C13.2107 8.03914 13 7.53043 13 7C13 6.46957 13.2107 5.96086 13.5858 5.58579C13.9609 5.21071 14.4696 5 15 5C15.5304 5 16.0391 5.21071 16.4142 5.58579C16.7893 5.96086 17 6.46957 17 7Z" fill="#BCBCBC" />
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M10.943 0.25H11.057C13.366 0.25 15.175 0.25 
                    16.587 0.44C18.031 0.634 19.171 1.04 20.066 1.934C20.961 2.829 21.366 3.969 21.56 5.414C21.75 6.825 21.75 8.634 21.75 10.943V11.031C21.75 12.94 21.75 14.502 21.646 15.774C21.542 17.054 21.329 18.121 20.851 19.009C20.641 19.4 20.381 19.751 20.066 20.066C19.171 20.961 18.031 21.366 16.586 21.56C15.175 21.75 13.366 21.75 11.057 
                    21.75H10.943C8.634 21.75 6.825 21.75 5.413 21.56C3.969 21.366 2.829 20.96 1.934 20.066C1.141 19.273 0.731 18.286 0.514 17.06C0.299 15.857 0.26 14.36 0.252 12.502C0.25 12.029 0.25 11.529 0.25 11.001V10.943C0.25 8.634 0.25 6.825 0.44 5.413C0.634 3.969 1.04 2.829 1.934 1.934C2.829 1.039 3.969 0.634 5.414 0.44C6.825 0.25 8.634 0.25 10.943 0.25ZM5.613 1.926C4.335 2.098 3.564 2.426 2.995 2.995C2.425 3.565 2.098 4.335 1.926 5.614C1.752 6.914 1.75 8.622 1.75 11V11.844L2.751 10.967C3.1902 10.5828 3.75902 10.3799 4.34223 10.3994C4.92544 10.4189 5.47944 10.6593 5.892 11.072L10.182 15.362C10.5149 15.6948 10.9546 15.8996 11.4235 15.9402C11.8925 15.9808 12.3608 15.8547 12.746 15.584L13.044 15.374C13.5997 14.9835 14.2714 14.7932 14.9493 14.834C15.6273 14.8749 16.2713 15.1446 16.776 15.599L19.606 18.146C19.892 17.548 20.061 16.762 20.151 15.653C20.249 14.448 20.25 12.946 20.25 11C20.25 8.622 20.248 6.914 20.074 5.614C19.902 4.335 19.574 3.564 19.005 2.994C18.435 2.425 17.665 2.098 16.386 1.926C15.086 1.752 13.378 1.75 11 1.75C8.622 1.75 6.913 1.752 5.613 1.926Z" fill="#BCBCBC" />
                </svg>
              </span>
            </label>
            <input id="fileGallery" type="file" class="hidden" />
          </div>


        </div>

        <!-- Footer -->
        <div class="modal-footer flex items-end flex-row-reverse px-3">
          <button id="postBtn" class="bg-accent py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
          <button class="cancel py-2 rounded px-5 text-grayish border border-slate-400 hover:bg-slate-400 hover:text-white">Cancel</button>
        </div>
      </div>
    </div>


    <!-- modal add email message -->
    <div id="modalEmail" class="modal fixed inset-0 h-full w-full flex items-center justify-center 
      text-grayish  top-0 left-0 hidden">
      <form id="emailForm" class="modal-container w-1/3 h-max bg-white rounded-lg p-3">
        <div class="w-full h-full">
          <div class="modal-header py-5">
            <h1 class="text-accent text-2xl text-center font-bold">Send Mail</h1>
          </div>
          <div class="modal-body px-3 h-1/2">

            <!-- header part -->
            <div class="flex gap-2 justify-start mb-2">
              <p class="font-semibold text-sm">Recipient</p>
              <input type="radio" id="groupEM" name="recipient" checked value="groupEmail">
              <label for="groupEM">Group</label>

              <input type="radio" id="individEM" name="recipient" value="individualEmail">
              <label for="individEM">individual</label>
            </div>


            <div id="groupEmail" class="flex gap-1">
              <div class=" border border-gray-400 rounded flex px-2 py-2 selectColWrapper">
                <select name="selectColToEmail" id="selectColToEmail" class="w-full outline-none">
                  <option value="" disabled selected>College</option>
                  <?php
                  require_once '../PHP_process/connection.php';
                  $query = "SELECT * FROM `college`";
                  $result = mysqli_query($mysql_con, $query);
                  $rows = mysqli_num_rows($result);

                  if ($rows > 0) {
                    while ($data = mysqli_fetch_assoc($result)) {
                      $colCode = $data['colCode'];
                      $colName = $data['colname'];

                      echo '<option value="' . $colCode . '">' . $colName . '</option>';
                    }
                  } else echo '<option>No college available</option>';
                  ?>
                </select>
              </div>


              <div class="flex gap-1 items-center">

                <!-- alumni -->
                <input id="alumniEM" name="selectedUser" type="radio" value="alumni">
                <label for="alumniEM">Alumni</label>

                <!-- student -->
                <input id="studentEM" name="selectedUser" type="radio" value="Student">
                <label for="studentEM">Student</label>
              </div>

            </div>


            <div class="relative">
              <div id="individualEmail" class="flex border border-gray-400 w-full rounded-md p-1 hidden">
                <img class="inline" src="../images/search-icon.png" alt="">
                <input class="focus:outline-none w-full" type="text" name="searchEmail" id="searchEmail" placeholder="Search email!">
              </div>
              <p id="userNotExist" class="text-sm italic text-accent hidden">User not exist</p>
              <div id="suggestionContainer" class="absolute p-2 w-full rounded-md z-10 h-56 overflow-y-auto hidden"></div>
            </div>

            <p class="font-semibold text-sm mt-2">Subject</p>
            <input class="focus:outline-none w-full border border-gray-400 rounded-md py-2 px-1" type="text" name="emailSubj" id="emailSubj" placeholder="Introduce something great!">

            <!-- body part -->
            <p class="font-semibold text-sm mt-2">Description</p>
            <div class="modal-descript relative w-full h-max border border-gray-400 rounded p-3">
              <div class="flex flex-col h-full">
                <textarea id="TxtAreaEmail" class="rar outline-none w-full h-48 p-1 border-b border-gray-300" type="text" placeholder="Say something here..."></textarea>
              </div>
            </div>
            <div id="imgContEmail" class=" hidden flex overflow-x-auto w-full"></div>
            <div id="fileContEmail" class="hidden"></div>
            <p class="text-sm text-red-400 hidden" id="errorMsgEM">Sorry we only allow images that has file extension of
              jpg,jpeg,png</p>
            <label class="flex justify-start items-center gap-3 mt-1">
              <i id="galleryIcon" class="fa-solid fa-image"></i>
              <i id="fileIcon" class="fa-solid fa-paperclip"></i>
            </label>
            <input id="imageSelection" type="file" class="hidden">
            <input id="fileSelection" type="file" class="hidden" accept="application/pdf">
          </div>

          <!-- Footer -->
          <div class="modal-footer flex items-end flex-row-reverse px-3 mt-2">
            <button type="submit" id="sendEmail" class="bg-accent h-full py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Send</button>
            <button type="button" class="cancelEmail py-2 rounded px-5  hover:bg-slate-400 hover:text-white">Cancel</button>
          </div>
        </div>
      </form>
    </div>


    <!-- View profile modal -->
    <div id="viewProfile" class="modal fixed inset-0 h-full w-full flex items-start justify-center 
        text-grayish  top-0 left-0 hidden">
      <div class="modal-container h-max w-1/3 bg-white rounded-lg mt-10">

        <div class="w-full h-max rounded-lg">

          <div class="w-full rounded-lg">
            <img class="h-36 w-full rounded-t-lg" src="https://i.pinimg.com/564x/c6/b0/c8/c6b0c8a8a0113922e3847e4257c6612c.jpg" alt="">
          </div>

          <div class="relative">
            <div class="flex">
              <img class="rounded-full ml-5 border-2 border-accent h-24 w-24 absolute -top-10" alt="">
              <div class="flex justify-start items-center w-full">

                <div class="ml-28">
                  <p class="p-2 font-bold w-full text-sm">Jayson Batoon</p>
                  <span class="ml-2 p-1 rounded-lg text-xs bg-green-300 text-green-600">STUDENT</span>
                </div>

              </div>

            </div>

            <div class="details p-5">
              <div class="flex">
                <img class="w-4 ml-1" src="../assets/icons/Location.png" alt="">
                <p class="text-sm ml-3">Lives in <span class="text-grayish font-semibold">Malolos, Bulacan</span></p>
              </div>

              <div class="flex mt-2">
                <img src="../images/graduate-cap.svg" alt="">
                <p class="text-sm ml-3">Student of <span class="text-grayish font-semibold">CICT</span></p>
              </div>
            </div>
          </div>

        </div>
        <!-- Footer -->
        <div class="modal-footer flex items-end flex-row-reverse px-3 mt-3">
          <button class="closeProfile py-2 rounded px-5 text-grayish border">Close</button>
        </div>

      </div>
    </div>

    <!-- View job post modal -->
    <div id="viewJob" class="modal fixed inset-0 h-full w-full flex items-start justify-center 
          top-0 left-0 p-5 hidden overflow-y-auto">
      <!-- modal body -->
      <div id="viewingJobModal" class="w-2/5 bg-white rounded-lg h-4/5 slide-bottom">

        <div class="flex flex-col justify-between h-full">
          <div class=" overflow-y-auto no-scrollbar">
            <!-- content -->
            <div class="headerJob flex rounded-t-lg p-5 w-full bg-accent">
              <div class="w-3/5 ps-3">
                <span id="viewJobColText" class="text-2xl font-bold text-gray-200"></span>
                <div class="flex gap-2 items-center">
                  <p class="text-sm text-white">Company Name: </p>
                  <p id="viewJobColCompany" class="text-sm font-bold text-white">Admin</p>
                </div>
                <div id="skillSets" class="flex flex-wrap gap-2 text-white text-xs my-1 "></div>
              </div>


            </div>

            <div class="p-5">
              <p class="text-gray-500 font-bold text-xl">Project Overview</p>
              <pre id="jobOverview" class="text-sm h-max w-full indented text-gray-500 mb-2 mt-1"></pre>

              <p class="text-gray-500 font-bold text-xl">Qualification</p>
              <pre id="jobQualification" class="text-sm h-max indented text-gray-500 mb-2 mt-1"></pre>

              <p class="text-gray-500 font-bold text-xl">LOCATION</p>
              <span id="locationJobModal" class="text-gray-500 text-sm my-1"></span>
            </div>
          </div>

          <div class="p-3 text-gray-400 border-t border-gray-400 flex justify-between">
            <button id="aplicantListBtn" class="flex items-center gap-2 cursor-text">
              <iconify-icon icon="uiw:user" style="color: #868e96;" width="18" height="18"></iconify-icon>
              <span id="jobApplicant"></span>
            </button>

            <div class="flex items-center gap-2">
              <iconify-icon icon="ri:verified-badge-line" style="color: #868e96;" width="18" height="18"></iconify-icon>
              <span id="statusJob"></span>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- list of applicant -->
    <div id="listOfApplicantModal" class="post modal fixed inset-0 flex justify-center p-3 hidden">
      <div class="modaListApplicant modal-container w-1/3 h-max bg-white rounded-md relative slide-bottom p-5">
        <button class="p-1 text-gray-300 items-center justify-center flex border border-gray-400 rounded-full hover:bg-accent absolute top-0 -right-11">
          <iconify-icon icon="ei:close" width="24" height="24"></iconify-icon>
        </button>
        <h3 class="text-center font-bold text-xl py-2 text-greyish_black border-b border-gray-300">Applicant List</h3>

        <div id="listApplicantContainer" class="modaListApplicant overflow-y-auto h-max p-3 flex flex-col gap-3"></div>

      </div>
    </div>

    <!-- modal promp -->
    <div id="promptMessage" class="modal fixed inset-0 h-full w-full flex items-start justify-center 
        text-grayish  top-0 left-0 hidden ">
      <div class="modal-container w-1/3 bg-white rounded-lg p-3 mt-2">
        <div class="modal-header py-5">
          <p id="insertionMsg" class="text-greyish_black font-bold text-lg text-center w-1/2 mx-auto"></p>
        </div>

        <!-- Footer -->
        <div class="modal-footer flex items-end flex-row-reverse px-3 mt-3">
          <button id="goBack" class="bg-accent py-2 rounded px-5 text-white ms-3 hover:bg-darkAccent 
                        hover:font-semibold">
            Go back
          </button>
        </div>
      </div>
    </div>

    <!-- news and update modal -->
    <div id="newsUpdateModal" class="modal fixed inset-0 h-full w-full flex items-center justify-center hidden p-10">
      <!-- container -->
      <div id="newsContainer" class="w-2/6 h-max bg-white rounded-sm overflow-y-auto py-3 px-5 max-h-full">
        <!-- header -->
        <div class="flex gap-2 items-center py-2">
          <img src="../images/BSU-logo.png" alt="Logo" class="w-10 h-10" />
          <span class="ml-2 text-xl font-bold">BulSU Update</span>
        </div>

        <div class="relative rounded-md h-48">
          <img id="imgHeader" class="h-full w-full rounded-md border">
          <input type="file" name="headerImg" id="headerImg" class="hidden" accept="image/*">
          <label for="headerImg" class="headerLbl absolute top-1/2 w-full text-center cursor-pointer text-gray-400">Add Header Image</label>
        </div>
        <input class="text-center w-full outline-none text-lg font-bold text-greyish_black my-2" type="text" id="newsTitle" placeholder="Title for this announcement">
        <textarea id="newstTxtArea" class=" w-full h-max resize-none p-2 text-sm text-gray-500 outline-none  overflow-y-hidden rounded-sm" oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' name="" id="" placeholder="Add your description here"></textarea>

        <p class="text-blue-400 text-sm my-2">Add more images ( Optional )</p>
        <!-- image collection -->
        <div id="collectionContainer" class="flex flex-wrap gap-2">
          <!-- adding image -->
          <div id="addImgCollection" class=" w-24 h-24 rounded-md border border-accent flex justify-center items-center">
            <label for="collectionFile">
              <iconify-icon icon="zondicons:add-outline" style="color: #991b1b;" width="32" height="32"></iconify-icon>
            </label>
            <input type="file" name="" id="collectionFile" accept="image/*" class="hidden">
          </div>
        </div>

        <div class="flex justify-end items-center gap-2">
          <button id="closeNewsModal" class="text-gray-400">Cancel</button>
          <button id="postNewsBtn" class="text-gray-300  bg-red-300 font-semibold px-5 py-1 rounded-md" disabled>Post</button>
        </div>
      </div>

    </div>

    <!-- post modal -->
    <div id="modalPost" class="modal fixed inset-0 z-50 h-full w-full p-3 hidden">
      <div class="modal-container w-full h-full bg-white rounded-lg flex relative">
        <span id="closePostModal" class="absolute top-0 right-0 text-center text-2xl cursor-pointer p-3 hover:scale-50 hover:font-bold">x</span>
        <div id="containerSection" class="w-8/12 h-full ">

          <div id="default-carousel" class="relative w-full h-full bg-black" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="overflow-hidden rounded-lg h-full" id="carousel-wrapper"></div>
            <!-- Slider indicators -->
            <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2" id="carousel-indicators">
            </div>
            <!-- Slider controls -->
            <button id="btnPrev" type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none hover:bg-gray-500 hover:bg-opacity-20" data-carousel-prev>
              <span class="inline-flex items-center justify-center w-10 h-10 ">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                  <path fill="white" d="m4 10l9 9l1.4-1.5L7 10l7.4-7.5L13 1z" />
                </svg>
                <span class="sr-only">Previous</span>
              </span>
            </button>
            <button id="btnNext" type="button" class="text-white absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none hover:bg-gray-500 hover:bg-opacity-20">
              <span class="inline-flex items-center justify-center w-10 h-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="none" stroke="currentColor" stroke-width="2" d="m7 2l10 10L7 22" />
                </svg>
                <span class="sr-only">Next</span>
              </span>

            </button>
          </div>

        </div>

        <!-- description -->
        <div id="descriptionInfo" class="w-4/12 h-full p-2 border-l p-3 border-gray-400">
          <div class="flex justify-start gap-2">
            <img id="profilePic" class="rounded-full border-2 border-accent h-10 w-10" src="" alt="">
            <div class="flex flex-col">
              <span id="postFullName" class=" text-greyish_black font-bold"></span>
              <span id="postUN" class=" text-gray-400 text-xs">username</span>
            </div>
          </div>
          <p id="postDescript" class=" text-greyish_black font-light text-sm">Description</p>

          <div class="relative">

            <div class="flex justify-end gap-2 border-t border-gray-400 mt-5 items-center text-gray-400 text-sm py-2 px-3">
              <img src="../assets/icons/emptyheart.png" alt="">
              <span id="noOfLikes" class="cursor-pointer w-10 text-center"></span>
              <img src="../assets/icons/comment.png" alt="">
              <span id="noOfComment">0</span>
            </div>
            <div id="namesOfUser" class="absolute -bottom-2 right-0 bg-black opacity-25 text-gray-300 w-1/3 text-xs p-2 rounded-md hidden"></div>
          </div>

          <!-- comments -->
          <div id="commentContainer" class=" h-3/4 p-2 overflow-auto"></div>
        </div>
      </div>
    </div>

    <!-- status modal -->
    <div id="postStatusModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
      <div class="postStatus bg-white rounded-md w-2/6 p-5 flex flex-col gap-3">
        <div class="flex justify-between">
          <div class="flex items-center">
            <img id="profileStatusImg" class="w-10 h-10 rounded-full" alt="" srcset="">

            <div class="px-2 text-greyish_black">
              <p id="statusFullnameUser" class="font-bold text-sm"></p>
              <span class="italic accountUN text-gray-400 text-sm"></span>
            </div>
          </div>

          <iconify-icon class="closeStatusPost cursor-pointer text-gray-400 hover:text-gray-500 hover:h-7 hover:w-7" icon="ei:close" width="24" height="24"></iconify-icon>
        </div>

        <!-- description -->
        <div>
          <pre id="statusDescript"></pre>
        </div>

        <!-- date -->
        <span id="statusDate" class="text-xs text-gray-500"></span>

        <!-- comment -->
        <div class="flex-col text-sm border-t border-gray-400 py-2 h-full overflow-y-auto">
          <div class="flex gap-2 text-gray-500 text-xs">
            <p>Likes: <span id="statusLikes"></span></p>
            <p>Comments: <span id="statusComment"></span></p>
          </div>

          <div id="commentStatus" class="flex flex-col gap-2 p-2 mt-2"></div>
        </div>
      </div>
    </div>

    <!-- EDIT PROFILE -->
    <div id="profileModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
      <div class="formUpdate bg-white rounded-md w-2/6 h-max p-5 flex flex-col gap-3">
        <!-- profile picture -->
        <div class="flex justify-between text-greyish_black items-center">
          <p class="text-lg font-bold">Profile Picture</p>
          <label id="profileLbl" for="profileFile">
            <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
          </label>
          <input type="file" name="profilePic" id="profileFile" class="hidden" accept="image/*">
        </div>
        <!-- Profile Photo (Intersecting with the Cover Photo) -->
        <div class="h-48 w-full flex justify-center">
          <?php
          if ($profilepicture == "") {
            echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-48 h-48 rounded-full" id="profileImgEdit" />';
          } else {
            $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
            echo '<img src="' . $srcFormat . '" alt="Profile Icon" class=" w-48 h-48 rounded-full " id="profileImgEdit"/>';
          }
          ?>
        </div>

        <div id="profileBtn" class="flex justify-end gap-2 hidden">
          <button id="cancelProfileImg" class="text-postButton hover:text-postHoverButton px-4 rounded-md py-2">Cancel</button>
          <button class=" bg-postButton hover:bg-postHoverButton px-4 rounded-md text-white py-2" id="saveProfile">Save</button>
        </div>

        <p class="text-lg font-bold">Customize Your Information</p>

        <!-- location -->
        <div class="flex justify-between text-greyish_black items-center">
          <div class="flex items-center gap-2">
            <span class="text-sm font-thin flex items-center">
              <iconify-icon icon="fluent:location-20-regular" style="color: #474645;" width="20" height="20"></iconify-icon>
              Lives in
            </span>

            <?php
            echo '<input class="font-bold text-sm flex items-center" disabled type="text" id="editAddress" value="' . $address . '" placeholder = "' . $address . '" >';
            ?>

          </div>
          <label id="editAddLabel" for="editAddress">
            <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
          </label>
          <div id="locBtn" class="text-sm hidden">
            <button id="cancelLocation" class="px-2 py-1">cancel</button>
            <button class="bg-postButton hover:bg-postHoverButton rounded-md text-white px-2 py-1" id="saveLocation">Save</button>
          </div>

        </div>

        <!-- email address -->
        <div class="flex justify-between text-greyish_black items-center">
          <div class="flex items-center gap-2">
            <span class="text-sm font-thin flex items-center gap-1">
              <iconify-icon icon="formkit:email" style="color: #474645;" width="20" height="20"></iconify-icon>
              Email
            </span>

            <?php
            echo '<input class="font-bold text-sm flex items-center" disabled type="text" id="editEmail" value="' . $personal_email . '" placeholder = "' . $address . '" >';
            ?>

          </div>
          <label id="editEmailLbl" for="editEmail">
            <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
          </label>
          <div id="emailBtn" class="text-sm hidden">
            <button id="cancelEmail" class="px-2 py-1">cancel</button>
            <button class="bg-postButton hover:bg-postHoverButton rounded-md text-white px-2 py-1" id="saveEmail">Save</button>
          </div>

        </div>

        <!-- contact No -->
        <div class="flex justify-between text-greyish_black items-center">
          <div class="flex items-center gap-2">
            <span class="text-sm font-thin flex items-center gap-1">
              <iconify-icon icon="fluent:call-24-regular" style="color: #474645;" width="20" height="20"></iconify-icon>
              Contact No.
            </span>

            <?php
            echo '<input class="font-bold text-sm flex items-center" disabled type="text" id="editContact" value="' . $contactNo . '" placeholder = "' . $address . '" >';
            ?>

          </div>
          <label id="editContactLbl" for="editContact">
            <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
          </label>
          <div id="contactBtn" class="text-sm hidden">
            <button class="px-2 py-1" id="cancelContact">cancel</button>
            <button class="bg-postButton hover:bg-postHoverButton rounded-md text-white px-2 py-1" id="saveContact">Save</button>
          </div>

        </div>

        <div class=" text-greyish_black text-sm flex flex-col gap-2">
          <p class="text-lg font-bold">Account Setting</p>
          <p>Username: <span class="font-bold">isaganilapira09</span></p>
          <div class="flex justify-between items-center">
            <p>Password: <span class="font-bold">*****</span></p>
            <iconify-icon id="editPassword" class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
          </div>
        </div>


      </div>
    </div>


    <!-- log out -->
    <div id="signOutPrompt" class="modal fixed inset-0 z-50 h-full w-full flex items-center justify-center 
      text-grayish hidden">
      <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3">
        <p class="text-center font-medium text-greyish_black mb-7 mt-3">Are you sure you want to sign out?</p>
        <div class="flex gap-2 justify-end">
          <button id="cancelSignout" class="border border-gray-400 px-3 py-2 rounded-md hover:bg-gray-400 hover:text-white">Cancel</button>
          <button id="signoutBtn" class="text-white bg-accent px-3 py-2 rounded-md hover:bg-darkAccent">Sign out</button>

        </div>
      </div>
    </div>


    <!-- deletion modal -->
    <div id="delete-modal" class="modal hidden fixed inset-0 z-50 h-full w-full flex items-center justify-center ">
      <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
          <button type="button" class="closeDeleteBtn absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
          <div class="p-6 text-center">
            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this post?</h3>
            <button id="deletePostbtn" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
              Yes, I'm sure
            </button>
            <button type="button" class="closeDeleteBtn text-gray-400">No, cancel</button>
          </div>
        </div>
      </div>
    </div>

    <!-- success prompt -->
    <div id="successModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
      <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
        <svg class="block mx-auto" width="115px" height="115px" viewBox="0 0 133 133" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="check-group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <circle id="filled-circle" fill="#47CF73" cx="66.5" cy="66.5" r="54.5" />
            <circle id="white-circle" fill="#FFFFFF" cx="66.5" cy="66.5" r="55.5" />
            <circle id="outline" stroke="#47CF73" stroke-width="4" cx="66.5" cy="66.5" r="54.5" />
            <polyline id="check" stroke="#FFFFFF" stroke-width="5.5" points="41 70 56 85 92 49" />
          </g>
        </svg>
        <h1 class=" text-xl font-bold text-green-500 text-center">Successfully Posted</h1>
        <p class="text-lg text-center text-gray-500">"Post successfully posted! 🎉 Happy sharing!</p>
      </div>
    </div>

    <!-- announcement modal -->
    <div id="announcementModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
      <div id="announcementDetails" class="modal-container w-2/5 h-5/6 overflow-y-auto bg-white rounded-md py-3 px-12 text-greyish_black flex flex-col gap-2">
        <!-- header -->
        <div class="flex gap-2 items-center py-2">
          <img src="../images/BSU-logo.png" alt="Logo" class="w-10 h-10" />
          <span class="ml-2 text-xl font-bold">BulSU Update</span>
        </div>

        <!-- headline image -->
        <img id="headline_img" class="h-60 object-cover bg-gray-300 rounded-md" alt="">

        <p class="text-sm text-gray-500">Date Posted: <span id="announceDatePosted"></span></p>
        <p class="text-sm text-gray-500">By: <span id="announcementAuthor" class="text-accent"></span></p>

        <p id="announcementTitle" class="text-2xl text-greyish_black font-black"></p>
        <pre id="announcementDescript" class=" text-gray-500 text-justify w-full"></pre>

        <!-- images container -->
        <div id="imagesContainer" class="my-2">
          <p class="font-semibold text-blue-400">More images available</p>
          <div id="imagesWrapper" class="flex flex-wrap gap-2"></div>
        </div>
      </div>
    </div>

    <div id="restoreModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
      <div class="relative w-1/3 h-max p-3 bg-white rounded-md">
        <h1 class="text-xl text-greyish_black font-bold text-center py-2 border-b border-gray-400">Restore to Profile</h1>
        <p class="text-gray-500 text-sm">Items you restore to your profile can be seen by the audience that was selected
          before they were moved to archive.</p>
        <div class="flex justify-end gap-2 my-2">
          <button id="closeRestoreModal" class="px-3 py-2 rounded-md text-blue-400 hover:bg-gray-300 text-sm">Cancel</button>
          <button id="restorePost" class="px-4 py-2 text-white bg-postButton hover:bg-postHoverButton rounded-md font-semibold text-sm">Restore</button>
        </div>

        <svg id="closeRestore" class="cursor-pointer absolute top-2 right-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20">
          <path fill="#6b7280" d="M2.93 17.07A10 10 0 1 1 17.07 2.93A10 10 0 0 1 2.93 17.07zM11.4 10l2.83-2.83l-1.41-1.41L10 8.59L7.17 5.76L5.76 7.17L8.59 10l-2.83 2.83l1.41 1.41L10 11.41l2.83 2.83l1.41-1.41L11.41 10z" />
        </svg>
      </div>
    </div>


    <!-- section modal -->
    <div id="sectionModalcontainer" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
      <input type="hidden" id="catIDHolder">
      <input type="hidden" id="formIDHolder">
      <input type="hidden" id="choiceIDHolder">
      <div id="sectionModal" class="modal-container h-4/5 bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2 border-t-8 border-accent relative">
        <iconify-icon id="addSectionQuestion" title="Add new question for this section" class="iconAddModal p-3 rounded-md center-shadow h-max absolute top-1 right-1" icon="gala:add" style="color: #AFAFAF;" width="24" height="24"></iconify-icon>
        <header class="font-bold text-4xl text-center text-accent py-2 border-b border-gray-300">
          Section
        </header>
        <div id="sectionBody" class="h-full overflow-y-auto py-2 flex flex-col gap-2"></div>
      </div>
    </div>

    <!-- add new question -->
    <div id="newQuestionModal" class="modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
      <div class="modal-container w-1/3 h-max bg-white rounded-lg text-greyish_black">
        <header class="text-center text-lg font-bold py-3 mb-2">Add new Question</header>
        <div class="wrapper p-3 w-full mx-auto m-2">
          <input id="newQuestionInputName" type="text" class="w-full text-center text-lg border-b border-gray-300" placeholder="Untitled Question" />
          <!-- body -->
          <div class="p-3 text-gray-400 mb-2">
            <select id="inputTypeModalNew" class="w-full p-2 outline-none center-shadow mb-2">
              <option value="Radio">Radio Type</option>
              <option value="Input">Input Type</option>
              <option value="Checkbox">Chexbox Type</option>
              <option value="DropDown">Dropdown Type</option>
            </select>
            <!-- options -->
            <div class="optionContainer">
              <div class="fieldWrapper flex items-center gap-2">
                <iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>
                <input type="text" class="py-2 choicesVal w-full" placeholder="Add choice">
              </div>
            </div>
          </div>

          <button id="addOptionmodal" class="flex items-center gap-2 text-gray-400">
            <iconify-icon icon="gala:add" style="color: #afafaf;" width="20" height="20"></iconify-icon>
            Add option
          </button>

          <div class="flex items-center justify-end gap-2">
            <button id="closeQuestionModal" class="text-gray-400 hover:bg-gray-300 py-2 px-3">Cancel</button>
            <button id="saveNewQuestion" class="bg-blue-400 hover:bg-blue-500 px-4 py-2 rounded-md text-white">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- log history modal -->
    <div id="logHistoryModal" class="modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
      <div id="modalLogContainer" class="modal-container w-1/2 h-3/4 bg-white rounded-lg text-greyish_black p-3">
        <header class="font-bold text-accent text-xl text-center py-2">College Admin Activities</header>
        <!-- HISTORY LOGS -->
        <button id="printLogsBtn" class="pt-1 px-2 m-2 border border-grayish text-grayish rounded-md ml-auto block hover:bg-accent">
          <iconify-icon icon="mingcute:print-fill" style="color: #afafaf;" width="24" height="24"></iconify-icon>
        </button>


        <div class="border-b border-gray-400 py-1"></div>
        <div class="filter flex gap-2 mt-2">

          <select id="weekFilter" class="border border-gray-400 rounded-md p-2">
            <option value="1">Last 1 week</option>
            <option value="2">Last 2 week</option>
            <option value="3">Last 3 week</option>
            <option value="5">Last 1 month</option>
            <option value="10">Last 2 months</option>
            <option value="14">Last 3 months</option>
          </select>
          <!-- date range -->
          <div class="w-max flex border border-grayish p-2 rounded-lg">
            <input type="text" name="logdaterange" id="logdaterange" value="01/01/2018 - 01/15/2018" />
            <label for="logdaterange">
              <img class="h-5 w-5" src="../assets/icons/calendar.svg" alt="">
            </label>
          </div>

          <!-- college selection -->
          <select name="logCollege" id="logCollege" class="w-full border border-grayish p-2 rounded-lg">
            <option value="" selected>All</option>
            <?php
            require_once '../PHP_process/connection.php';
            $query = "SELECT * FROM `college`";
            $result = mysqli_query($mysql_con, $query);
            $rows = mysqli_num_rows($result);

            if ($rows > 0) {
              while ($data = mysqli_fetch_assoc($result)) {
                $colCode = $data['colCode'];
                $colName = $data['colname'];

                echo '<option value="' . $colCode . '">' . $colName . '</option>';
              }
            } else echo '<option>No college available</option>';
            ?>
          </select>

        </div>

        <div id="logList" class="overflow-y-auto py-2 flex flex-col gap-3">
          <!-- loading screen -->
          <div class="lds-roller relative w-4/5 flex ps-5 items-center justify-center h-1/2">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div>
        </div>

      </div>
    </div>

    <div id="deploymentModal" class="modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">

      <div id="modalConfirmDeployment" class="modal-container w-2/5 h-max bg-white rounded-lg text-greyish_black p-3">
        <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-300 py-3">Tracer Deploy Confirmation</h3>
        <p class="p-4 text-sm">You are about to deploy the Graduate Tracer, which will be accessible to all alumni users in the system.
          Please be aware that this deployment is valid for a limited period of 4 months. </p>

        <p class="p-4 text-sm">By confirming this action, you are distributing
          the Graduate Tracer to all alumni, enabling them to access valuable information and insights.
          It's essential to ensure that the data and content within the tracer are up-to-date and accurate for the
          benefit of our alumni community. </p>

        <p class="p-4 text-sm">Remember, this deployment is a significant step in keeping our alumni engaged and informed.
          Are you sure you want to proceed?</p>

        <div class="flex w-full justify-end items-center gap-2 my-3 border-t border-gray-300 py-2">
          <button id="cancelDeployBtn" class="text-gray-400 hover:text-gray-500">Cancel</button>
          <button id="confirmDeployTracerBtn" class="px-4 py-2 rounded-lg bg-green-400 hover:bg-green-500 text-white font-bold">Deploy</button>
        </div>
      </div>

    </div>

    <div id="insertCategoryModal" class="modal fixed inset-0 z-50 flex justify-center p-3 hidden">
      <div class="modal-container w-2/5 h-max bg-white rounded-lg text-greyish_black p-3">
        <h3 class="font-bold text-xl text-center text-greyish_black py-2 border-b border-gray-300">Insert New Category</h3>
        <input id="categoryInputVal" type="text" placeholder="Enter a new category..." class="w-full border-b border-gray-300 p-3 my-3">

        <div class="flex justify-end gap-2">
          <button id="cancelCatInsertion" class="text-gray-400 hover:text-gray-500">Cancel</button>
          <button id="addNewCategoryBtn" class="text-white bg-green-400 rounded-lg py-2 px-4 hover:bg-green-500 font-bold">Create</button>
        </div>
      </div>
    </div>

    <!-- deletion post modal -->
    <div class="deleteModalPost modal fixed inset-0 z-50 flex justify-center p-3 hidden">
      <div class="modal-container w-2/5 h-max bg-white rounded-lg text-greyish_black p-3 center-shadow slide-bottom">
        <h3 class="text-lg text-greyish_black text-center ">Are you sure you want to delete post this post?</h3>
        <input id="reasonForDel" class="text-gray-400 py-2 w-full text-center" type="text" placeholder="State your reason for deleting">
        <div class="flex items-center justify-end my-2 gap-2">
          <button class="text-gray-400 hover:text-gray-500 cancelDeletionAdmin">Cancel</button>
          <button id="deleteByAdminBtn" class="bg-accent py-1 px-4 text-white hover:bg-darkAccent rounded-lg">Delete</button>
        </div>
      </div>
    </div>

    <!-- deletion question modal -->
    <div class="deleteQuestionPost modal fixed inset-0 z-50 flex justify-center p-3 hidden">
      <div class="modal-container w-1/3 h-max bg-white rounded-lg text-greyish_black p-3 center-shadow slide-bottom">
        <h3 class="text-lg text-greyish_black text-center ">Are you sure you want to delete this question?</h3>
        <div class="flex flex-col justify-end mt-7 gap-2 w-4/5 mx-auto">
          <button id="deleteQuestionBtn" class="bg-accent py-1 px-4 text-white hover:bg-darkAccent rounded-lg">Delete</button>
          <button id="cancelDelQuestionBtn" class="text-gray-400 hover:text-gray-500">Cancel</button>
        </div>
      </div>
    </div>

    <div id="viewResumeModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
      <div class="fixed h-max w-full bg-black bg-opacity-50  flex justify-between p-3 top-0 gap-2">
        <button id="closeViewResume" class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-gray-300 hover:bg-opacity-50">
          <iconify-icon icon="fluent-mdl2:back" style="color: white;" width="24" height="24"></iconify-icon>
        </button>

        <div class="flex gap-2">
          <button id="downloadResume" class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-gray-300 hover:bg-opacity-50">
            <iconify-icon icon="teenyicons:download-outline" style="color: white;" width="24" height="24"></iconify-icon>
          </button>
          <button id="printResume" class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-gray-300 hover:bg-opacity-50">
            <iconify-icon icon="fluent:print-32-regular" style="color: white;" width="24" height="24"></iconify-icon>
          </button>
        </div>

      </div>
      <div class="bg-white p-5 h-full overflow-y-auto w-2/3">
        <div id="resumeWrapperModal">
          <main class="flex">
            <aside class="w-2/6 text-greyish_black p-3 flex flex-col gap-4 text-xs">
              <header id="fullnameResume" class="text-3xl block font-bold"></header>
              <!-- contact Section -->
              <section class="flex flex-col gap-2">
                <h1 class="font-bold text-base">CONTACT</h1>

                <!-- contact number -->
                <span class="flex gap-4 items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <g fill="none" fill-rule="evenodd">
                      <path d="M24 0v24H0V0h24ZM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093c.012.004.023 0 .029-.008l.004-.014l-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014l-.034.614c0 .012.007.02.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01l-.184-.092Z" />
                      <path fill="#555" d="M16.552 22.133c-1.44-.053-5.521-.617-9.795-4.89c-4.273-4.274-4.836-8.354-4.89-9.795c-.08-2.196 1.602-4.329 3.545-5.162a1.47 1.47 0 0 1 1.445.159c1.6 1.166 2.704 2.93 3.652 4.317a1.504 1.504 0 0 1-.256 1.986l-1.951 1.449a.48.48 0 0 0-.142.616c.442.803 1.228 1.999 2.128 2.899c.901.9 2.153 1.738 3.012 2.23a.483.483 0 0 0 .644-.162l1.27-1.933a1.503 1.503 0 0 1 2.056-.332c1.407.974 3.049 2.059 4.251 3.598a1.47 1.47 0 0 1 .189 1.485c-.837 1.953-2.955 3.616-5.158 3.535Z" />
                    </g>
                  </svg>
                  <span id="contactNoResume" class="font-thin">09104905440</span>
                </span>

                <!-- email address -->
                <span class="flex gap-4 items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                    <path fill="#555" d="M19 14.5v-9c0-.83-.67-1.5-1.5-1.5H3.49c-.83 0-1.5.67-1.5 1.5v9c0 .83.67 1.5 1.5 1.5H17.5c.83 0 1.5-.67 1.5-1.5zm-1.31-9.11c.33.33.15.67-.03.84L13.6 9.95l3.9 4.06c.12.14.2.36.06.51c-.13.16-.43.15-.56.05l-4.37-3.73l-2.14 1.95l-2.13-1.95l-4.37 3.73c-.13.1-.43.11-.56-.05c-.14-.15-.06-.37.06-.51l3.9-4.06l-4.06-3.72c-.18-.17-.36-.51-.03-.84s.67-.17.95.07l6.24 5.04l6.25-5.04c.28-.24.62-.4.95-.07z" />
                  </svg>
                  <span id="emailAddResume" class=" font-thin">lapiraisagani@gmail.com</span>
                </span>

                <!-- location -->
                <span class="flex gap-4 items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 16 16">
                    <path fill="#555" d="M9.156 14.544C10.899 13.01 14 9.876 14 7A6 6 0 0 0 2 7c0 2.876 3.1 6.01 4.844 7.544a1.736 1.736 0 0 0 2.312 0ZM6 7a2 2 0 1 1 4 0a2 2 0 0 1-4 0Z" />
                  </svg>
                  <span id="addressResume" class="font-thin">56 Vinta Street, Mabolo, Malolos Bulacan</span>
                </span>
              </section>

              <!-- education -->
              <section id="educationContainer" class="flex flex-col gap-2 z-50">
                <h1 class="font-bold text-base">EDUCATION</h1>

                <div id="primaryLvl" class="font-thin"></div>
                <div id="secondaryLvl" class="font-thin"></div>
                <div id="tertiaryLvl" class="font-thin"></div>
              </section>


              <!-- skills -->
              <section>
                <h1 class="font-bold text-base">SKILLS</h1>

                <div id="skillWrapper" class="flex flex-col gap-2 z-50"></div>
              </section>
            </aside>

            <aside class="w-4/6 text-greyish_black text-xs p-3">
              <!-- objective -->
              <section>
                <h1 class="font-bold text-base">OBJECTIVE</h1>
                <p id="objectiveResume" class="my-2"></p>
              </section>

              <!-- work experience -->
              <section class="my-2 workExp">
                <h1 class="font-bold text-base mt-5">WORK EXPERIENCE</h1>
                <ul id="workExpList" class="p-3 flex flex-col gap-2"></ul>
              </section>

              <!-- reference -->
              <section class="my-2">
                <h1 class="font-bold text-base">REFERENCES</h1>

                <div id="referenceContainer" class="flex flex-col gap-4 my-2"></div>
              </section>
            </aside>
          </main>

        </div>

      </div>
    </div>

    <!-- comment -->
    <div id="commentPost" class="post modal fixed inset-0 flex justify-center p-3 z-50 hidden">
      <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 flex flex-col gap-1 slide-bottom">
        <!-- close button -->
        <span class="flex justify-end">
          <iconify-icon id="closeComment" class="rounded-full cursor-pointer p-2 hover:bg-gray-300" icon="ep:close" style="color: #686b6f;" width="20" height="20"></iconify-icon>
        </span>

        <div class="flex gap-2 items-center">
          <img id="postProfile" class="h-10 w-10 rounded-full" src="../assets/icons/person.png" alt="">
          <div>
            <p id="postFullname" class="text-black"></p>
            <p id="postUsername" class="text-xs text-gray-400 font-thin"></p>
          </div>
        </div>

        <div id="replacementComment" class="border-l-2 border-gray-400 w-max ml-5 p-3">
          <p class="text-center text-sm italic text-gray-400">Reply to
            <span id="replyToUsername" class=" font-semibold text-blue-500">username</span>
          </p>
        </div>
        <div class="flex gap-2 ">
          <?php
          if ($profilepicture == "") {
            echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 profile-icon rounded-full" />';
          } else {
            $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
            echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-10 h-10 profile-icon rounded-full" />';
          }
          ?>
          <textarea id="commentArea" class="w-full h-28 outline-none text-gray-400" placeholder="Comment your thought!"></textarea>
        </div>

        <button id="commentBtn" class="px-3 py-2 rounded-lg bg-red-950 text-white font-semibold block ml-auto text-sm" disabled>Comment</button>
      </div>
    </div>

    <!-- loading screen -->
    <div id="loadingScreen" class="post modal fixed inset-0 flex flex-col justify-center items-center p-3 z-50 hidden">
      <span class="loader w-36 h-36"></span>
      <span class="text-lg font-bold text-white my-2 italic">"We promise it's worth the wait!"</span>
    </div>

    <div id="sectionModalPreview" class=" bg-black bg-opacity-50 fixed inset-0 flex flex-col items-center p-3 z-50 hidden">
      <div class="sectionModalPreview bg-white rounded shadow-lg w-2/5 h-max slide-bottom p-5">
        <h3 class="font-bold text-xl text-accent py-2 text-center">Additional Question</h3>

        <!-- close button -->
        <button class="closeSectionModal absolute top-1 right-1">
          <iconify-icon class="text-gray-400 hover:text-accent" icon="carbon:close-outline" width="24" height="24"></iconify-icon>
        </button>

        <div id="previewSectionQuestion" class="p-3 flex flex-col gap-3 border border-gray-300 rounded-md overflow-y-auto"></div>
        <button class="text-gray-500 border hover:font-semibold py-2 px-4 rounded-md my-2 block ml-auto">Close</button>
      </div>
    </div>

    <!-- alumni of the year confirmation -->
    <div class=" bg-black bg-opacity-50 fixed inset-0 flex flex-col items-center p-3 z-50 hidden">
      <div class="alumniOfYearModal bg-white rounded shadow-lg w-2/5 h-max slide-bottom p-5">
        <h2 class="text-center text-lg italic text-green-400 font-bold">Alumni of the Year! </h2>
        <p class="text-center text-gray-500 text-sm">Assigning this person will be permanently in the list of Alumni of the year. Add reason why you choosed this person</p>
        <input id="reasonForAOY" type="text" placeholder="Reason for assigning this alumni as Alumni of the year" class="w-full p-5 border-b border-gray-400 text-center">
        <button class="text-white bg-blue-400 hover:bg-postHoverButton rounded-md mt-5 w-full py-2 assignAOY">Assign</button>
        <button class="text-gray-400 hover:text-gray-500 hover:font-semibold w-full mt-2 cancelAOY">Cancel</button>
      </div>
    </div>

    <!-- alumni of the year -->
    <div class=" bg-black bg-opacity-50 fixed inset-0 flex flex-col items-center p-3 z-50 hidden aoyModal">
      <div class="w-1/2 rounded-md h-4/5 bg-white p-5 overflow-y-auto aoyModalContainer">
        <h3 class="text-center text-greyish_black font-semibold text-xl">Alumni of the year</h3>
        <!-- content -->
        <div class=" flex flex-col gap-2 items-center">
          <img class="rounded-full h-44 w-44 border border-accent coverImgAOY" src="" alt="">
          <p class="text-lg font-bold text-greyish_black fullnameAOY">Full name</p>
          <p class="w-1/2 italic quotationAOY text-center"></p>
          <h2 class="text-greyish_black font-bold text-center text-lg">Testimonials</h2>
          <div class="flex flex-wrap justify-center gap-2 testimonyWrapper p-3"></div>
          <h2 class="text-greyish_black font-bold text-center text-lg">Achievements</h2>
          <div class="flex justify-center flex-wrap gap-2 achievementWrapper"></div>
          <!-- skills -->
          <h2 class="text-greyish_black font-bold text-center text-lg">Skills</h2>
          <div class="flex flex-wrap gap-2 justify-center skillWrapper flex-1 mb-5"></div>

          <!-- social media -->
          <h2 class="text-greyish_black font-bold text-center text-lg">Social Media</h2>
          <div class="flex flex-wrap justify-center gap-2 socMedWrapper flex-1"></div>

        </div>
      </div>
    </div>

    <!-- email details -->
    <div class=" bg-black bg-opacity-50 fixed inset-0 flex flex-col items-center p-3 z-50 emailDetailModal hidden">
      <div class="w-4/5 rounded-md h-4/5 bg-white p-5 overflow-y-auto">
        <header class="flex items-center justify-between">
          <div class="flex gap-2 cursor-pointer closeEmailModal">
            <iconify-icon icon="fluent-mdl2:back" class="text-gray-700" width="24" height="24"></iconify-icon>
            <h2 class="font-bold text-gray-500">Back</h2>
          </div>

          <h2 class="font-bold text-xl text-gray-400">Sent Email</h2>

          <div class="text-sm text-gray-500 italic">
            <span>Email sent: </span>
            <span class="dateData font-bold"></span>
          </div>


        </header>
        <!-- body -->
        <div class="flex flex-col mt-3 text-gray-500 emailModal">
          <h2>Subject: <span class="text-based font-bold subject"></span></h2>
          <h2>To: <span class="text-based font-bold to"></span></h2>
          <div class="h-4/5">
            <h2>Message:</h2>
            <pre class=" text-gray-500 text-justify w-full overflow-y-auto p-5 messageEmail"></pre>
          </div>
        </div>
      </div>
    </div>

    <!-- password edit -->
    <div class="bg-black bg-opacity-50 fixed inset-0 flex flex-col items-center p-3 z-50 passwordModal hidden">
      <div class=" w-2/5 rounded-md h-max bg-white p-5 overflow-y-auto slide-bottom">
        <h2 class="font-bold text-xl">Edit Password</h2>

        <!-- current password -->
        <div class="flex flex-col gap-2 text-greyish_black mt-5">
          <label for="currentPassEdit" class="text-sm font-bold">Current password:</label>
          <input id="currentPassEdit" type="password" placeholder="Enter password" class="rounded-md border border-gray-300 p-2 passwordInputEdit">
          <span class="text-sm italic text-red-400 currentPassErrorMsg hidden">Password is incorrect</span>
        </div>

        <!-- New Password -->
        <div class="flex flex-col gap-2 text-greyish_black mt-2">
          <label for="newPassEdit" class="text-sm font-bold">New password:</label>
          <input id="newPassEdit" type="password" placeholder="Enter password" class="rounded-md border border-gray-300 p-2 passwordInputEdit">
        </div>

        <!-- Confirm Password -->
        <div class="flex flex-col gap-2 text-greyish_black mt-2">
          <label for="confirmPassEdit" class="text-sm font-bold">Confirm password:</label>
          <input id="confirmPassEdit" type="password" placeholder="Enter password" class="rounded-md border border-gray-300 p-2 passwordInputEdit">
          <span class="text-sm italic text-red-400 newPassErrorMsg hidden">New Password doest not match</span>
        </div>

        <div class="flex justify-end gap-2 mt-3">
          <button class="cancelEditBtn text-gray-400 hover:text-gray-500">Cancel</button>
          <button class="confirmEditBtn text-white bg-blue-400 hover:bg-blue-500 px-3 py-2 rounded-md">Save</button>
        </div>
      </div>
    </div>

    <div id="successModalAOY" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
      <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
        <svg class="block mx-auto" width="115px" height="115px" viewBox="0 0 133 133" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="check-group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <circle id="filled-circle" fill="#47CF73" cx="66.5" cy="66.5" r="54.5" />
            <circle id="white-circle" fill="#FFFFFF" cx="66.5" cy="66.5" r="55.5" />
            <circle id="outline" stroke="#47CF73" stroke-width="4" cx="66.5" cy="66.5" r="54.5" />
            <polyline id="check" stroke="#FFFFFF" stroke-width="5.5" points="41 70 56 85 92 49" />
          </g>
        </svg>
        <h1 class=" text-xl font-bold text-green-500 text-center">Successfully Assigned</h1>
        <p class="text-lg text-center text-gray-500">Alumni Of the year successfully assigned!</p>
      </div>
    </div>

  </div>


  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <script src="../js/admin.js"></script>
  <script src="../js/college.js"></script>
  <script src="../js/alumni_of_the_year.js"></script>
  <script src="../js/jobposted.js"></script>
  <script src="../js/tracerchart.js"></script>
  <script src="../js/alumni_of_the_month.js"></script>
  <script src="../js/log.js"></script>
  <script src="../js/tracer.js"></script>
  <script src="../js/alumnirecord.js"></script>
  <script src="../js/announcementscript.js"></script>
  <script src="../js/sendMail.js"></script>
  <script src="../js/previewcontainer.js"></script>
  <script src="../js/postScript.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

</body>

</html>