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

  $username = $_SESSION['username'];

  //get the person ID of that user
  $query = "SELECT 'student' AS user_type, student.personID
  FROM student
  WHERE student.username = '$username'
  UNION
  SELECT 'alumni' AS user_type, alumni.personID
  FROM alumni
  WHERE alumni.username = '$username'
  UNION
  SELECT 'not found' AS user_type, NULL
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
        <nav class="grid grid-cols-3 gap-4 p-6 bg-white text-black shadow-lg">
          <a href="homepage.php" class="col-span-1 flex items-center">
            <img src="../assets/bulsu_connect_img/bulsu_connect_logo.png" alt="Logo" class=" w-32 h-16" />
          </a>

          <div class="col-span-3 md:col-span-1 flex items-center justify-center mt-4 md:mt-0 relative">
            <div class="relative w-full">
              <input type="text" id="searchUser" placeholder="Search" class="pl-10 pr-4 py-3 w-full text-black border-accent center-shadow p-3 rounded-md shadow text-sm border outline-none" />
              <i class="absolute left-3 top-1/2 transform -translate-y-1/2 fas fa-search text-accent text-base"></i>
            </div>
            <div id="searchProfile" class="absolute top-16 bg-white rounded-b-lg p-3 z-50 w-full hidden"></div>
          </div>

          <div class="col-span-2 md:col-span-1 flex items-center justify-end">
            <!-- set profile image -->
            <?php
            if ($profilepicture == "") {
              echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
            } else {
              $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
              echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-10 h-10 profile-icon" />';
            }

            ?>
            <p id="accFN" class="mr-4 text-sm font-medium text-greyish_black p-4">
              <?php
              echo $fullname;
              ?>
            </p>

            <!-- Dropdown Button -->
            <div class="relative">
              <button id="dropdown-btn" class="bg-transparent border-none outline-none">
                <i class="fas fa-chevron-down text-lg"></i>
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
      </div>

      <!--SUB NAVBAR-->
      <div class="sub-navbar-wrapper fixed flex justify-center h-24 top-24 mt-1 left-0 right-2 sm:top-24 sm:mt-1 sm:justify-center z-20">

        <ul class="sub-navbar bg-accent text-white flex flex-wrap items-center justify-evenly h-max sm:w-2/6 md:w-5/12 p-4 text-sm" style="width: 50%;">
          <!--FEED TAB-->
          <li class="w-full sm:w-auto px-5">
            <a href="#tabs-1" class="flex items-center justify-center w-full" id="feedLink" onclick="toggleFeed()">
              <svg class="inline icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path d="M10 20v-6h4v6h5v-8h3L12 3L2 12h3v8h5Z" />
              </svg>
              <span class="text-white font-semibold text" id="feedText">Feed</span>
            </a>
          </li>

          <!--LINE SEPARATOR-->
          <div class="h-10 w-0.5 bg-white md:5"></div>

          <!--EVENTS TAB-->
          <li id="eventLI" class="w-full sm:w-auto px-5">
            <a href="#tabs-2" id="eventsLink" class="inline-flex items-center">
              <svg class="inline icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19 19H5V8h14m-3-7v2H8V1H6v2H5c-1.11 0-2 .89-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-1V1m-1 11h-5v5h5v-5Z" />
              </svg>
              <span id="eventsText" class="text-white font-semibold text" id="eventText">Events</span>
            </a>
          </li>

          <!--LINE SEPARATOR-->
          <div class="h-10 w-0.5 bg-white md:5"></div>

          <!--JOB HUNT TAB-->
          <li class="w-full sm:w-auto px-5">
            <a href="#tabs-3" id="jobHuntLink" class="flex items-center">
              <svg class="inline icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentColor" d="M7 5V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h4ZM4 15v4h16v-4H4Zm7-4v2h2v-2h-2ZM9 3v2h6V3H9Z" />
              </svg>
              <span id="JobHuntText" class="text-white font-semibold text" id="jobHuntText">Job Hunt</span>
            </a>
          </li>
        </ul>

      </div>

      <!-- TAB 1 -->
      <div id="tabs-1">
        <!-- Container for MAIN FEED -->
        <div id="mainFeedContainer" class="flex pt-48 z-10 w-full h-full scrollable-container">
          <!-- LEFT DIV -->
          <div class="left-div fixed top-32 left-0 w-1/4 h-full px-8 z-50">

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
            <div id="target-div-job" class="div-btn flex items-center hover:bg-gray-100 rounded-md h-10 p-2 mt-1">
              <button id="verif-btn" onclick="toggleColorJob(), toggleJobPost()">
                <svg class="inline fa" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                  <path fill="currentColor" d="m10.6 16.6l7.05-7.05l-1.4-1.4l-5.65 5.65l-2.85-2.85l-1.4 1.4l4.25 4.25ZM12 22q-2.075 0-3.9-.788t-3.175-2.137q-1.35-1.35-2.137-3.175T2 12q0-2.075.788-3.9t2.137-3.175q1.35-1.35 3.175-2.137T12 2q2.075 0 3.9.788t3.175 2.137q1.35 1.35 2.138 3.175T22 12q0 2.075-.788 3.9t-2.137 3.175q-1.35 1.35-3.175 2.138T12 22Z" />
                </svg>
                <span class="ps-3 text-sm text-greyish_black font-medium">Job Repository</span>
              </button>
            </div>

            <!-- Yearbook -->
            <!-- show only yearbook for alumni -->
            <?php
            if ($user_type == "alumni") {
              require '../PHP_process/deploymentTracer.php';
              $isAvailable = retrievedDeployment($mysql_con);

              // check if there's still available to be answer
              if ($isAvailable != "None") {
                echo '
                <div id="target-div-yearbook" class="div-btn flex items-center hover:bg-gray-100 rounded-md h-10 p-2 mt-1">
                  <button id="yearbook-btn" onclick="toggleYearbook()">
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

                  <p id="noPostMsgFeed" class="text-blue-400 text-center hidden">No available post</p>
                </div>
              </div>

              <!-- Job Post Feed -->
              <div id="jobRepo" class="hidden h-full grid grid-cols-3 gap-2 overflow-y-auto no-scrollbar py-3"></div>

            </div>

            <span id="promptMsg" class=" slide-bottom fixed bottom-28 px-4 py-2 z-50 bg-accent text-white rounded-sm hidden font-bold">Comment successfully added</span>
          </div>

          <!-- RIGHT DIV -->
          <div class="right-div fixed top-32 right-2 w-1/4 h-full px-8">
            <!-- Content for the right div -->
            <p class="font-medium border-b-2 border-grayish ml-auto block text-sm pb-2 mb-4 text-greyish_black">University News</p>
            <div class="h-1/3">
              <div class="swiper announcementSwiper">
                <div id="announcementWrapper" class="swiper-wrapper"></div>
                <div class="swiper-pagination"></div>
              </div>
            </div>

          </div>

          <!-- MODALS && OTHER OBJECTS THAT HAVE Z-50 -->
          <!-- Notifications Tab -->
          <div id="notification-tab" class="notification-tab hidden fixed top-24 mt-1 right-1 h-full bg-black bg-opacity-50 w-3/4 z-50">
            <div class="notification-content bg-white center-shadow border-2 px-4 pt-4 pb-20 h-full md:w-2/6 lg:w-3/6 xl:w-2/5 2xl:w-2/5 overflow-y-auto hide-scrollbar">
              <h1 class="text-greyish_black text-lg font-bold mb-4">Notifications</h1>

              <div class="flex space-x-4 mb-4">
                <button id="btnNotifAll" class="hover:bg-gray-500 rounded-full  px-4 py-2 text-sm font-semibold bg-accent text-white">All</button>
                <button id="btnNotifUnread" class="hover:bg-gray-500 rounded-full text-greyish px-4 py-2 text-sm font-semibold">Unread</button>
              </div>

              <p id="noNotifMsg" class="text-center my-4 text-blue-400 hidden">No available notification</p>
            </div>
          </div>


          <div id="modal" class="modal hidden fixed inset-0 h-full w-full flex items-center justify-center
            text-grayish  top-0 left-0">
            <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3">
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
                    <textarea id="TxtAreaAnnouncement" class="rar outline-none w-full h-full" type="text" placeholder="Say something here..."></textarea>
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
          top-0 left-0 p-5 hidden overflow-y-auto">
          <!-- modal body -->
          <div id="viewingJobModal" class="w-2/5 bg-white rounded-lg h-4/5 slide-bottom">

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
                <div class="flex items-center gap-2">
                  <iconify-icon icon="uiw:user" style="color: #868e96;" width="18" height="18"></iconify-icon>
                  <span id="jobApplicant"></span>
                </div>

                <div class="flex items-center gap-2">
                  <iconify-icon icon="ri:verified-badge-line" style="color: #868e96;" width="18" height="18"></iconify-icon>
                  <span id="statusJob"></span>
                </div>

              </div>
            </div>

          </div>
        </div>

        <!-- viewing of post -->
        <div id="viewingPost" class="post modal fixed hidden inset-0 flex items-center justify-center p-3">
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

        <!-- comment -->
        <div id="commentPost" class="post modal fixed inset-0 flex justify-center p-3 hidden">
          <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 mt-14 flex flex-col gap-1">
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
              <textarea id="commentArea" class="w-full h-28 outline-none text-gray-400" placeholder="Comment your thought!"></textarea>
            </div>

            <button id="commentBtn" class="px-3 py-2 rounded-lg bg-red-950 text-white font-semibold block ml-auto text-sm" disabled>Comment</button>
          </div>
        </div>
        <!-- Container for Yearbook -->
        <div id="yearbookContainer" class="hidden flex pt-48 z-10 w-full">

          <!-- front page -->
          <div id="frontpageTracer" class="h-full w-full overflow-y-auto flex flex-col gap-3 items-center p-3">
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
          <div id="questionsContainer" class="h-full w-full overflow-y-auto flex flex-col gap-3 items-center p-3 hidden">
            <div class="w-1/2">
              <h3 id="categoryNameQuestion" class="text-3xl font-extrabold text-accent text-center">Category Name</h3>
            </div>
            <div class="questions h-full w-1/2 p-2 overflow-y-auto"></div>
            <div id="navigationWrapper" class="w-1/2"></div>
          </div>

        </div>

        <!-- report modal -->
        <div id="reportModal" class="post modal hidden fixed inset-0 z-50 flex items-center justify-center p-3">
          <div class="modal-container w-2/5 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
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
            <h1 class=" text-3xl font-bold text-green-500 text-center">Thank you</h1>
            <p class=" text-lg text-center text-gray-500">"Your feedback is important to us, and we take all reports seriously"</p>
          </div>
        </div>

        <!-- announcement modal -->
        <div id="announcementModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
          <div id="announcementContainer" class="modal-container w-2/5 h-5/6 overflow-y-auto bg-white rounded-md py-3 px-12 text-greyish_black flex flex-col gap-2">
            <!-- header -->
            <div class="flex gap-2 items-center py-2">
              <img src="../images/BSU-logo.png" alt="Logo" class="w-10 h-10" />
              <span class="ml-2 text-xl font-bold">BulSU Update</span>
            </div>

            <!-- headline image -->
            <img id="headline_img" class="h-60 object-cover bg-gray-300 rounded-md" src="../images/bsu-header5.jpg" alt="">

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

        <!-- event modal -->
        <div id="eventModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
          <div id="eventContainer" class="modal-container w-2/5 h-5/6 overflow-y-auto bg-white rounded-md py-7 px-12 
          text-greyish_black flex flex-col gap-2">

            <!-- Event images -->
            <div id="eventImgWrapper" class="flex flex-wrap justify-center gap-1">

            </div>

            <p id="eventTitleModal" class="text-center text-2xl text-accent font-black"></p>
            <pre id="eventDescript" class="text-gray-500 text-justify w-full indented"></pre>
            <p class="text-lg font-bold text-greyish_black">WHEN AND WHERE</p>
            <p class="text-sm text-gray-500">Date: <span id="eventDateModal"></span></p>
            <p class="text-sm text-gray-500">Place: <span id="eventPlaceModal"></span></p>
            <p class="text-sm text-gray-500">Start time: <span id="eventTimeModal"></span></p>
            <!-- <p id="author" class="text-sm text-gray-500">By: <span class="text-accent">Media Relations Office</span></p> -->
            <p class="text-lg text-greyish_black font-bold">EXPECTATION</p>
            <div id="expectationList" class="flex flex-col gap-2"></div>
          </div>
        </div>

        <div id="restoreModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
          <div class="relative w-1/3 h-max p-3 bg-white rounded-md">
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

        <div id="profileModal" class="fixed inset-0 flex pt-10 justify-center z-50 bg-black bg-opacity-50 hidden">
          <div id="profileModalUser" class="bg-white rounded shadow-lg w-2/5 max-h-screen h-max overflow-y-auto slide-bottom">
            <!-- Cover Photo -->
            <div class="coverPhotoContainer">
              <img id="profileModalCover" alt="Cover Photo" class=" w-4/5 h-full md:h-56 mb-4 object-cover block mx-auto object-center">
            </div>
            <div class="px-4 md:px-6">

              <!-- Profile Picture and Info -->
              <div class="flex items-start mb-4">
                <img id="profileModalProfile" alt="Profile Picture" class=" w-16 h-16 md:w-28 md:h-28 rounded-full md:-mt-20 mr-4 ml-2 bg-white border-2">
                <div class="flex-grow">
                  <h2 id="profileModalFN" class=" md:text-lg font-bold text-gray-700">Patrick Joseph Pronuevo</h2>
                  <p id="profileModalUN" class="text-gray-500 text-sm">@3xjoseph</p>
                </div>

                <button class="px-3 md:px-4 py-2 text-xs md:text-sm bg-red-800 text-white rounded-md">Send Email</button>
              </div>

              <h2 class="text-md md:text-lg font-bold mb-2 text-greyish_black">Social Media</h2>
              <div class="flex gap-2 border-b border-gray-300 text-xs py-2 mb-2">
                <!-- social media links -->
                <!-- facebook -->
                <div class="flex items-center gap-2">
                  <iconify-icon icon="formkit:facebook" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                  <span id="facebookUN" class="text-center"></span>
                </div>

                <!-- instagram -->
                <div class="flex items-center gap-2">
                  <iconify-icon icon="formkit:instagram" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                  <span id="instagramUN" class="text-center"></span>
                </div>

                <!-- twitter -->
                <div class="flex items-center gap-2">
                  <iconify-icon icon="simple-icons:twitter" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                  <span id="twitterUN" class="text-center"></span>
                </div>

                <!-- linkedIN -->
                <div class="flex items-center gap-2">
                  <iconify-icon icon="uiw:linkedin" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                  <span id="linkedInUN" class="text-center"></span>
                </div>

              </div>

              <!-- user post -->
              <div id="userPostContainer" class="max-h-48 md:max-h-64 overflow-y-auto no-scrollbar">
                <div id="userPost" class="grid grid-cols-3 gap-4 p-2"></div>
                <p id="noProfileMsgSearch" class="text-center text-blue-400 my-2 hidden">No available Post</p>
              </div>

            </div>
          </div>
        </div>

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

      </div>

      <!-- TAB 2 -->
      <div id="tabs-2" class=" h-2/3">
        <!--IMAGE HEADER-->
        <div id="image-header-con" class="relative top-24 mt-1 z-10 h-full">
          <div class="flex items-center justify-center h-full">
            <div class="w-2/5 p-5">
              <h1 id="headerEvent" class=" text-gray-800 text-5xl lg:text-6xl font-bold">Get Ready to Dance with</h1>
              <p id="eventNameHeader" class=" text-4xl font-bold mb-2">Rainbow Pop</p>
              <button class="text-white font-bold bg-blue-300 px-5 py-3 my-2">
                <a id="connectURL" target="_blank">CONNECT WITH US</a>
              </button>
            </div>

            <!-- swiper -->
            <div class="w-1/2 flex justify-center items-center p-3 h-full">
              <div class="swiper mySwiper w-1/2 h-1/2">
                <div id="swiperWrapperEvent" class="swiper-wrapper"></div>
                <div class="swiper-pagination"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="separator h-10 mt-16"></div>

        <h1 id="eventName" class="text-5xl p-5 indented font-bold">RAINBOX POP</h1>
        <div class="flex flex-nowrap px-5">
          <!-- about image -->
          <div class="w-1/2 flex justify-center">
            <img id="aboutImg" class="h-3/4 w-3/4 rounded-md center-shadow object-contain bg-black">
          </div>

          <!-- about the event -->
          <div class="flex flex-col p-5 w-1/2">
            <h1 class="w-4/5 text-end text-3xl text-greyish_black font-bold">About the Event</h1>
            <p id="aboutEvent" class=" w-4/5 text-gray-500 text-justify">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
              dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
              ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
              fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt
              mollit anim id est laborum."</p>

            <!-- event details -->
            <div class="my-10 w-full">
              <h1 class="w-4/5 text-3xl text-greyish_black font-bold">Event Details</h1>
              <p class="w-4/5 text-gray-500">Date: <span id="eventDate"></span></p>
              <p class="w-4/5 text-gray-500">Place: <span id="eventPlace"></span></p>
              <p class="w-4/5 text-gray-500">Start time: <span id="eventStartTime"></span></p>
            </div>
          </div>


        </div>


        <!-- Expectation for event -->
        <div class="p-10 mb-10 bg-red-400 relative">
          <h1 class="w-full px-5 indented text-4xl text-center text-white font-bold">Expectation for this event</h1>

          <div id="expectContainer" class="flex flex-nowrap justify-center gap-3 my-5"></div>

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

            <div class="absolute bottom-7 mt-3 flex justify-center items-center w-10/12">
              <button id="createJobPost" class="bg-blue-400 rounded-md text-white w-full py-3 hover:bg-blue-500">
                <svg class="inline" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="-2 -2 24 24">
                  <path fill="white" d="m5.72 14.456l1.761-.508l10.603-10.73a.456.456 0 0 0-.003-.64l-.635-.642a.443.443 0 0 0-.632-.003L6.239 12.635l-.52 1.82zM18.703.664l.635.643c.876.887.884 2.318.016 3.196L8.428 15.561l-3.764 1.084a.901.901 0 0 1-1.11-.623a.915.915 0 0 1-.002-.506l1.095-3.84L15.544.647a2.215 2.215 0 0 1 3.159.016zM7.184 1.817c.496 0 .898.407.898.909a.903.903 0 0 1-.898.909H3.592c-.992 0-1.796.814-1.796 1.817v10.906c0 1.004.804 1.818 1.796 1.818h10.776c.992 0 1.797-.814 1.797-1.818v-3.635c0-.502.402-.909.898-.909s.898.407.898.91v3.634c0 2.008-1.609 3.636-3.593 3.636H3.592C1.608 19.994 0 18.366 0 16.358V5.452c0-2.007 1.608-3.635 3.592-3.635h3.592z" />
                </svg>
                Job post
              </button>
            </div>
          </div>

          <!-- CENTER DIV -->
          <div class="center-div w-full  overflow-y-auto jobDescript">
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
              <div>
                <div id="jobCard" class="w-full h-full grid grid-cols-3 gap-2 overflow-y-auto p-5"></div>
              </div>

            </div>

          </div>

        </div>

        <!-- error prompt -->
        <div id="errorResumeModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
          <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-1">
            <lord-icon class="block mx-auto" src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop" delay="1000" colors="primary:#e83a30,secondary:#e83a30" style="width:150px;height:150px">
            </lord-icon>
            <h1 class=" text-3xl font-bold text-red-500 text-center">Oopss!</h1>
            <p class=" text-center text-gray-500">Your resume appears to not have been set up yet. Set it first before applying.</p>
          </div>

        </div>

        <div id="createJobModal" class="post modal fixed inset-0 z-50 flex justify-center p-3 hidden">
          <div id="jobContainer" class="relative w-1/3 no-scrollbar py-3 px-4 bg-white rounded-md h-3/4 overflow-y-auto slide-bottom">
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

    </div>

  </div>


  </div>


  <script src="../student-alumni/js/hompage.js"></script>
  <script src="../student-alumni/js/announcementscript.js"></script>
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