<!DOCTYPE html>
<html lang="en">

<?php

session_start();
if (
  !isset($_SESSION['username']) ||
  $_SESSION['logged_in'] != true ||
  $_SESSION['accountType'] != 'User'
) {
  header("location: login.php");
  exit();
} else {
  require_once '../PHP_process/connection.php';
  require '../PHP_process/personDB.php';
  require_once '../PHP_process/migration.php';

  $username = $_SESSION['username'];

  //get the person ID of that user
  $query = "SELECT 'student' AS user_type, student.personID, student.currentYear, student.studNo, student.courseID
  FROM student
  WHERE student.username = '$username'
  UNION
  SELECT 'alumni' AS user_type, alumni.personID, NULL, NULL, NULL
  FROM alumni
  WHERE alumni.username = '$username'
  UNION
  SELECT 'not found' AS user_type, NULL, NULL, NULL, NULL
  WHERE NOT EXISTS (
      SELECT 1 FROM student WHERE student.username = '$username'
  ) AND NOT EXISTS (
      SELECT 1 FROM alumni WHERE alumni.username = '$username'
  )";

  $result = mysqli_query($mysql_con, $query);
  if ($result) {
    $data = mysqli_fetch_assoc($result);
    $personID = $data['personID'];
    $user_type = $data['user_type'];
    $studentYr = $data['currentYear'];
    $studentNo = $data['studNo'];
    $courseID = $data['courseID'];

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

    $data = json_decode(getAccDetails($mysql_con, $personID), true); //query to get account type and college code
    $accountType = $data[0];
    $colCode = $data[1];

    $_SESSION['colCode'] = $colCode;
  }
}
function getAccDetails($con, $personID)
{
  $query = "SELECT 'student' AS accountType, colCode FROM student WHERE personID = '$personID' UNION 
    SELECT 'alumni' AS accountType, colCode FROM alumni WHERE personID = '$personID'";
  $result = mysqli_query($con, $query);
  $row = mysqli_num_rows($result);

  $accountType = "";
  $colCode = "";
  if ($result && $row) {
    while ($data = mysqli_fetch_assoc($result)) {
      $accountType = $data['accountType'];
      $colCode = $data['colCode'];
    }
  }

  $data = array($accountType, $colCode);
  return json_encode($data);
}

?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style/student-alumni.css" />
  <link href="../css/main.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


  <link rel="icon" href="../assets/bulsu_connect_img/bulsu_connect_icon.png" type="image/x-icon">
  <title>BulSU Connect</title>
</head>

<body>
  <!--CONTENT PAGE -->
  <div class="fixed top-0 w-full z-50">
    <?php
    echo '<p id="colCode" class="hidden">' . $colCode . '</p>';
    echo '<p id="accUsername" class="hidden">' . $username . '</p>';
    echo '<p id="" class="hidden">' . $user_type . '</p>';
    ?>
    <div id="tabs" class="h-screen overflow-y-scroll hide-scrollbar relative">
      <!-- Navbar -->
      <div class="Navbar fixed top-0 left-0 right-0 z-30">
        <!-- nav class -->
        <nav class="flex flex-row justify-between items-center   py-6 bg-white text-black shadow-lg">

          <!-- Icon Image -->
          <div class=" w-1/4 px-2 sm:px-8 flex items-center">

            <button id="showLeftBtn" class=""><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg></button>

            <a href="homepage.php" class=" min-w-[20px] flex items-center h-auto w-20 lg:h-16 lg:w-32">
              <img src="../assets/bulsu_connect_img/bulsu_connect_logo.png" alt="Logo" class="object-contain" />
            </a>
          </div>
          <!-- end Icon Image -->

          <!-- Search Bar -->
          <div class="flex-1 w-1/2 max-w-[50%] flex items-center justify-center mt-0   relative cursor-text">
            <div class="relative w-full">
              <input type="text" id="searchUser" placeholder="Search" class="pl-10 pr-4 py-3 w-full text-black border-accent center-shadow p-3 rounded-md shadow text-sm border outline-none" />
              <i class="absolute left-3 top-1/2 transform -translate-y-1/2 fas fa-search text-accent text-base"></i>
            </div>
            <div id="searchProfile" class="absolute top-16 bg-white rounded-b-lg p-3 z-50 w-full hidden">
              <p id="retrieveDataMsg" class="text-sm italic text-gray-400">Retrieving data</p>
            </div>
          </div>

          <div class="w-1/4  px-2 sm:px-8 flex items-center justify-end ">


            <!-- End Search Bar -->

            <!-- Dropdown Button -->
            <div class="relative  ">
              <button id="dropdown-btn" class="flex flex-row items-center  gap-4 bg-transparent border-none outline-none">
                <!-- set profile image -->
                <div class="w-10 h-10 flex justify-center items-center">
                  <?php
                  if ($profilepicture == "") {
                    echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="object-contain profile-icon" />';
                  } else {
                    $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                    echo '<img src="' . $srcFormat . '" alt="Profile Icon" class=" object-contain profile-icon" />';
                  }
                  ?>
                </div>
                <p id="accFN" class="hidden lg:block mr-4 text-sm font-medium text-greyish_black p-4">
                  <?php
                  echo $fullname;
                  ?>
                </p>
                <i class="hidden lg:inline fas fa-chevron-down text-lg"></i>
              </button>
            </div>

            <!-- Dropdown Content -->
            <div id="dropdown-content" class="absolute bg-white rounded-md shadow-lg mt-40 justify-evenly right-8 hidden w-72 p-2">
              <a href="profile.php" class="flex items-center py-2 px-4 hover:bg-gray-200 rounded-lg">
                <i class="fas fa-light fa-user text-md pr-2"></i>See Profile
              </a>
              <span id="logout" class="flex items-center py-2 px-4 hover:bg-gray-200 rounded-lg cursor-pointer">
                <i class="fas fa-sign-out-alt text-md pr-2"></i>Logout
              </span>
            </div>
          </div>


        </nav>
        <!-- end nav class -->
      </div>

      <!--SUB NAVBAR-->
      <div class="sub-navbar-wrapper fixed flex justify-center h-24 top-24 mt-1 left-0 right-2 sm:top-24 sm:mt-1 sm:justify-center z-20">

        <ul class="sub-navbar bg-accent text-white flex items-center justify-evenly h-max   p-4 text-sm w-full  lg:w-5/12 ">
          <!--FEED TAB-->
          <li class="w-full sm:w-auto px-5">
            <a href="#tabs-1" class="flex items-center justify-center w-full" id="feedLink" onclick="toggleFeed()">
              <svg class="inline icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M10 20v-6h4v6h5v-8h3L12 3L2 12h3v8h5Z" />
              </svg>
              <span class="hidden sm:inline text-white font-semibold text" id="feedText">Feed</span>
            </a>

          </li>

          <!--LINE SEPARATOR-->
          <div class="hidden xl:block h-10 w-0.5 bg-white md:5"></div>

          <!--EVENTS TAB-->
          <li id="eventLI" class="w-full sm:w-auto px-5 flex justify-center">
            <a href="#tabs-2" id="eventsLink" class="inline-flex items-center">
              <svg class="inline icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19 19H5V8h14m-3-7v2H8V1H6v2H5c-1.11 0-2 .89-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-1V1m-1 11h-5v5h5v-5Z" />
              </svg>
              <span id="eventsText" class="hidden sm:inline text-white font-semibold text" id="eventText">Events</span>
            </a>
          </li>

          <!--LINE SEPARATOR-->
          <div class="hidden xl:block h-10 w-0.5 bg-white md:5"></div>

          <!--JOB HUNT TAB-->
          <li class="w-full sm:w-auto px-5 flex justify-center">
            <a href="#tabs-3" id="jobHuntLink" class="flex items-center">
              <svg class="inline icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentColor" d="M7 5V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h4ZM4 15v4h16v-4H4Zm7-4v2h2v-2h-2ZM9 3v2h6V3H9Z" />
              </svg>
              <span id="JobHuntText" class="hidden sm:inline text-white font-semibold text" id="jobHuntText">Job Hunt</span>
            </a>
          </li>
        </ul>

      </div>

      <!-- TAB 1 -->
      <div id="tabs-1">
        <!-- Container for MAIN FEED -->
        <div id="mainFeedContainer" class="flex pt-48 z-10 w-full h-full scrollable-container">
          <!-- LEFT DIV -->
          <div id="left-sidebar" class="left-div bg-white hidden lg:block w-1/2 fixed top-32 left-0 lg:w-1/4 h-full px-8 z-50">

            <!-- Notifications -->
            <div id="target-div" class="original-color flex items-center hover:bg-gray-100 rounded-md h-10 p-2">
              <button id="notif-btn" class="notif relative" onclick="buttonColor()">
                <svg class="inline fa" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M21 19v1H3v-1l2-2v-6c0-3.1 2.03-5.83 5-6.71V4a2 2 0 0 1 2-2a2 2 0 0 1 2 2v.29c2.97.88 5 3.61 5 6.71v6l2 2m-7 2a2 2 0 0 1-2 2a2 2 0 0 1-2-2" />
                </svg>
                <span class="ps-3 text-sm text-greyish_black font-medium">Notifications</span>
                <span id="notifBadge" class="inline-flex items-center hidden justify-center w-7 h-7 ml-2 text-xs font-semibold text-white
                  bg-red-400 rounded-full">
                </span>
              </button>

            </div>

            <!-- Verification Job Post -->
            <?php
            if ($user_type == "alumni") {
              echo '
              <div id="target-div-job" class="div-btn flex items-center hover:bg-gray-100 rounded-md h-10 p-2 mt-1">
              <button id="verif-btn" onclick="toggleColorJob(), toggleJobPost()">
                <svg class="inline fa" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <path fill="currentColor" d="m10.6 16.6l7.05-7.05l-1.4-1.4l-5.65 5.65l-2.85-2.85l-1.4 1.4l4.25 4.25ZM12 22q-2.075 0-3.9-.788t-3.175-2.137q-1.35-1.35-2.137-3.175T2 12q0-2.075.788-3.9t2.137-3.175q1.35-1.35 3.175-2.137T12 2q2.075 0 3.9.788t3.175 2.137q1.35 1.35 2.138 3.175T22 12q0 2.075-.788 3.9t-2.137 3.175q-1.35 1.35-3.175 2.138T12 22Z" />
                </svg>
                <span class="ps-3 text-sm text-greyish_black font-medium">Job Repository</span>
              </button>
            </div>';
            }
            ?>

            <!-- show only yearbook for alumni -->
            <?php
            if ($user_type == "alumni") {
              require '../PHP_process/deploymentTracer.php';
              $isAvailable = retrievedDeployment($mysql_con);

              // check if there's still available to be answer
              if ($isAvailable != "None") {
                echo '
                <div id="target-div-yearbook" class="div-btn flex items-center hover:bg-gray-100 rounded-md h-10 p-2 mt-1">
                  <button id="yearbook-btn">
                    <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M14.727 6h6l-6-6v6zm0 .727H14V0H4.91c-.905 0-1.637.732-1.637 1.636v20.728c0 .904.732 1.636 1.636 1.636h14.182c.904 0 1.636-.732 1.636-1.636V6.727h-6zM7.91 17.318a.819.819 0 1 1 .001-1.638a.819.819 0 0 1 0 1.638zm0-3.273a.819.819 0 1 1 .001-1.637a.819.819 0 0 1 0 1.637zm0-3.272a.819.819 0 1 1 .001-1.638a.819.819 0 0 1 0 1.638zm9 6.409h-6.818v-1.364h6.818v1.364zm0-3.273h-6.818v-1.364h6.818v1.364zm0-3.273h-6.818V9.273h6.818v1.363z"/>
                    </svg>
                    <span class="ps-3 text-sm text-greyish_black font-medium">Graduate Tracer</span>
                  </button>
                </div>';
              }
            }
            ?>
            <!-- profile -->
            <div class="flex items-center h-10 p-2 mt-1">
              <a href="profile.php">
                <button>
                  <svg class="inline fa" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path fill="black" d="M7.5 6.5C7.5 8.981 9.519 11 12 11s4.5-2.019 4.5-4.5S14.481 2 12 2S7.5 4.019 7.5 6.5zM20 21h1v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h17z" />
                  </svg>
                </button>
                <span class="ps-3 text-sm text-greyish_black font-medium">Profile</span>
              </a>
            </div>

            <!-- Make Post Button -->
            <button id="postButton" class="bg-postButton hover:bg-postHoverButton rounded-md w-full lg:w-3/4 py-2 text-white mt-3">Make a post</button>

            <!-- Upcoming Events -->
            <div class="py-4">
              <h3 class="text-lg font-bold text-grayish_black">Upcoming Events:</h3>
              <div id="upcomingEventroot" class="px-3 flex flex-col gap-1 mt-2"></div>
              <span class="text-gray-400 italic text-sm noavailableEvent hidden">No upcoming events at the moment</span>
            </div>
          </div>

          <!-- CENTER DIV -->
          <div class="flex-1 flex justify-center items-center h-screen relative">
            <div id="centerDiv" class="border-l border-r border-grayish px-4 mt-2 h-full">

              <!-- Content for the center div -->
              <!-- Main Feed -->
              <div id="mainFeed" class="mainFeed h-full">
                <!-- Content for the main feed -->
                <!-- POST -->
                <div id="feedContainer" class="post w-5/6 mx-auto post-width p-3 h-full no-scrollbar">
                  <!-- Make Post && Profile -->
                  <div id="makePostProfile" class="post p-3 input-post-width mx-auto rounded-md center-shadow w-full my-2">
                    <div class="flex items-center">
                      <!-- set profile image -->
                      <?php
                      if ($profilepicture == "") {
                        echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
                      } else {
                        $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                        echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
                      }

                      ?>
                      <div class="write pl-2 w-full">
                        <button id="writeBtn" class="bg-gray-200 hover:bg-gray-100 text-grayish font-extralight py-2 px-4 rounded-full flex-grow w-full hover:shadow-md border-2">
                          <span class="flex items-center">
                            <span>Write something...</span>
                          </span>
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="flex flex-col items-center justify-center">
                    <div class="lds-facebook">
                      <div></div>
                      <div></div>
                      <div></div>
                    </div>
                  </div>

                  <p id="loadingDataFeed" class="text-gray-400 text-center">Loading data...</p>
                  <p id="noPostMsgFeed" class="text-blue-400 text-center hidden">No available post <button class="refresher underline hover:text-blue-500">retrieve seen post</button></p>
                </div>
              </div>

              <!-- Job Post Feed -->
              <div id="jobRepo" class="hidden h-max grid grid-cols-3 gap-2 overflow-y-auto no-scrollbar py-3">
                <p id="loadingDataJobRepo" class="text-gray-400 text-center hidden">Loading repository</p>
              </div>

            </div>

            <span id="promptMsg" class=" slide-bottom fixed bottom-28 px-4 py-2 z-50 bg-accent text-white rounded-sm hidden font-bold">Comment successfully added</span>
          </div>

          <!-- RIGHT DIV -->
          <div class="right-div hidden lg:block fixed top-32 right-2 w-1/4 h-full px-8">
            <!-- Content for the right div -->
            <p class="font-medium border-b-2 border-grayish ml-auto block text-sm pb-2 mb-4 text-greyish_black">University News</p>
            <p id="loadingDataAnnouncement" class="text-gray-400 text-center">Loading data</p>
            <div class="h-1/3">
              <div class="swiper announcementSwiper">
                <div id="announcementWrapper" class="swiper-wrapper"></div>
                <div class="swiper-pagination"></div>
              </div>
            </div>

          </div>

          <!-- MODALS && OTHER OBJECTS THAT HAVE Z-50 -->
          <!-- Notifications Tab -->
          <div id="notification-tab" class=" notification-tab hidden fixed top-40 lg:top-24 mt-1 right-1 h-full bg-black bg-opacity-50 w-3/4 z-50">
            <div class="notification-content bg-white border-2 px-4 pt-4 pb-20 h-full md:w-2/6 lg:w-3/6 xl:w-2/5 2xl:w-2/5 overflow-y-auto hide-scrollbar">
              <h1 class="text-greyish_black text-lg font-bold mb-4">Notifications</h1>

              <div class="flex space-x-4 mb-4">
                <button id="btnNotifAll" class="hover:bg-gray-500 rounded-full  px-4 py-2 text-sm font-semibold bg-accent text-white">All</button>
                <button id="btnNotifUnread" class="hover:bg-gray-500 rounded-full text-greyish px-4 py-2 text-sm font-semibold">Unread</button>
              </div>

              <p id="loadingDataNotif" class="text-gray-400 text-center">Loading data...</p>
              <p id="noNotifMsg" class="text-center my-4 text-blue-400 hidden">No available notification</p>
            </div>
          </div>


          <div id="modal" class="modal hidden fixed inset-0 h-full w-full flex items-center justify-center
            text-grayish  top-0 left-0">
            <div class="modal-container w-10/12 mx-auto lg:mx-0 lg:w-1/3  h-max bg-white rounded-lg p-3">
              <div class="modal-header py-5 border-b border-gray-400">
                <h1 class=" text-greyish_black text-2xl text-center font-bold">Create New Post</h1>
              </div>
              <div class="flex items-center mb-2 my-2">
                <!-- set profile image -->
                <?php
                if ($profilepicture == "") {
                  echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
                } else {
                  $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                  echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
                }

                ?>
                <p class="text-black font-semibold text-sm pl-2">
                  <?php
                  echo $fullname;
                  ?>
                </p>
              </div>

              <div class="modal-body px-3 h-40">

                <!-- body part -->
                <div class="modal-descript relative w-full h-full rounded p-3">
                  <div class="flex flex-col h-full border-gray-300">
                    <textarea id="TxtAreaAnnouncement" class="rar outline-none w-full h-4/5" type="text" placeholder="Say something here..."></textarea>
                  </div>
                  <label for="fileGallery" class="cursor-pointer">
                    <span id="galleryLogo" class="absolute bottom-1 left-1">
                      <svg class="inline" width="22" height="22" viewBox="0 0 22 22" fill="green" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 7C17 7.53043 16.7893 8.03914 16.4142 8.41421C16.0391 8.78929 15.5304 9 15 9C14.4696 9 13.9609 8.78929 13.5858 8.41421C13.2107 8.03914 13 7.53043 13 7C13 6.46957 13.2107 5.96086 13.5858 5.58579C13.9609 5.21071 14.4696 5 15 5C15.5304 5 16.0391 5.21071 16.4142 5.58579C16.7893 5.96086 17 6.46957 17 7Z" fill="#BCBCBC" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.943 0.25H11.057C13.366 0.25 15.175 0.25 
                        16.587 0.44C18.031 0.634 19.171 1.04 20.066 1.934C20.961 2.829 21.366 3.969 21.56 5.414C21.75 6.825 21.75 8.634 21.75 10.943V11.031C21.75 12.94 21.75 14.502 21.646 15.774C21.542 17.054 21.329 18.121 20.851 19.009C20.641 19.4 20.381 19.751 20.066 20.066C19.171 20.961 18.031 21.366 16.586 21.56C15.175 21.75 13.366 21.75 11.057 
                        21.75H10.943C8.634 21.75 6.825 21.75 5.413 21.56C3.969 21.366 2.829 20.96 1.934 20.066C1.141 19.273 0.731 18.286 0.514 17.06C0.299 15.857 0.26 14.36 0.252 12.502C0.25 12.029 0.25 11.529 0.25 11.001V10.943C0.25 8.634 0.25 6.825 0.44 5.413C0.634 3.969 1.04 2.829 1.934 1.934C2.829 1.039 3.969 0.634 5.414 0.44C6.825 0.25 8.634 0.25 10.943 0.25ZM5.613 1.926C4.335 2.098 3.564 2.426 2.995 2.995C2.425 3.565 2.098 4.335 1.926 5.614C1.752 6.914 1.75 8.622 1.75 11V11.844L2.751 10.967C3.1902 10.5828 3.75902 10.3799 4.34223 10.3994C4.92544 10.4189 5.47944 10.6593 5.892 11.072L10.182 15.362C10.5149 15.6948 10.9546 15.8996 11.4235 15.9402C11.8925 15.9808 12.3608 15.8547 12.746 15.584L13.044 15.374C13.5997 14.9835 14.2714 14.7932 14.9493 14.834C15.6273 14.8749 16.2713 15.1446 16.776 15.599L19.606 18.146C19.892 17.548 20.061 16.762 20.151 15.653C20.249 14.448 20.25 12.946 20.25 11C20.25 8.622 20.248 6.914 20.074 5.614C19.902 4.335 19.574 3.564 19.005 2.994C18.435 2.425 17.665 2.098 16.386 1.926C15.086 1.752 13.378 1.75 11 1.75C8.622 1.75 6.913 1.752 5.613 1.926Z" fill="#BCBCBC" />
                      </svg>
                      Add Image
                    </span>
                  </label>
                  <input id="fileGallery" type="file" class="hidden" />
                </div>

              </div>
              <div id="imgContPost" class="hidden flex overflow-x-scroll w-full border-t border-gray-300"></div>
              <p class="text-sm text-red-400 hidden" id="errorMsg">Sorry we only allow images that has file extension of
                jpg,jpeg,png</p>
              <!-- Footer -->
              <div class="modal-footer flex items-end flex-row-reverse px-3">
                <button id="postBtn" class="bg-accent py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
                <button class="cancel py-2 rounded px-5 text-grayish border border-slate-400 hover:bg-slate-400 hover:text-white">Cancel</button>
              </div>
            </div>
          </div>

        </div>

        <!-- View job post modal -->
        <div id="viewJob" class="modal fixed inset-0 h-full w-full flex items-start justify-center 
          top-0 left-0 p-10 hidden overflow-y-auto">
          <!-- modal body -->
          <div id="viewingJobModal" class="w-2/5 bg-white rounded-lg h-4/5 slide-bottom relative">

            <div class="flex flex-col justify-between h-full">
              <div class=" overflow-y-auto no-scrollbar">
                <!-- content -->
                <div class="headerJob flex rounded-t-lg p-5">
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

            <button class="absolute -top-12 p-2 border border-white hover:border-accent hover:bg-accent rounded-md right-0 text-white stop-job-btn">
              Cease accepting applicants.
            </button>
          </div>

        </div>

        <!-- stop job post -->
        <div class="modal fixed inset-0 h-full w-full flex items-start hidden justify-center 
          top-0 left-0 p-5 overflow-y-auto stop-job-modal">
          <div class="w-2/5 bg-white rounded-xl h-max p-5 relative text-gray-500">
            <h2 class="text-xl pb-3 border-b border-gray-400 mb-2">Warning for this action</h2>
            <p>This action will hide this job posting from the user. It will no longer accept applications.
              Make sure that you have finished looking for candidates for this position.</p>

            <p class="text-sm italic mt-5">Note: This post will not be deleted and its applicant and it cannot be undone once it set as ceased</p>
            <div class="flex justify-end items-center gap-2">
              <button class="text-gray-400 hover:text-gray-500 stop-job-cancel">Cancel</button>
              <button class="bg-green-400 hover:bg-green-500 text-white px-3 py-2 rounded-md stop-job-confirm">Confirm</button>
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

        <!-- viewing of post -->
        <div id="viewingPost" class="post modal fixed hidden inset-0 flex items-center justify-center p-3">
          <div class="modal-container w-full h-full bg-white rounded-lg flex relative p-6 lg:p-0">
            <button id="closePostModal" class="daisy-btn daisy-btn-sm daisy-btn-circle daisy-btn-ghost absolute right-2 top-2">✕</button>

            <!-- <span  class="absolute top-0 right-0 text-center text-2xl cursor-pointer p-3 hover:scale-50 hover:font-bold">x</span> -->
            <!-- change the orientation dpending on the screen size -->
            <div class="flex flex-col lg:flex-row w-full mt-4 lg:mt-0">
              <div id="containerSection" class="w-full lg:w-8/12 h-full ">
                <div id="default-carousel" class="relative w-full h-full bg-black" data-carousel="slide">
                  <!-- Carousel wrapper -->
                  <div class="overflow-hidden rounded-lg h-full" id="carousel-wrapper"></div>
                  <!-- Slider indicators -->
                  <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2" id="carousel-indicators">
                  </div>
                  <!-- Slider controls -->
                  <button id="btnPrev" type="button" class="navigatorBtn absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none hover:bg-gray-500 hover:bg-opacity-20" data-carousel-prev>
                    <span class="inline-flex items-center justify-center w-10 h-10 ">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path fill="white" d="m4 10l9 9l1.4-1.5L7 10l7.4-7.5L13 1z" />
                      </svg>
                      <span class="sr-only">Previous</span>
                    </span>
                  </button>
                  <button id="btnNext" type="button" class="navigatorBtn text-white absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none hover:bg-gray-500 hover:bg-opacity-20">
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
              <div id="descriptionInfo" class="w-full lg:w-4/12 h-full  lg:border-l p-3 border-gray-400">
                <div class="flex justify-start gap-2">
                  <img id="profilePic" class="rounded-full border-2 border-accent h-10 w-10" src="" alt="">
                  <div class="flex flex-col">
                    <span id="postFullName" class=" text-greyish_black font-bold"></span>
                    <span id="postUN" class=" text-gray-400 text-xs">username</span>
                  </div>
                </div>
                <p id="postDescript" class=" text-greyish_black font-light text-sm h-1/2 overflow-y-auto p-2">Description</p>
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
        </div>

        <!-- comment -->
        <div id="commentPost" class="post modal fixed inset-0 flex justify-center p-3 hidden">
          <div class="modal-container w-full lg:w-1/3 h-max bg-white rounded-lg p-3 mt-14 flex flex-col gap-1">
            <!-- close button -->
            <span class="flex justify-end">
              <iconify-icon id="closeComment" class="rounded-full cursor-pointer p-2 hover:bg-gray-300" icon="ep:close" style="color: #686b6f;" width="20" height="20"></iconify-icon>
            </span>

            <div class="flex gap-2 items-center">
              <img id="postProfile" class="h-10 w-10 rounded-full" src="../" alt="">
              <div>
                <p id="postFullname" class="text-black">Fullname ko 'to</p>
                <p id="postUsername" class="text-xs text-gray-400 font-thin">username ko 'to</p>
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
                echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
              } else {
                $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
              }
              ?>
              <textarea id="commentArea" class="w-full h-28 outline-none text-gray-400" placeholder="Comment your thoughts!"></textarea>
            </div>

            <button id="commentBtn" class="px-3 py-2 rounded-lg bg-red-950 text-white font-semibold block ml-auto text-sm" disabled>Comment</button>
          </div>
        </div>
        <!-- Container for Yearbook -->
        <div id="yearbookContainer" class="hidden flex pt-48 z-10 w-full">

          <!-- finished answering -->
          <div id="finishedContainer" class="hidden h-full w-full overflow-y-auto flex flex-col gap-3 items-start p-5 text-accent relative">
            <h2 class="text-lg md:text-5xl font-bold italic">Welcome to</h2>
            <h2 class="text-lg md:text-5xl font-bold">BulSU Connect Community!</h2>
            <p class="w-1/2 text-sm md:text-lg">Thank you for answering the Graduate Tracer form,
              you are now connected to the community. Stay tuned for the more updates.</p>

            <a href="javascript:location.reload(true);" class="text-center text-sm md:font-xl md:w-1/3 py-4 rounded-md text-white font-bold bg-accent hover:bg-darkAccent my-3">Back to Homepage</a>

            <div class="flex justify-end absolute bottom-0">
              <img src="../assets/alumni_tracer_bg.png" class="w-2/3" alt="">
            </div>
          </div>
          <!-- front page -->
          <div id="frontpageTracer" class=" h-full w-full overflow-y-auto flex flex-col gap-3 items-center p-3">
            <img src="../assets/tracer_header_img.png" class="w-1/3 h-56" alt="">
            <div class="rounded-lg center-shadow p-3 border-t-4 border-accent w-1/2">
              <h3 class="text-2xl text-greyish_black font-bold">Alumni Graduate Tracer 2023-2024</h3>
              <span>Dear Graduates of Batch 2010-2023,</span>

              <p class="text-sm text-justify mt-3">Please complete this questionnaire as accurately and completely as possible. Kindly
                provide the needed information or check (/) the space corresponding to your response.
                Your responses will be used to assess graduate employability and eventually improve
                the current curriculum of the programs offered in the Bulacan State University (BulSU).
                We ensure that every information that you will provide in this form will be treated in
                strict confidentiality . Thank you for your participation and honesty!</p>
            </div>

            <div class="flex justify-end w-1/2">
              <button id="proceedTracer" class="text-white px-3 py-2 rounded-md bg-accent hover:bg-darkAccent">Proceed</button>
            </div>

          </div>

          <!-- container questions -->
          <div id="questionsContainer" class="h-full w-4/5 overflow-y-auto flex flex-col gap-3 rounded-md border border-gray-400 mx-auto items-center p-5 hidden">
            <div class="w-full">
              <h3 id="categoryNameQuestion" class="text-lg font-extrabold text-accent">Category Name</h3>
            </div>
            <div class="questions h-full w-full p-2 overflow-y-auto "></div>
            <div id="navigationWrapper" class="w-full">
              <div class="border border-gray-300 rounded-lg w-full mb-2">
                <div class="progressBar bg-green-600 h-full rounded-lg p-1"></div>
              </div>
            </div>
          </div>

        </div>

        <!-- report modal -->
        <div id="reportModal" class="post modal hidden fixed inset-0 z-50 flex items-center justify-center p-3">
          <div class="modal-container w-full lg:w-2/5 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
            <h1 class="text-xl  text-center font-bold py-3 border-b border-gray-400">Report</h1>

            <!-- description -->
            <div>
              <p class=" text-lg font-bold">Please select a problem</p>
              <p class="">Help BulSU Connect to remove inappropriate action here! Report something if it is in the following. Let’s build a wonderful community</p>
            </div>

            <!-- report types -->
            <div class="flex flex-wrap gap-3 mt-5 mb-10">
              <div class="flex items-center gap-3">
                <input class="reportCateg" type="checkbox" id="nudity" value="Nudity">
                <label class="font-semibold text-sm" for="nudity">Nudity</label>
              </div>

              <div class="flex items-center gap-3">
                <input class="reportCateg" type="checkbox" id="Violence" value="Violence">
                <label class="font-semibold text-sm" for="Violence">Violence</label>
              </div>

              <div class="flex items-center gap-3">
                <input class="reportCateg" type="checkbox" id="Terrorism" value="Terrorism">
                <label class="font-semibold text-sm" for="Terrorism">Terrorism</label>
              </div>

              <div class="flex items-center gap-3">
                <input class="reportCateg" type="checkbox" id="HateSpeech" value="Hate Speech">
                <label class="font-semibold text-sm" for="HateSpeech">Hate Speech</label>
              </div>

              <div class="flex items-center gap-3">
                <input class="reportCateg" type="checkbox" id="falseInfo" value="False Information">
                <label class="font-semibold text-sm" for="falseInfo">False Information</label>
              </div>

              <div class="flex items-center gap-3">
                <input class="reportCateg" type="checkbox" id="SOS" value="Suicide or self-injury">
                <label class="font-semibold text-sm" for="SOS">Suicide or self-injury</label>
              </div>

              <div class="flex items-center gap-3">
                <input class="reportCateg" type="checkbox" id="Harassment" value="Harassment">
                <label class="font-semibold text-sm" for="Harassment">Harassment</label>
              </div>

            </div>

            <!-- definitions -->
            <div class="flex flex-col gap-2 border-t border-gray-400 py-3 mb-10 px-2">
              <span class="text-sm">
                <iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #474645;"></iconify-icon>
                <span class="font-bold">Nudity</span> - the state or fact of being naked.
              </span>

              <span class="text-sm">
                <iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #474645;"></iconify-icon>
                <span class="font-bold">Violence</span> - behavior involving physical force intended to hurt,
                damage, or kill someone or something.
              </span>

              <span class="text-sm">
                <iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #474645;"></iconify-icon>
                Terrorism - the unlawful use of violence and intimidation, especially against civilians, in the pursuit
                of political aims.
              </span>

              <span class="text-sm">
                <iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #474645;"></iconify-icon>
                <span class="font-bold">Hate Speech</span> - abusive or threatening speech or writing that expresses prejudice on the basis of
                ethnicity, religion, sexual orientation, or similar grounds.
              </span>

              <span class="text-sm">
                <iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #474645;"></iconify-icon>
                <span class="font-bold">False Information</span> - wrong information which is given to someone, often in a deliberate attempt to
                make them believe something which is not true.
              </span>

              <span class="text-sm">
                <iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #474645;"></iconify-icon>
                <span class="font-bold">Suicide or self-injury</span> - the act of harming your own body on purpose
              </span>

              <span class="text-sm">
                <iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #474645;"></iconify-icon>
                <span class="font-bold">Harassment</span> - If someone is abusing, insulting, or otherwise harming you on a regular basis.
              </span>

            </div>

            <!-- interaction container -->
            <div class="flex gap-2 text-sm justify-end">
              <button id="closeReportModal" class="text-gray-400 hover:text-gray-500">Cancel</button>
              <button id="reportBtn" class="bg-red-300 text-gray-300 py-2 px-5 rounded-md " disabled>Report</button>
            </div>
          </div>
        </div>

        <!-- success prompt Modal -->
        <div id="successModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
          <div class="modal-container w-full md:w-1/2 lg:w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
            <svg class="block mx-auto" width="115px" height="115px" viewBox="0 0 133 133" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <g id="check-group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <circle id="filled-circle" fill="#47CF73" cx="66.5" cy="66.5" r="54.5" />
                <circle id="white-circle" fill="#FFFFFF" cx="66.5" cy="66.5" r="55.5" />
                <circle id="outline" stroke="#47CF73" stroke-width="4" cx="66.5" cy="66.5" r="54.5" />
                <polyline id="check" stroke="#FFFFFF" stroke-width="5.5" points="41 70 56 85 92 49" />
              </g>
            </svg>
            <h1 class=" text-3xl font-bold text-green-500 text-center">Thank you</h1>
            <p id="msgSuccess" class=" text-center text-gray-500">Your feedback is important to us, and we take all reports seriously</p>
          </div>
        </div>

        <!-- announcement modal -->
        <div id="announcementModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
          <div id="announcementContainer" class="modal-container w-10/12 mx-auto lg:mx-0 lg:w-2/5 h-5/6 overflow-y-auto bg-white rounded-md py-3 px-12 text-greyish_black flex flex-col gap-2">
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

        <!-- Restore Modal -->
        <div id="restoreModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
          <div class="relative w-10/12 mx-auto lg:mx-0 lg:w-1/3 h-max p-3 bg-white rounded-md">
            <h1 class="text-xl text-greyish_black font-bold text-center py-2 border-b border-gray-400">Restore to Profile</h1>
            <p class="text-gray-500 text-sm">Items you restore to your profile can be seen by the audience that was selected
              before they were moved to archive.</p>
            <div class="flex justify-end gap-2 my-2">
              <button id="closeRestoreModal" class="px-3 py-2 rounded-md text-blue-400 hover:bg-gray-300 text-sm">Cancel</button>
              <button class="px-4 py-2 text-white bg-postButton hover:bg-postHoverButton rounded-md font-semibold text-sm">Restore</button>
            </div>

            <svg id="closeRestore" class="cursor-pointer absolute top-2 right-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20">
              <path fill="#6b7280" d="M2.93 17.07A10 10 0 1 1 17.07 2.93A10 10 0 0 1 2.93 17.07zM11.4 10l2.83-2.83l-1.41-1.41L10 8.59L7.17 5.76L5.76 7.17L8.59 10l-2.83 2.83l1.41 1.41L10 11.41l2.83 2.83l1.41-1.41L11.41 10z" />
            </svg>
          </div>
        </div>

        <!-- Profile Modal -->
        <div id="profileModal" class="fixed inset-0 flex justify-center z-50 bg-black bg-opacity-50 hidden">
          <div id="profileModalUser" class="bg-white rounded shadow-lg w-10/12 mx-auto md:w-8/12 lg:w-2/5 lg:mx-0 max-h-screen h-max overflow-y-auto slide-bottom">
            <!-- Cover Photo -->
            <div class="coverPhotoContainer">
              <img id="profileModalCover" alt="Cover Photo" class=" w-full h-full md:h-56 mb-4 object-cover block object-center">
            </div>
            <div class="px-4 md:px-6">

              <!-- Profile Picture and Info -->
              <div class="flex items-start mb-4">
                <img id="profileModalProfile" alt="Profile Picture" class=" w-16 h-16 md:w-28 md:h-28 rounded-full md:-mt-20 mr-4 ml-2 bg-white border-2">
                <div class="flex-grow">
                  <h2 id="profileModalFN" class=" md:text-lg font-bold text-gray-700"></h2>
                  <p id="profileModalUN" class="text-gray-500 text-sm"></p>
                </div>

              </div>
              <p class="italic text-gray-500 text-sm userCourse mb-2"></p>
              <h2 class="text-md md:text-lg font-bold mb-2 text-greyish_black">Social Media</h2>

              <!-- social media links -->
              <div class="flex gap-2 border-b border-gray-300 text-sm text-gray-500 py-2 mb-2">

                <div class="flex-1 flex-col gap-4 justify-center items-center">
                  <!-- facebook -->
                  <div class="flex items-center gap-3 mb-2">
                    <iconify-icon icon="logos:facebook" width="24" height="24"></iconify-icon>
                    <span id="facebookUN" class="text-center"></span>
                  </div>

                  <!-- instagram -->
                  <div class="flex items-center gap-3">
                    <iconify-icon icon="skill-icons:instagram" width="20" height="20"></iconify-icon>
                    <span id="instagramUN" class="text-center"></span>
                  </div>
                </div>

                <div class="flex-1 flex-col gap-3 justify-center items-center">
                  <!-- twitter -->
                  <div class="flex items-center gap-3 mb-2">
                    <iconify-icon icon="devicon:twitter" width="20" height="20"></iconify-icon>
                    <span id="twitterUN" class="text-center"></span>
                  </div>

                  <!-- linkedIN -->
                  <div class="flex items-center gap-3">
                    <iconify-icon icon="devicon:linkedin" width="20" height="20"></iconify-icon>
                    <span id="linkedInUN" class="text-center"></span>
                  </div>
                </div>


              </div>

              <!-- user post -->
              <div id="userPostContainer" class="max-h-48 md:max-h-64 overflow-y-auto no-scrollbar">
                <div id="userPostModal" class="grid grid-cols-3 gap-4 p-2"></div>
                <p id="noProfileMsgSearch" class="text-center text-blue-400 my-2 hidden">No available Post</p>
              </div>

            </div>
          </div>
        </div>

        <!-- Additional Question Modal -->
        <div id="sectionModal" class="fixed inset-0 flex pt-10 justify-center z-50 bg-black bg-opacity-50 hidden">
          <div class="sectionModalTracer bg-white rounded shadow-lg w-2/5 h-max overflow-y-auto slide-bottom p-5 relative">
            <h3 class="font-bold text-xl text-accent py-2 border-b border-gray-300 text-center">Additional Question</h3>

            <!-- close button -->
            <button class="closeSectionModal absolute top-1 right-1">
              <iconify-icon class="text-gray-400 hover:text-accent" icon="carbon:close-outline" width="24" height="24"></iconify-icon>
            </button>

            <!-- section container -->
            <div id="sectionQuestionContainer" class="h-4/5 overflow-y-auto p-3"></div>
            <div class="flex w-full justify-end gap-2 py-2 border-t border-gray-300 my-2">
              <button class="closeSectionModal text-gray-400 hover:text-gray-500">Cancel</button>
              <button id="proceedBtnSection" class="px-4 py-2 rounded-md bg-blue-400 hover:bg-blue-500 text-white">Proceed</button>
            </div>
          </div>
        </div>

        <!-- Reported Post MOdal -->
        <div id="reportedPostModal" class="fixed inset-0 flex pt-10 justify-center z-50 bg-black bg-opacity-50 hidden">
          <div class="bg-white rounded shadow-lg w-full lg:w-2/5 h-max overflow-y-auto slide-bottom p-5 relative">

            <!-- head -->
            <div class="flex gap-2 items-center">
              <iconify-icon class="text-accent" icon="fe:warning" width="32" height="32"></iconify-icon>
              <div class="text-gray-500">
                <p class="text-lg font-medium">Your post was removed by admin.</p>
                <p id="reportedTime" class="text-sm">Date</p>
              </div>
            </div>

            <!-- post details -->
            <div class="mt-5">
              <div class="flex justify-between items-center">
                <div class="flex gap-2 items-center">
                  <?php
                  if ($profilepicture == "") {
                    echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
                  } else {
                    $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                    echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
                  }
                  echo '<span class="text-gray-600 ">' . $username . '</span>';
                  ?>
                </div>

                <span id="postDate" class="text-xs text-gray-500"></span>
              </div>

              <p id="reportedPostCap" class="text-center p-3 mt-3 text-sm"></p>
              <p id="reasonForDel" class="text-accent text-center font-semibold text-sm p-3"></p>
            </div>

            <div class="flex justify-end gap-3 p-3">
              <button id="closeReportedPost" class="text-accent px-2 hover:text-darkAccent hover:font-semibold">Ok</button>
              <button id="learnMoreBtn" class="text-white py-2 px-4 bg-accent rounded-md hover:bg-darkAccent">Learn more</button>
            </div>
          </div>
        </div>


        <!-- View Resume Modal  -->
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
                  <section class="my-2">
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

        <!-- community guidelines -->
        <div class="communityGuideline fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 h-full hidden">
          <div id="modalGuidelines" class="bg-white rounded-lg shadow-lg w-2/5">

            <div class="w-full h-full">
              <!-- head -->
              <div class="bg-accent p-5 gap-2 flex items-center text-white rounded-t-lg">
                <iconify-icon icon="gridicons:notice-outline" width="36" height="36"></iconify-icon>
                <h3 class="text-xl font-bold">BulSU Connect Community Guidelines</h3>
              </div>
              <!-- content -->
              <div class="p-4 flex flex-col gap-2 text-sm text-greyish_black overflow-y-auto no-scrollbar h-4/5">
                <p class="text-justify">
                  <span class="font-bold">1.) Reporting Threshold:</span>
                  Any post that receives 10 or more reports from community members will be subject to review and
                  may be removed if found to violate these guidelines. Reporting plays a crucial role in maintaining
                  the quality and safety of our community, so please use this feature responsibly.
                </p>

                <p class="text-justify">
                  <span class="font-bold">2.) Be Respectful and Inclusive:</span>
                  Treat every user with respect, kindness, and inclusivity. We encourage diverse opinions and
                  discussions but do not engage in hate speech, harassment, or bullying of any kind.
                </p>

                <p class="text-justify">
                  <span class="font-bold">3.) No Offensive or Harmful Content:</span>
                  Do not post or share content that is offensive, discriminatory, or explicit.
                  This includes hate speech, nudity, violence, or any content that may be considered harmful or offensive to others.
                </p>

                <p class="text-justify">
                  <span class="font-bold">4.) Respect Privacy:</span>
                  Always respect the privacy of others. Do not share personal information, such as phone numbers, addresses,
                  or financial details, without the explicit consent of the individuals involved.
                </p>

                <p class="text-justify">
                  <span class="font-bold">5.) Safety First:</span>
                  Ensure a safe environment for all. Do not post content that promotes violence, threats, or criminal behavior.
                  Bullying, harassment, and self-harm content are strictly prohibited.
                </p>

                <p class="text-justify">
                  <span class="font-bold">6.) Thoughtful and Constructive Engagement:</span>
                  When sharing your thoughts, opinions, or ideas, be constructive and considerate of others' perspectives.
                  Engage in healthy discussions and avoid trolling or flame wars.
                </p>

                <p class="text-justify">
                  <span class="font-bold">7.) Respect Intellectual Property:</span>
                  Only share content that you have the right to distribute. Do not infringe upon
                  copyrights, trademarks, or intellectual property rights.
                </p>

                <p class="text-justify">
                  <span class="font-bold">8.) No Spam or Self-Promotion:</span>
                  Do not post spammy content, advertisements, or self-promotional material. If you have a business or product to promote,
                  please use designated channels or contact the administrators for permission.

                </p>

                <p class="text-justify">
                  <span class="font-bold">9.) Reporting Content:</span>
                  If you come across content that violates these guidelines, use the report feature to notify the administrators.
                  Do not engage in public arguments or retaliation.
                </p>

                <p class="text-justify">
                  <span class="font-bold">10.) Photo Sharing:</span>
                  You can share photos that are appropriate and relevant to the community's interests. Ensure you have the necessary
                  rights or permissions to share any images.
                </p>

                <p class="text-justify">
                  <span class="font-bold">11.) User Safety and Authenticity: </span>
                  Help maintain the integrity of our community by reporting fake accounts, spam, or misleading information.
                  We do not tolerate fake news or misinformation.
                </p>

                <p class="text-justify">
                  <span class="font-bold">12.) Respect for Diverse Opinions:</span>
                  While we encourage healthy debates, do not engage in credible threats or direct harassment,
                  even when discussing public figures or organizations.
                </p>

                <p class="text-justify">
                  <span class="font-bold">13.) Child Safety:</span>
                  We are committed to child safety. Do not share or promote content that involves minors in harmful or abusive situations.
                </p>

                <p class="text-justify">
                  <span class="font-bold">14.) Legal Compliance: </span>
                  Adhere to all applicable laws and regulations in your area when using our platform,
                  including but not limited to copyright, defamation, and privacy laws.
                </p>

                <p class="text-justify">
                  <span class="font-bold">15.) Account Responsibility: </span>
                  You are responsible for all activity conducted under your account.
                  Keep your login credentials secure and do not share your account with others.
                </p>

              </div>

              <button class="closeGuidelines px-4 py-2 rounded-md block ml-auto m-3 text-gray-400 hover:text-gray-500">Close</button>
            </div>

          </div>
        </div>

      </div>

      <!-- TAB 2 -->
      <div id="tabs-2" class="h-full">
        <div id="eventView" class="h-full ">
          <div class=" h-1/2 lg:h-2/3  md:top-72">
            <div class="relative w-full h-full flex items-center">
              <img class=" object-cover object-top darkened-image" src="../assets/bg_event.jpg" alt="">
              <h1 class="text-2xl md:text-4xl lg:text-5xl xl:text-8xl  text-white font-black w-full text-center absolute typewriter">Let's Connect <span class="text-accent"> BulSUAN!</span></h1>
            </div>
          </div>

          <!-- body -->
          <div class="p-10">
            <span class="text-gray-500 text-lg">Get excited for the upcoming</span>
            <h3 id="eventName" class="font-bold text-gray-700 text-4xl"></h3>

            <div class="date flex flex-col items-end text-gray-700">
              <span class="text-lg">Starts on</span>
              <span id="eventStartDate" class="font-bold"></span>
            </div>

            <!-- description -->
            <p id="eventDescriptData" class="max-w-prose text-justify lg:text-left leading-relaxed mx-auto xl:mx-0 text-gray-700 my-4 text-lg w-4/5"></p>

            <!-- view more details -->
            <div class="viewDetailCont flex justify-end my-4">
              <button id="viewInDetailsEvent" class="bg-accent px-4 py-2 rounded-md text-white font-semibold">See Event Details</button>
            </div>


            <!-- college event -->
            <div class="m-4 rounded-md border border-gray-300 p-5 text-gray-600">
              <h3 class="font-bold text-2xl mb-5">College Future Events</h3>
              <!-- top 3 incoming college event -->
              <div id="upcomingColEvent" class="flex justify-evenly flex-wrap gap-4"></div>
            </div>

            <!-- alumni event -->
            <div class="m-4 rounded-md border border-gray-300 p-5 text-gray-600">
              <h3 class="font-bold text-2xl mb-5">Alumni Future Events</h3>
              <!-- top 3 upcoming event for alumni -->
              <div id="upcomingAlumniEvent" class="flex justify-evenly flex-wrap gap-4"></div>
            </div>
          </div>

        </div>

        <!-- default view -->
        <div id="defaultEvent" class="h-full flex flex-col justify-center p-5 relative hidden">
          <h3 class="w-1/2 font-bold text-lg md:text-6xl mb-2 text-gray-800">No Upcoming Event On Our Calendar</h3>
          <p class="w-1/2 text-lg text-gray-800">Thank you for your interest! While there are no upcoming events right now,
            we're constantly working to bring you exciting experiences. Please stay tuned</p>
          <div class="w-56 h-56 relative"></div>


          <img class="absolute left-2/3 z-50" src="../assets/3d_images/Chat Emoji 1.png" alt="">
          <img class="absolute top-1/3 right-16 z-50" src="../assets/3d_images/Music Player 1.png" alt="">
          <img class="absolute translate-y-1/2 calendar-logo w-56 h-56 block z-40" src="../assets/3d_images/Calendar 1.png" alt="calendar image">
          <img class="absolute translate-y-2/3 left-2/3 z-50" src="../assets/3d_images/Microphone 1.png" alt="">
          <img class="absolute top-3/4 right-10 z-50" src="../assets/3d_images/look_left chat.png" alt="">
          <div class="absolute diagonalBg bottom-0 right-0 h-1/2 w-1/2"></div>
        </div>
      </div>

      <!-- TAB 3 -->
      <div id="tabs-3" class="h-full">
        <!-- Job Offer Tabs -->
        <div id="job-offer-tabs" class="flex md:flex-row pt-44 z-10 h-full">

          <!-- LEFT DIV -->
          <div class="left-div w-1/3 md:w-5/12 relative p-3">

            <!-- Upper Part -->
            <div class="flex flex-col md:flex-row items-center w-10/12">
              <!-- Dropdown List -->
              <select id="jobSelection" class="py-2 p-3 outline-none border-black center-shadow rounded-md shadow text-sm appearance-none cursor-pointer">
                <option value="all">All</option>
                <option value="Saved">Bookmark</option>
                <option value="Applied">Applied</option>
                <option value="Admin">Admin post</option>
              </select>

              <!-- Searchbar -->
              <div class="relative w-full pb-2 md:pb-0">
                <input id="searchJob" type="text" placeholder="Search" class="pl-10 pr-4 py-2 w-full text-black text-sm border outline-none border-grayish center-shadow p-3 rounded-md shadow text-sm border outline-none" />
                <i class="absolute left-3 top-5 transform -translate-y-1/2 fas fa-search text-grayish"></i>
              </div>
            </div>

            <div class="scrollable-container mt-8 rounded-md  w-10/12">
              <ul id="listOfJob" class="tab-links overflow-y-auto px-2 pb-4 flex flex-col gap-2 w-full" style="max-height: 440px;">
                <p id="noJobMsg" class="hidden text-sm text-blue-400 text-center">No available job right now</p>
              </ul>
            </div>

            <?php

            if ($user_type == "alumni") {
              echo '
              <div class="absolute bottom-7 mt-3 flex justify-center items-center w-10/12">
                <button id="createJobPost" class="bg-blue-400 rounded-md text-white w-full py-3 hover:bg-blue-500">
                  <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="-2 -2 24 24">
                    <path fill="white" d="m5.72 14.456l1.761-.508l10.603-10.73a.456.456 0 0 0-.003-.64l-.635-.642a.443.443 0 0 0-.632-.003L6.239 12.635l-.52 1.82zM18.703.664l.635.643c.876.887.884 2.318.016 3.196L8.428 15.561l-3.764 1.084a.901.901 0 0 1-1.11-.623a.915.915 0 0 1-.002-.506l1.095-3.84L15.544.647a2.215 2.215 0 0 1 3.159.016zM7.184 1.817c.496 0 .898.407.898.909a.903.903 0 0 1-.898.909H3.592c-.992 0-1.796.814-1.796 1.817v10.906c0 1.004.804 1.818 1.796 1.818h10.776c.992 0 1.797-.814 1.797-1.818v-3.635c0-.502.402-.909.898-.909s.898.407.898.91v3.634c0 2.008-1.609 3.636-3.593 3.636H3.592C1.608 19.994 0 18.366 0 16.358V5.452c0-2.007 1.608-3.635 3.592-3.635h3.592z" />
                  </svg>
                  Job post
                </button>
              </div>';
            }
            ?>
          </div>

          <!-- CENTER DIV -->
          <div class="center-div w-full jobDescript">
            <div class="content-div rounded-md text-sm h-full">
              <div id="jobDescWrapper" class="tab-content p-3 w-full mx-auto hidden h-full">
                <!-- JOB DESC. -->
                <div class="job-des h-full center-shadow overflow-y-auto p-5" id="job-description">
                  <iconify-icon id="backToCard" icon="ep:back" style="color: #a0a0a0;" width="40" height="40"></iconify-icon>
                  <!-- Company Name and Image -->
                  <div class="flex py-10 px-16">
                    <div>
                      <img id="viewJobLogo" class="w-24 h-24 object-contain rounded-full">
                    </div>
                    <div class="pl-4">
                      <h2 id="viewJobTitle" class="text-lg font-bold"></h2>
                      <p id="viewJobCompany" class="text-sm"></p>
                      <div class="flex items-center pt-2">
                        <!-- <i class="fa-solid fa-location-dot text-sm pr-1 text-gray-400"></i> -->
                        <p class="text-sm text-gray-400"></p>
                      </div>
                      <div class="flex items-center">
                        <!-- <p class="text-sm text-gray-400 pr-1">Posted by:</p> -->
                        <p id="viewJobAuthor" class="text-sm text-green-500 font-bold"></p>
                      </div>
                      <div class="flex items-center">
                        <p class="text-sm text-gray-400 pr-1">Posted
                          <span class="font-semibold" style="font-size: 1rem">·</span>
                          <span id="viewJobDatePosted"></span>
                        </p>
                      </div>

                      <!-- Buttons -->
                      <div id="applicationBtn" class="flex items-center space-x-4 mt-4"></div>
                    </div>
                  </div>

                  <!-- Horizontal Line -->
                  <div class="flex justify-center px-10">
                    <hr class="w-full h-2 border-black">
                  </div>

                  <!-- Project Overview -->
                  <div class="px-10 py-2">
                    <h3 class="font-semibold">Job Description</h3>
                    <p id="jobDescript" class="indented text-justify text-sm text-dirtyWhite"></p>
                  </div>

                  <!-- Qualifications -->
                  <div class="px-10 py-2">
                    <h3 class="font-semibold">Qualifications:</h3>
                    <p id="viewJobQuali" class="indented text-justify text-dirtyWhite"></p>
                  </div>

                  <!-- location -->
                  <div class="px-10 py-2">
                    <h3 class="font-semibold">Location</h3>
                    <div class="flex items-center gap-2">
                      <iconify-icon icon="fluent:location-12-filled" style="color: #6c6c6c;"></iconify-icon>
                      <span id="locationContainer" class="text-justify text-sm text-dirtyWhite"></span>
                    </div>

                  </div>

                  <!-- Skills -->
                  <div class="px-10 py-2">
                    <h3 class="font-semibold">Tags</h3>
                    <div id="skillsContainer" class="flex flex-wrap gap-3 indented"></div>
                  </div>

                </div>

              </div>
              <div class="h-full">
                <div id="jobCard" class="w-full h-full grid grid-cols-3 overflow-y-auto gap-2 p-5"></div>
              </div>

            </div>

          </div>

        </div>


        <div id="createJobModal" class="post modal fixed inset-0 z-50 flex justify-center p-3 hidden">
          <div id="jobContainer" class="relative w-10/12 mx-auto lg:mx-0 lg:w-1/3 no-scrollbar py-3 px-4 bg-white rounded-md h-3/4 overflow-y-auto slide-bottom">
            <h5 class="text-center font-bold text-xl text-greyish_black py-2 border-b border-gray-300">Career Posting</h5>

            <form id="jobPostingForm" enctype="multipart/form-data">
              <!-- job title -->
              <div>
                <label for="job_title" class="block my-2 text-sm  text-gray-500">Job Title</label>
                <input type="text" id="job_title" name="job_title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-3 outline-none" placeholder="ex: Software Engineer" required>
              </div>

              <!-- company name -->
              <div>
                <label for="companyName" class="block my-2 text-sm  text-gray-500">Company Name</label>
                <input type="text" id="companyName" name="companyName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-3 outline-none" placeholder="ex: Accenture" required>
              </div>

              <!-- job description -->
              <div>
                <label for="jobDesc" class="block my-2 text-sm  text-gray-500">Job Description</label>
                <textarea id="jobDesc" name="jobDesc" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-3 outline-none h-32" placeholder="Please provide a detailed job description including responsibilities, qualifications, and any other relevant information that will help potential candidates understand the role." required></textarea>
              </div>

              <!-- job qualification -->
              <div>
                <label for="job_quali" class="block my-2 text-sm  text-gray-500">Job Qualification</label>
                <textarea name="job_quali" id="job_quali" class="bg-gray-50 h-32 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-3 outline-none" placeholder="List the required qualifications, skills, and experience candidates need to succeed in this role. Include education level, technical proficiencies, certifications, and any other relevant criteria." required></textarea>
              </div>

              <!-- company name -->
              <div>
                <label for="jobLocation" class="block my-2 text-sm  text-gray-500">Location</label>
                <input type="text" id="jobLocation" name="jobLocation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-3 outline-none" placeholder="ex: San Jose Del monte Bulacan" required>
              </div>

              <!-- salary range -->
              <div>
                <label for="job_title" class="block my-2 text-sm  text-gray-500">Salary Range</label>
                <div class="flex flex-wrap gap-2">

                  <!-- minimum -->
                  <div class="border border-gray-300 text-gray-900 text-sm rounded-lg flex-1 py-3 outline-none flex items-center">
                    <span>
                      <iconify-icon icon="tabler:currency-peso" style="color: #626262;" width="24" height="24"></iconify-icon>
                    </span>
                    <input type="number" name="minSalary" id="minSalary" class="outline-none" placeholder="Minimum" required>
                  </div>

                  <!-- maximum -->
                  <div class="border border-gray-300 text-gray-900 text-sm rounded-lg flex-1 py-3 outline-none flex items-center">
                    <span>
                      <iconify-icon icon="tabler:currency-peso" style="color: #626262;" width="24" height="24"></iconify-icon>
                    </span>
                    <input type="number" name="maxSalary" id="maxSalary" class="outline-none" placeholder="Maximum" required>
                  </div>
                </div>
              </div>

              <!-- company logo -->
              <div class="text-sm my-2 py-3">
                <span for="companyLogo" class="block my-2 text-sm text-gray-500">Company Logo</span>
                <input id="companyLogo" name="companyLogo" type="file" accept="image/jpeg" maxlength="1048576" required>
              </div>

              <!-- tags -->
              <div>
                <label class="block my-2 text-sm  text-gray-500">Tags</label>
                <div class="flex flex-wrap gap-2">
                  <!-- tag 1 -->
                  <div class="flex items-center gap-1">
                    <iconify-icon icon="gala:add" style="color: #60a5fa;" width="24" height="24"></iconify-icon>
                    <input id="tag1" name="tag1" type="text" class="jobTag bg-gray-50 border border-gray-300 text-gray-900 
                    text-sm rounded-lg w-full p-2 outline-none" placeholder="ex: Design" required>
                  </div>

                  <!-- tag 2 -->
                  <div class="flex items-center gap-1">
                    <iconify-icon icon="gala:add" style="color: #60a5fa;" width="24" height="24"></iconify-icon>
                    <input id="tag2" name="tag2" type="text" class="jobTag bg-gray-50 border border-gray-300 text-gray-900 
                    text-sm rounded-lg w-full p-2 outline-none" placeholder="ex: Content Creation" required>
                  </div>

                  <!-- tag 3 -->
                  <div class="flex items-center gap-1">
                    <iconify-icon icon="gala:add" style="color: #60a5fa;" width="24" height="24"></iconify-icon>
                    <input id="tag3" name="tag3" type="text" class="jobTag bg-gray-50 border border-gray-300 text-gray-900 
                    text-sm rounded-lg w-full p-2 outline-none" placeholder="ex: Python" required>
                  </div>

                </div>

              </div>

              <div class="flex justify-end items-center gap-2">
                <button id="cancelJobPosting" type="button" class="text-gray-400 px-3 py-2">Cancel</button>
                <button type="submit" class="px-3 py-2 text-white bg-postButton hover:bg-postHoverButton rounded-md">Post Job</button>
              </div>

            </form>
          </div>
        </div>

        <!-- success prompt -->
        <div id="successJobModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
          <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
            <svg class="block mx-auto" width="115px" height="115px" viewBox="0 0 133 133" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <g id="check-group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <circle id="filled-circle" fill="#47CF73" cx="66.5" cy="66.5" r="54.5" />
                <circle id="white-circle" fill="#FFFFFF" cx="66.5" cy="66.5" r="55.5" />
                <circle id="outline" stroke="#47CF73" stroke-width="4" cx="66.5" cy="66.5" r="54.5" />
                <polyline id="check" stroke="#FFFFFF" stroke-width="5.5" points="41 70 56 85 92 49" />
              </g>
            </svg>
            <h1 class=" text-3xl font-bold text-green-500 text-center">Thank you</h1>
            <p class=" text-lg text-center text-gray-500">"Your Job Post is Being Reviewed!"</p>
          </div>
        </div>


      </div>

      <!-- event detail modal -->
      <div id="eventModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
        <div id="eventContainer" class="modal-container w-11/12 mx-auto lg:mx-0 lg:w-2/5 h-5/6 overflow-y-auto bg-white rounded-md py-7 px-12 
          text-greyish_black flex flex-col gap-2">

          <!-- Event images -->
          <img id="headerImg" class="w-44 h-44 block mx-auto  rounded-full" src="" alt="">

          <p id="eventTitleModal" class="text-center text-2xl text-accent font-black"></p>
          <pre id="eventDescript" class="text-gray-500 text-justify w-full indented"></pre>
          <p class="text-lg font-bold text-greyish_black">WHEN AND WHERE</p>
          <p class="text-sm text-gray-500">Date: <span id="eventDateModal"></span></p>
          <p class="text-sm text-gray-500">Place: <span id="eventPlaceModal"></span></p>
          <p class="text-sm text-gray-500">Start time: <span id="eventTimeModal"></span></p>
        </div>
      </div>

      <!-- status modal -->
      <div id="postStatusModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="postStatus bg-white rounded-md mx-2 lg:mx-0 w-full lg:w-2/6 p-5 flex flex-col gap-3">
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
          <div class="flex-col text-sm border-t border-gray-400 py-2 commentStatus">
            <div class="flex gap-2 text-gray-500 text-xs">
              <p>Likes: <span id="statusLikes"></span></p>
              <p>Comments: <span id="statusComment"></span></p>
            </div>

            <div id="commentStatus" class="flex flex-col gap-2 p-2 mt-2"></div>
          </div>
        </div>
      </div>

      <!-- deletion modal -->
      <div id="delete-modal" class="modal hidden fixed inset-0 h-full w-full flex items-center justify-center ">
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
              <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete your comment?</h3>
              <button id="deletePostbtn" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                Yes, I'm sure
              </button>
              <button type="button" class="closeDeleteBtn text-gray-400">No, cancel</button>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- migration modal -->
    <?php
    $today = date('F');

    if ($user_type == 'student' && strtotime($today) >= strtotime('August') && $studentYr == 4) {
      $migration = new Migration($studentNo);
      $isNotifShown = $migration->isNotifAlreadyShown($mysql_con); //for checking if the modal is already shown to the studen

      if (!$isNotifShown) {
        echo
        '<div class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center migrationModal">
          <div class="bg-white   w-10/12 mx-auto lg:mx-0 lg:w-2/5 p-5 flex flex-col gap-3 text-greyish_black">
            <h2 class="text-2xl font-semibold">We noticed something about this account!</h2>
            <p>Your account appears to be prepared for account migration to alumni.
              Verify that you graduated from Bulacan State University before processing your immigration.</p>

            <p class="mt-3">The following capabilities have been introduced to alumni accounts:
              <span class="font-semibold italic"> job applications, job postings, and alumni graduate tracker.</span>
            </p>
            <!-- note -->
            <div class="text-red-500 flex gap-2 border border-gray-300 rounded-md p-3">
              <iconify-icon icon="ep:warning-filled" width="24" height="24"></iconify-icon>
              <div class="flex gap-2">
                <span class="font-semibold">Note:</span>
                <p>You can\'t undo this confirmation. Make sure you\'re prepared to migrate your account before continuing.</p>
              </div>

            </div>

            <div class="flex justify-end gap-2">
              <button class="cancelMigration text-gray-400 hover:text-gray-500">Cancel</button>
              <button class="migrateConfirmBtn rounded-md text-white bg-green-400 hover:bg-green-500 px-3 py-2">Confirm</button>
            </div>
          </div>
        </div>';

        // insert new data in migration
        $migration->createEntry($mysql_con);
      }
    }

    ?>
    <!-- cancel migration -->
    <div class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center cancelMigrationModal hidden">
      <div class="bg-white rounded-md w-10/12 mx-auto lg:mx-0 lg:w-2/6 p-5 flex flex-col gap-3 text-greyish_black">
        <h2 class="text-xl font-semibold">Confirmation</h2>
        <p>In the edit profile area, you can see this if you chose to move your account.</p>
        <div class="flex justify-end gap-2">
          <button id="cancelMigrationBtn">Cancel</button>
          <button id="closeMigrationModal" class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-2 rounded-md">Okay</button>
        </div>
      </div>
    </div>

    <!-- additional information for migrating -->
    <div class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center additionalInfo hidden">
      <div class="bg-white rounded-md w-10/12 mx-auto lg:mx-0 lg:w-2/5 p-5 flex flex-col gap-3 text-greyish_black">
        <h2 class="text-lg font-semibold border-b border-gray-400 py-2">Additional Information for Migrating</h2>
        <form id="migrationForm">
          <?php
          echo '
          <input name="studNoMigration" type="hidden" value="' . $studentNo . '">
          <input name="personIDMigration" type="hidden" value="' . $personID . '">
          <input name="colCodeMigration" type="hidden" value="' . $colCode . '">
          <input name="usernameMigration" type="hidden" value="' . $username . '">
          <input name="courseID" type="hidden" value="' . $courseID . '">';

          ?>

          <!-- employment status -->
          <div class="flex flex-col">
            <label for="empStatData">1. ) Employment Status</label>
            <select name="empStatData" id="empStatData" class="py-2 text-gray-400 border-b border-gray-300 rounded-b-md hover:border-blue-500 hover:border-b-2">
              <option value="Employed">Employed</option>
              <option value="Unemployed">Unemployed</option>
              <option value="Self-employed">Self-employed</option>
              <option value="Retired">Retired</option>
            </select>
          </div>

          <!-- batch year -->
          <div class="flex flex-col mt-5">
            <label for="batchYrData">2. ) Batch Year</label>
            <select name="batchYrData" id="batchYrData" class="py-2 text-gray-400 border-b border-gray-300 rounded-b-md hover:border-blue-500 hover:border-b-2"></select>
          </div>

          <div class="flex flex-col mt-5 gap-2">
            <button class="bg-green-400 hover:bg-green-500 text-white rounded-md py-2 font-bold">Migrate</button>
            <button type="button" class="hover:text-lg hover:text-gray-500 text-gray-400 cancelAdditionalInfo">Cancel</button>
          </div>

        </form>
      </div>
    </div>

    <!-- success migration -->
    <div class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center successMigrationModal hidden">
      <div class="bg-white rounded-md w-10/12 mx-auto lg:mx-0 lg:w-2/6 p-5 flex flex-col gap-3 text-greyish_black">
        <!-- success animation -->
        <div class="success-checkmark">
          <div class="check-icon">
            <span class="icon-line line-tip"></span>
            <span class="icon-line line-long"></span>
            <div class="icon-circle"></div>
            <div class="icon-fix"></div>
          </div>
        </div>
        <h2 class="text-xl text-center text-green-500 font-bold">Migration Successful</h2>
        <p class="text-center text-gray-500">After 5 seconds this account will sign out automatically to refresh your account</p>
      </div>
    </div>

    <!-- applicant message -->
    <div class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center applicantMsg hidden">
      <div class="bg-white rounded-2xl w-10/12 mx-auto lg:mx-0 lg:w-1/2 p-5 flex flex-col gap-3 text-gray-700 h-1/2">
        <h3 class="text-xl font-semibold">Message for recruiter</h3>
        <p>Tell us about yourself here so that the recruiter may learn more about you. Don't forget to include your contact information so that they can easily contact you.</p>
        <textarea id="applicantMsg" class="h-4/5 outline-none border border-gray-400 rounded-lg resize-none p-5 no-scrollbar"></textarea>
        <div class="flex justify-end gap-2">
          <button class="text-gray-400 hover:text-gray-500 msgCancelBtn">Cancel</button>
          <button class="bg-green-400 hover:bg-green-500 text-white font-semibold rounded-md px-3 py-2 applyJobBtn">Apply</button>
        </div>

      </div>
    </div>

    <!-- success application   prompt -->
    <div id="successApplicationModal" class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center hidden">
      <div class="modal-container w-10/12 mx-auto lg:mx-0 lg:w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
        <svg class="block mx-auto" width="115px" height="115px" viewBox="0 0 133 133" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="check-group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <circle id="filled-circle" fill="#47CF73" cx="66.5" cy="66.5" r="54.5" />
            <circle id="white-circle" fill="#FFFFFF" cx="66.5" cy="66.5" r="55.5" />
            <circle id="outline" stroke="#47CF73" stroke-width="4" cx="66.5" cy="66.5" r="54.5" />
            <polyline id="check" stroke="#FFFFFF" stroke-width="5.5" points="41 70 56 85 92 49" />
          </g>
        </svg>
        <h1 class=" text-3xl font-bold text-green-500 text-center">Applied Successfully</h1>
        <p class=" text-center text-gray-500">Your message and resume have been sent to the job recruiter.</p>
        <button class=" bg-postButton hover:bg-postHoverButton text-white px-4 py-2 w-full  rounded-md font-bold">Close</button>
      </div>
    </div>

    <!-- inbox -->
    <div class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center messageJob hidden">
      <div class="bg-white rounded-2xl w-10/12 mx-auto lg:mx-0 lg:w-1/2 p-5 flex flex-col gap-3 text-gray-700 h-1/2">
        <div class="flex items-center gap-2">
          <iconify-icon icon="fluent-mdl2:back" class="cursor-pointer closeMsgJob" width="24" height="24"></iconify-icon>
          <h3 class="text-2xl flex items-center">Applicant Message</h3>
        </div>

        <p></p> <!--applicant name-->
        <p></p> <!--applied-->
        <pre class="w-full h-4/5 overflow-y-auto p-5 border border-gray-300 rounded-md"></pre>
      </div>
    </div>

    <!-- error prompt -->
    <div id="errorResumeModal" class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center p-3 hidden">
      <div class="modal-container w-10/12 mx-auto lg:mx-0 lg:w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-1">
        <lord-icon class="block mx-auto" src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" delay="1000" colors="primary:#e83a30,secondary:#e83a30" style="width:150px;height:150px">
        </lord-icon>
        <h1 class=" text-3xl font-bold text-red-500 text-center">Oopss!</h1>
        <p class=" text-center text-gray-500">It appears that you have forgotten to include your resume. Please do not forget to review and revise your resume to make it relevant to the position you are applying for. To access the resume editing area, simply click the link below.</p>
        <span id="directToResume" class="text-blue-400 hover:font-bold hover:text-blue-500 mt-5 text-center w-full cursor-pointer">Click Me</span>
        <span class="w-full text-center text-sm text-gray-400 hover:text-gray-500 mb-5 cursor-pointer noresumeBtn">Cancel</span>
      </div>

    </div>

    <!-- loading modal -->
    <div class="modal fixed inset-0 h-full w-full flex flex-col items-center justify-center hidden">
      <div class="loadingProfile flex items-center justify-center">
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


  <script src="../student-alumni/js/hompage.js"></script>
  <script src="../student-alumni/js/announcementscript.js"></script>
  <script src="../student-alumni/js/currentyear.js"></script>
  <script src="../student-alumni/js/migration.js"></script>
  <script src="../student-alumni/js/eventscript.js"></script>
  <script src="../student-alumni/js/jobposting.js"></script>
  <script src="../student-alumni/js/notification.js"></script>
  <script src="../student-alumni/js/post.js"></script>
  <script src="../student-alumni/js/userformtracer.js"></script>
  <script src="../student-alumni/js/searchProfile.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
  <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
</body>

</html>