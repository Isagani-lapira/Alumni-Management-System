<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Corinthia&family=Dancing+Script:wght@500&family=Exo+2:wght@700&family=Fasthand&family=Freehand&family=Montserrat:ital,wght@0,400;0,700;1,400;1,600;1,700;1,800&family=Poppins:ital,wght@0,400;0,700;1,400&family=Roboto:wght@300;400;500&family=Source+Sans+Pro:ital@1&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"
    integrity="sha256-6XMVI0zB8cRzfZjqKcD01PBsAy3FlDASrlC8SxCpInY=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../style/student-alumni.css" />
  <link href="../css/main.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
  <title>BulSU Connect</title>
</head>

<body>
  <!--CONTENT PAGE -->
  <div class="fixed top-0 w-full z-50">
    <div id="tabs" class="h-screen overflow-y-scroll hide-scrollbar">
      <!-- Navbar -->
      <div class="Navbar fixed top-0 left-0 right-0 z-30">
        <nav class="grid grid-cols-3 gap-4 p-6 bg-white text-black shadow-lg">
          <a href="homepage.html" class="col-span-1 flex items-center">
            <img src="../images/BSU-logo.png" alt="Logo" class="w-10 h-10" />
            <span class="ml-2 text-xl font-bold">BulSU Connect</span>
          </a>
      
          <div class="col-span-3 md:col-span-1 flex items-center justify-center mt-4 md:mt-0">
            <div class="relative w-full">
              <input
                type="text"
                placeholder="Search"
                class="pl-10 pr-4 py-3 w-full text-black text-sm border outline-none border-accent center-shadow p-3 rounded-md shadow text-sm border outline-none"
              />
              <i class="absolute left-3 top-1/2 transform -translate-y-1/2 fas fa-search text-accent text-base"></i>
            </div>
          </div>
      
          <div class="col-span-2 md:col-span-1 flex items-center justify-end">
            <img href="profile.html" class="mr-4">
            <img src="../images/ye.jpg" alt="Profile Icon" class="w-10 h-10 profile-icon" />
            </img>
            <p href="profile.html" class="mr-4 text-sm font-medium text-greyish_black p-4">
              Patrick Joseph Pronuevo
            </p>
      
            <!-- Dropdown Button -->
            <div class="relative">
              <button id="dropdown-btn" class="bg-transparent border-none outline-none">
                <i class="fas fa-chevron-down text-lg"></i>
              </button>
            </div>
      
            <!-- Dropdown Content -->
            <div id="dropdown-content" class="absolute bg-white rounded-md shadow-lg mt-40 justify-evenly right-8 hidden w-72 p-2">
              <a href="profile.html" class="flex items-center py-2 px-4 hover:bg-gray-200 rounded-lg">
                <i class="fas fa-light fa-user text-md pr-2"></i>See Profile
              </a>
              <a href="/student-alumni/login.php" class="flex items-center py-2 px-4 hover:bg-gray-200 rounded-lg">
                <i class="fas fa-sign-out-alt text-md pr-2"></i>Logout
              </a>
            </div>
          </div>
        </nav>
      </div>

      <!--SUB NAVBAR-->
      <div class="sub-navbar-wrapper fixed flex justify-center h-24 top-24 mt-1 left-0 right-3 sm:top-24 sm:mt-1 sm:justify-center z-20">

        <ul class="sub-navbar bg-accent text-white flex flex-wrap items-center justify-evenly h-max sm:w-2/6 md:w-5/12 p-4 text-sm" style="width: 45%;">
          <!--FEED TAB-->
          <li class="w-full sm:w-auto">
            <a href="#tabs-1" class="flex items-center" id="feedLink" onclick="toggleFeed()">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAA5ElEQVR4nO3SMUoDURDG8aewaQSbgNYW2tnoDaz0CrmEYGObK3iHnMFKWy30AhamUrAVbSz0J4sT8lgSdjeriJgPpnnzzX/ezHsp/SvhpIyfAK9gaKozrH4XvIdRgN8ixFmvK3wN5wF8wSEO8Bxnl1hfFN7HdYCesJfldvEQuVtstoVv4S4A99iu8Yyx0xRe3u4xCm+wUTPlVTblfh083+9Fk/1W3ukVR/OMg+yHlCoajfxVW2R1JWNQNRzjPTNpCp8or8UHTtMsdW2QFjGao7/XILXwLBt0X9EspRae32uwVKroE2OChkKsj2YoAAAAAElFTkSuQmCC"
                alt="Home Icon" class="w-5 h-5 mr-2 text-white" />
              <span class="inline-block text-white font-semibold" id="feedText">Feed</span>
            </a>
          </li>
        
          <!--LINE SEPARATOR-->
          <div class="h-10 w-0.5 bg-white md:5"></div>
        
          <!--EVENTS TAB-->
          <li class="w-full sm:w-auto">
            <a href="#tabs-2" id="eventsLink" class="inline-flex items-center">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAqUlEQVR4nN3UQQrCMBCF4aw9hLj1JuodXCkU9FzeSlzYa2g/kaZQxGpp2kp9EEiYyfyZR5IQ/laiQk954wCwQa67cqw/AVKKV7r2026X/aMBUhV+DghDWxTaFdti0QsAs5d1hgKXeqwTAHOccYzrPe4RcEjuQGlFEcepNs/aOvA1Abt4au9OngyoWXOrrBrkFmHZuLmMF9N5B10Bg3/X62dCSnGsGgGT1ANaBPWW0/Q4dwAAAABJRU5ErkJggg=="
                alt="Events Icon" class="w-5 h-5 mr-2 text-white" />
              <span id="eventsText" class="text-white font-semibold">Events</span>
            </a>
          </li>
        
          <!--LINE SEPARATOR-->
          <div class="h-10 w-0.5 bg-white md:5"></div>
        
          <!--JOB HUNT TAB-->
          <li class="w-full sm:w-auto">
            <a href="#tabs-3" id="jobHuntLink" class="flex items-center">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAX0lEQVR4nO2UQQqAMAwE93kWny1+xPqPKT0KtiqJFDRz3bADe4gUeECDpyUzsGMnA+lMUAMvtuYMpi3V6QnBDycCVmC5UXi4uxR4oWECGeHTgvz2s0s18CgHJusSgdwoBMOg0T9wg1YAAAAASUVORK5CYII="
                class="w-5 h-5 mr-2 text-white" alt="Job Hunt" />
              <span id="JobHuntText" class="text-white font-semibold">Job Hunt</span>
            </a>
          </li>
        </ul>
        
      </div>
      
      <!-- TAB 1 -->
      <div id="tabs-1">

        <!-- Container for MAIN FEED -->
        <div id="mainFeedContainer" class="flex pt-48 z-10 w-full h-full scrollable-container">

          <!-- LEFT DIV -->
          <div class="left-div fixed top-48 left-0 w-1/4 h-full px-8">
            <!-- Content for the left div -->
            <!-- Notifications -->
            <div id="target-div" class="original-color flex items-center hover:bg-gray-100 rounded-md h-10 p-2">
              <button id="notif-btn" class="notif" onclick="buttonColor(), toggleNotifications()">
                <span class="h-4 w-4 fa fa-bell"></span>
                <span class="ps-3 text-sm text-greyish_black font-medium">Notifications</span>
              </button>
            </div>

            <!-- Verification Job Post -->
            <div id="target-div-job" class="div-btn flex items-center hover:bg-gray-100 rounded-md h-10 p-2 mt-1">
              <button id="verif-btn" onclick="toggleColorJob(), toggleJobPost()">
                <span class="h-4 w-4 fas fa-check-circle"></span>
                <span class="ps-3 text-sm text-greyish_black font-medium">Verified Job Post</span>
              </button>
            </div>

            <!-- Yearbook -->
            <div id="target-div-yearbook" class="div-btn flex items-center hover:bg-gray-100 rounded-md h-10 p-2 mt-1">
              <button id="yearbook-btn" onclick="toggleYearbook()">
                <span class="h-4 w-4 fa fa-book-open"></span>
                <span class="ps-3 text-sm text-greyish_black font-medium">Yearbook</span>
              </button>
            </div>

            <!-- Make Post Button -->
            <button id="postButton" class="bg-postButton hover:bg-postHoverButton rounded-md w-full lg:w-3/4 py-2 text-white mt-3" onclick="openModal()">Make a post</button>

            <!-- Upcoming Events -->
            <div class="py-4">
              <h3 class="text-lg font-bold text-grayish_black">Upcoming Events:</h3>
              <div class="px-8">
                <div class="py-1">
                  <ul class="list-disc text-sm">
                    <li>Alumni Event</li>
                  </ul>
                  <p class="list-disc pl-2 text-gray-600 text-sm">July 13, 2023</p>
                </div>
                <div class="py-1">
                  <ul class="list-disc text-sm">
                    <li>Enrollment Date</li>
                  </ul>
                  <p class="list-disc pl-2 text-gray-600 text-sm">July 23, 2023</p>
                </div>
                <div class="py-1">
                  <ul class="list-disc text-sm">
                    <li>Start of Classes</li>
                  </ul>
                  <p class="list-disc pl-2 text-gray-600 text-sm">August 7, 2023</p>
                </div>
              </div>
            </div> 
          </div>

          <!-- CENTER DIV -->
          <div class="flex-1 flex justify-center items-center">
            <div id="centerDiv" class="border-l border-r border-grayish px-4 mt-2">
              
              <!-- Content for the center div -->
              
              <!-- Main Feed -->
              <div id="mainFeed" class="mainFeed">
                <!-- Content for the main feed -->

                <!-- Make Post && Profile -->
                <div id="makePostProfile" class="post p-3 input-post-width mx-auto rounded-md center-shadow w-5/6">
                  <div class="flex items-center">
                    <img class="h-12 border-2 border-accentBlue rounded-full inline" src="../images/ye.jpg" alt="">
                    <div class="write pl-2 w-full">
                      <button id="writeBtn" class="bg-gray-200 hover:bg-gray-100 text-grayish font-extralight py-2 px-4 rounded-full flex-grow w-full hover:shadow-md border-2" onclick="openModal()">
                        <span class="flex items-center">
                          <span>Write something...</span>
                        </span>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- POST -->
                <div class="post w-5/6 mt-5 mx-auto post-width h-3/6">
                  <div class="center-shadow p-3 rounded-md">
                    
                    <!-- Header -->
                    <div class="flex justify-between items-center">
                      <!-- User information -->
                      <div class="flex items-center">
                        <!-- Profile -->
                        <img class="h-12 border-2 border-accent rounded-full" src="../images/univ-prof.jpg" alt="">
                        <!-- Name -->
                        <p class="text-start px-3 text-sm font-semibold">Bulacan State University</p>
                      </div>

                      <!-- Elipsis Dropdown for report -->
                      <div class="relative inline-block">
                        <i class="fa-solid fa-ellipsis p-4 cursor-pointer" onclick="toggleDropdown()"></i>
                        <div id="dropdownMenu" class="flex hidden absolute right-4 w-40 bg-white rounded-md shadow-lg post">
                          <button class="flex items-center w-full py-2 px-4 text-grayish text-sm hover:bg-gray-100">
                            <i class="fa-solid fa-flag p-2 text-black"></i>
                            <span class="ml-2 text-black">Report</span>
                          </button>
                        </div>
                      </div>

                    </div>

                    <!-- Body -->
                    <p class="text-sm mt-2 p-2">
                      "Our future growth relies on competitiveness and innovation, skills and productivity... and these in turn rely on the education of our people." ~ Julia Gilla
                      Congratulations to our BulSU Faculty Scholars who have graduated in SY 2021, SY 2022, and SY 2023! May all the great opportunities and blessings come your way. Soar high, BulSU! 😊🙌
                      #BulSUFacultyScholars #Graduates #LearningNeverStops #ProudBulSUan #SoarHighFacultyScholars
                    </p>
                    <img id="postImage" class="my-2 rounded-md cursor-pointer" src="../images/univ-post.jpg" alt="" onclick="openImageModal(this.src)">

                    <!-- Image Modal -->
                    <div id="imageModal" class="modal hidden fixed inset-0 flex items-center justify-center">
                      <div class="modal-contentPost post relative flex flex-col md:flex-row w-full h-full overflow-x-hidden rounded-md bg-white">
                        <!-- Left Side: Image -->
                        <div class="w-full h-full px-10 relative bg-licorice">
                          <button class="text-white text-3xl absolute top-2 left-2 w-10 rounded-full" onclick="closeModalPost()">&times;</button>
                          <img id="modalImage" class="w-full h-full object-contain rounded-md" src="" alt="">
                        </div>

                        <!-- Vertical Line Separator -->
                        <!-- <div class="border-l border-gray-200 px-1 my-4 hidden md:block"></div> -->

                        <!-- Right Side: User Info and Comments -->
                        <div class="my-4 md:mt-0 flex-grow p-4 bg-gray-100">
                          <!-- User Info -->
                          <div class="flex items-center mb-2">
                            <img class="h-10 border-2 border-accent rounded-full inline" src="../images/BSU-logo.png" alt="">
                            <div class="ml-2">
                              <p class="text-grayish_black font-semibold text-sm">Bulacan State University</p>
                              <p class="text-accent text-xs">@BulSU</p>
                            </div>
                          </div>

                          <div class="w-1/2">
                             <p class="text-black text-xs break-words"> #BulSUFacultyScholars #Graduates #LearningNeverStops #ProudBulSUan #SoarHighFacultyScholars</p>
                          </div>

                          <!-- Likes and Comments -->
                          <div class="flex items-center justify-end mb-2">
                            <!-- Likes (Heart) -->
                            <div class="likes px-2">
                              <button id="heartIcon1" class="fa-regular fa-heart h-8 text-accent" onclick="toggleIcon1('heartIcon1')"></button>
                              <span class="text-sm">1,498</span>
                            </div>
                            <!-- Comment -->
                            <div class="comment px-2">
                              <button id="commentIcon1" class="fa-regular fa-comment h-8 text-grayish" onclick="toggleIcon1('commentIcon1')"></button>
                              <span class="text-sm">3,000</span>
                            </div>
                          </div>

                          <hr class="border-black mb-2">

                          <!-- User Comments -->
                          <div class="comment-section h-96 overflow-y-auto">
                            <!-- Comment 1 -->
                            <div class="flex items-start mb-2"> 
                              <div class="p-2">
                                <div class="w-10 h-10">
                                  <img class="h-full w-full border-2 border-accentBlue rounded-full" src="../images/happy.png" alt="">
                                </div>
                              </div>
                              <div class="pl-2 bg-gray-200 rounded-lg p-2 mt-2 w-52">
                                <div class="flex items-center justify-between">
                                  <div>
                                    <div class="flex">
                                      <p class="text-black font-semibold text-sm pr-2">Isagani Lapira jr.</p>
                                      <div class="relative inline-block">
                                        <i class="fa-solid fa-ellipsis text-grayish cursor-pointer" onclick="toggleDropdownPostModal('dropdown1')"></i>
                                        <div id="dropdown1" class="hidden absolute right-0 top-8 mt-2 w-40 bg-white rounded-md shadow-lg">
                                          <button class="flex items-center w-full py-2 px-4 text-grayish text-sm hover:bg-gray-100">
                                            <i class="fa-solid fa-flag p-2 text-black"></i>
                                            <span class="ml-2 text-black">Report</span>
                                          </button>
                                        </div>
                                      </div>
                                    </div>
                                    <p class="text-grayish_black text-sm break-words">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur id dignissim erat. Suspendisse sed malesuada nisi, sit amet laoreet tellus. Sed feugiat aliquet tortor, quis semper diam condimentum vitae. Praesent tincidunt velit sed erat congue euismod. Pellentesque tortor leo, tempus sit amet sem blandit, congue tincidunt sem. Donec scelerisque orci at tortor facilisis, vitae molestie lorem luctus. Proin porta lobortis ipsum id scelerisque. Curabitur sed consequat tortor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Fusce faucibus auctor scelerisque.</p>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Comment 2 -->
                            <div class="flex items-start mb-2"> 
                              <div class="p-2">
                                <div class="w-10 h-10">
                                  <img class="h-full w-full border-2 border-accent rounded-full" src="../images/Mr.Jayson.png" alt="">
                                </div>
                              </div>
                              <div class="pl-2 bg-gray-200 rounded-lg p-2 mt-2 w-48">
                                <div class="flex items-center justify-between">
                                  <div>
                                    <div class="flex">
                                      <p class="text-black font-semibold text-sm pr-2">Jayson Batoon.</p>
                                      <div class="relative inline-block">
                                        <i class="fa-solid fa-ellipsis text-grayish cursor-pointer" onclick="toggleDropdownPostModal('dropdown2')"></i>
                                        <div id="dropdown2" class="hidden absolute right-0 top-8 mt-2 w-40 bg-white rounded-md shadow-lg">
                                          <button class="flex items-center w-full py-2 px-4 text-grayish text-sm hover:bg-gray-100">
                                            <i class="fa-solid fa-flag p-2 text-black"></i>
                                            <span class="ml-2 text-black">Report</span>
                                          </button>
                                        </div>
                                      </div>
                                    </div>
                                    <p class="text-grayish_black text-sm break-words">Congrats! Good job guys.</p>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>

                          <!-- User Comment Input -->
                          <div class="flex p-1">
                            <!-- User Profile Image -->
                            <img id="userProfileImage" class="h-10 border-2 border-accentBlue rounded-full inline" src="../images/ye.jpg" alt="">
                            <!-- Comment Text Area -->
                            <div class="flex flex-col ml-2 border border-gray-300 rounded-md w-full">
                              <textarea id="commentInput" class="w-full h-16 p-2 bg-gray-100 outline-none" placeholder="Write your comment"></textarea>
                              <div class="flex justify-end">
                                <button class="text-accent rounded-md hover:bg-accentDark" onclick="submitComment()">
                                  <i class="fa-regular fa-paper-plane p-2"></i>
                                </button>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>

                    <!-- Comment Modal -->
                    <div id="commentModal" class="modal hidden fixed inset-0 flex items-center justify-center ">
                      <div class="modal-contentPost post relative flex flex-col w-5/12 rounded-md bg-white">

                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-2">
                          <!-- Modal title -->
                          <h2 class="text-grayish_black text-2xl font-bold mx-auto">COMMENT POST</h2>
                          <!-- Close button -->
                          <button class="text-gray-600 text-3xl p-1 focus:outline-none" onclick="closeCommentModal()">&times;</button>
                        </div>

                        <!-- Image -->
                        <div class="p-4 w-10/12 mx-auto">
                          <img id="modalPostImage" class="max-w-full max-h-full rounded-md" src="" alt=""> 
                        </div>                           

                        <!-- Comments -->
                        <div id="modalComments" class="max-h-40 overflow-y-auto px-4">
                          <!-- Comment 1 -->
                          <div class="flex items-start mb-2"> 
                            <div class="p-2">
                              <div class="w-8 h-8">
                                <img class="h-full w-full border-2 border-accentBlue rounded-full" src="../images/happy.png" alt="">
                              </div>
                            </div>
                            <div class="pl-2 bg-gray-200 rounded-lg p-2 mt-2 w-auto">
                              <div class="flex items-center justify-between">
                                <div>
                                  <div class="flex">
                                    <p class="text-black font-semibold text-sm pr-2">Isagani Lapira jr.</p>
                                    <div class="relative inline-block">
                                      <i class="fa-solid fa-ellipsis text-grayish cursor-pointer" onclick="toggleDropdownPostModal('dropdown1-1')"></i>
                                      <div id="dropdown1-1" class="hidden absolute right-0 top-8 mt-2 w-40 bg-white rounded-md shadow-lg">
                                        <button class="flex items-center w-full py-2 px-4 text-grayish text-sm hover:bg-gray-100">
                                          <i class="fa-solid fa-flag p-2 text-black"></i>
                                          <span class="ml-2 text-black">Report</span>
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                                  <p class="text-grayish_black text-sm break-words">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur id dignissim erat. Suspendisse sed malesuada nisi, sit amet laoreet tellus. Sed feugiat aliquet tortor, quis semper diam condimentum vitae. Praesent tincidunt velit sed erat congue euismod. Pellentesque tortor leo, tempus sit amet sem blandit, congue tincidunt sem. Donec scelerisque orci at tortor facilisis, vitae molestie lorem luctus. Proin porta lobortis ipsum id scelerisque. Curabitur sed consequat tortor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Fusce faucibus auctor scelerisque.</p>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Comment 2 -->
                          <div class="flex items-start mb-2"> 
                            <div class="p-2">
                              <div class="w-8 h-8">
                                <img class="h-full w-full border-2 border-accent rounded-full" src="../images/Mr.Jayson.png" alt="">
                              </div>
                            </div>
                            <div class="pl-2 bg-gray-200 rounded-lg p-2 mt-2 w-auto">
                              <div class="flex items-center justify-between">
                                <div>
                                  <div class="flex">
                                    <p class="text-black font-semibold text-sm pr-2">Jayson Batoon.</p>
                                    <div class="relative inline-block">
                                      <i class="fa-solid fa-ellipsis text-grayish cursor-pointer" onclick="toggleDropdownPostModal('dropdown2-2')"></i>
                                      <div id="dropdown2-2" class="hidden absolute right-0 top-8 mt-2 w-40 bg-white rounded-md shadow-lg">
                                        <button class="flex items-center w-full py-2 px-4 text-grayish text-sm hover:bg-gray-100">
                                          <i class="fa-solid fa-flag p-2 text-black"></i>
                                          <span class="ml-2 text-black">Report</span>
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                                  <p class="text-grayish_black text-sm break-words">Congrats! Good job guys.</p>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Add more comments here... -->
                        </div>

                        <!-- User Comment Input -->
                        <div class="flex p-4">
                          <!-- User Profile Image -->
                          <img id="userProfileImage" class="h-10 border-2 border-accentBlue rounded-full inline" src="../images/ye.jpg" alt="">
                          <!-- Comment Text Area -->
                          <div class="flex flex-col ml-2 border border-gray-300 rounded-md w-full">
                            <textarea id="commentInput" class="w-full h-16 p-2 outline-none" placeholder="Write your comment"></textarea>
                            <div class="flex justify-end">
                              <button class="text-accent rounded-md hover:bg-accentDark" onclick="submitComment()">
                                <i class="fa-regular fa-paper-plane p-2"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex py-2 items-center justify-start">
                      <div class="likes px-2">
                        <button id="heartIcon" class="fa-regular fa-heart h-10 text-accent " onclick="toggleIcon('heartIcon')"></button>
                        <span class="text-sm">1,498</span>
                      </div>
                      <div class="comment px-2">
                        <button id="commentIcon" class="fa-regular fa-comment h-10 text-grayish" onclick="openCommentModal()"></button>
                        <span class="text-sm">3,000</span>
                      </div>   
                    </div>

                  </div>
                </div>

                
              </div>

              <!-- Job Post Feed -->
              <div id="jobPostFeed" class="hidden jobPostFeed">
                <p>Job Post Feed</p>
              </div>
              
            </div>
          </div>
 
          <!-- RIGHT DIV -->
          <div class="right-div fixed top-48 right-2 w-1/4 h-full px-8">
            <!-- Content for the right div -->
            <p>Right Div</p>
          </div>

          <!-- MODALS && OTHER OBJECTS THAT HAVE Z-50 -->
          <!-- Notifications Tab -->
          <div id="notification-tab" class="notification-tab hidden fixed top-24 mt-1 right-1 h-full bg-black bg-opacity-50 w-3/4 z-50">
            <div class="notification-content bg-white center-shadow border-2 p-4 h-full md:w-2/6 lg:w-3/6 xl:w-2/5 2xl:w-2/5 overflow-y-auto hide-scrollbar">
              <h1 class="text-greyish_black text-lg font-bold mb-4">Notifications</h1>
              
              <div class="flex space-x-4 mb-4">
                <button class="hover:bg-gray-500 rounded-full text-greyish px-4 py-2 text-sm font-semibold">All</button>
                <button class="hover:bg-gray-500 rounded-full text-greyish px-4 py-2 text-sm font-semibold">Unread</button>
              </div>
              
              <!-- THIS WEEK -->
              <div class="this-week">
                <h3 class="text-greyish text-sm font-semibold mb-2">This Week</h3>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>
              </div>

              <!-- THIS MONTH -->
              <div class="this-month">
                <h3 class="text-greyish text-sm font-semibold mb-2">This Month</h3>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>
              </div>

              <!-- EARLIER -->
              <div class="earlier">
                <h3 class="text-greyish text-sm font-semibold mb-2">Earlier</h3>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>

                <!-- NOTIFICATION POST -->
                <a href="#profile" class="notification-item mb-2 flex items-center hover:bg-gray-300 rounded-md p-2">
                  <div class="flex justify-start items-center inline ">
                    <div class="flex items-center">
                      <img class="h-12 border-2 border-accent rounded-full inline" src="/images/Mr.Jayson.png" alt="">
                    </div>
                  </div>
                  <div class="notification-description p-2">
                    <p class="text-start text-sm font-semibold inline">Jayson Batoon</p>
                    <p class="inline">added a post.</p>
                  </div>
                  <img src="/images/bg1.jpg" alt="Notification Image" class="w-16 h-16 rounded-md ml-auto">
                </a>
              </div>
            </div>

          </div>

          <!-- MODAL FOR POST -->
          <div id="postModal" class="post modal hidden fixed inset-0 flex items-center justify-center">
            <div class="modal-content bg-white mx-auto rounded-md p-4 shadow-lg mt-10">
              <!-- Modal header -->
              <div class="flex items-center justify-between mb-4">
                <!-- Modal title -->
                <h2 class="text-grayish_black text-2xl font-bold mx-auto">CREATE POST</h2>
                <!-- Close button -->
                <button class="text-gray-600 text-3xl p-1 focus:outline-none" onclick="closeModal()">&times;</button>
              </div>
              
              <!-- Modal body -->
              <div class="mb-4">
                <!-- User profile picture and name -->
                <div class="flex items-center mb-2">
                  <img class="h-10 border-2 border-accentBlue rounded-full inline" src="/images/ye.jpg" alt="">
                  <p class="text-grayish_black font-semibold text-sm pl-2">Patrick Joseph Pronuevo</p>
                </div>
                <!-- Post description -->
                <textarea id="postDescription" class="w-full h-32 p-2 border-gray-300 border rounded-md resize-none" placeholder="Write something..."></textarea>
                <!-- Add images button -->
                <button class="hover:bg-gray-100 text-grayish font-extralight py-2 px-4 rounded-md flex items-center mt-3 ml-auto" onclick="openFileExplorer()">
                  <span class="fa-solid fa-image text-green-600"></span>
                  <span class="ml-2"> Add Photo</span>
                </button>
                <input type="file" id="fileInput" class="hidden" accept="image/*" multiple onchange="handleFileSelection(event)">
                <!-- Selected image previews -->
                <div id="selectedImagesContainer" class="mt-4 flex flex-wrap justify-center max-h-64 overflow-y-auto"></div>
              </div>
              <!-- Modal footer -->
              <div class="flex">
                <!-- Post button -->
                <button class="bg-postButton hover:bg-postHoverButton rounded-md text-white py-2 px-4 w-full" onclick="submitPost()">Post</button>
              </div>
            </div>
          </div>

        </div>

        <!-- Container for Yearbook -->
        <div id="yearbookContainer" class="hidden flex pt-48 z-10 w-full h-full">
          <p>Yearbook</p>
        </div>
      </div>
      
      <!-- TAB 2 -->
      <div id="tabs-2">
        <!--IMAGE HEADER-->
        <div id="image-header-con" class="relative top-24 mt-1 z-10 h-4/12">
          <img src="../images/bsu-header5.jpg" alt="Image Header" class="w-full h-auto">
          <div class="absolute inset-0 flex flex-col items-center justify-center">
            <h1 class="text-white text-5xl lg:text-7xl font-bold">Welcome to BulSU Events</h1>
            <p class="text-white text-lg font-bold">See the newest events here</p>
          </div>
        </div>
        
        <div class="separator h-10"></div>
        
        <!-- CONTENT -->
        <div class="grid grid-cols-2 mt-16 h-full w-full">
          
            <!-- Content for the left side div -->
            <div class="h-full pl-16 pt-10">
              <h1 class="text-4xl font-bold text-greyish_black mb-4 pl-4">Schedule of the Year</h1>
              <div class="flex p-4">
                <img src="../images/bsu-header.jpg" alt="Square Image" class="w-5/5 h-4/5 rounded-2xl shadow-lg">
              </div>
            </div>
            
            <!-- Content for the right side div -->
            <div class="flex flex-col pr-16 pt-16">

              <div class="text-right px-8 md:px-16 lg:px-24">
                <a href="#" class="text-accentBlue underline font-semibold p-4">show all</a>
              </div>
              <div class="flex flex-col justify-center items-center mt-4">
              
                <!-- CONTENT EVENT -->
                <div class="w-9/12 p-2 ">
                  <a href="#link-here1" class="flex items-center bg-accent px-2 md:px-8 rounded-lg p-4 md:p-8 h-20">
                    <div class="w-1 bg-white h-16"></div>
                    <div class="p-8">
                      <p class="text-white font-bold text-center text-lg">03</p>
                      <p class="text-white font-bold text-center text-lg">Feb</p>
                    </div>
                    <div class="flex justify-center">
                      <p class="text-white pl-4 md:pl-8 font-bold text-center text-lg tracking-wide">CICT Fun Run for a Cause</p>
                    </div>                  
                  </a>
                </div>
                
                <!-- CONTENT EVENT -->
                <div class="w-9/12 p-2 ">
                  <a href="#link-here1" class="flex items-center bg-accent px-2 md:px-8 rounded-lg p-4 md:p-8 h-20">
                    <div class="w-1 bg-white h-16"></div>
                    <div class="p-8">
                      <p class="text-white font-bold text-center text-lg">16</p>
                      <p class="text-white font-bold text-center text-lg">May</p>
                    </div>
                    <div class="flex justify-center">
                      <p class="text-white pl-4 md:pl-8 font-bold text-center text-lg tracking-wide">Alumni Homecoming Event</p>
                    </div>                  
                  </a>
                </div>

                <!-- CONTENT EVENT -->
                <div class="w-9/12 p-2 ">
                  <a href="#link-here1" class="flex items-center bg-accent px-2 md:px-8 rounded-lg p-4 md:p-8 h-20">
                    <div class="w-1 bg-white h-16"></div>
                    <div class="p-8">
                      <p class="text-white font-bold text-center text-lg">21</p>
                      <p class="text-white font-bold text-center text-lg">Nov</p>
                    </div>
                    <div class="flex justify-center">
                      <p class="text-white pl-4 md:pl-8 font-bold text-center text-lg tracking-wide">CICT Trick o' Treatin</p>
                    </div>                  
                  </a>
                </div>

              </div>
            
            </div>
            
          </div>
      </div>
       
      <!-- TAB 3 -->
      <div id="tabs-3">
        <!-- Job Offer Tabs -->
        <div id="job-offer-tabs" class="flex flex-col md:flex-row pt-48 z-10">

            <!-- LEFT DIV -->
            <div class="fixed left-div w-3/12 md:w-4/12 p-10">

              <!-- Upper Part -->
              <div class="flex flex-col md:flex-row items-center">
                <!-- Dropdown List -->
                <div class="dp-list px-2 pb-2 md:pb-0 relative">
                  <select class="py-2 pr-4 p-3 outline-none border-black center-shadow rounded-md shadow text-sm appearance-none slide-down-select">
                    <option value="option1">All</option>
                    <option value="option2">Tags</option>
                    <option value="option3">Com. Name</option>
                  </select>
                  <i class="absolute top-1 right-5 pr-2 py-2 fas fa-chevron-down text-grayish"></i>
                </div>
                
                <!-- Searchbar -->
                <div class="relative w-full pb-2 md:pb-0 pl-2">
                  <input
                    type="text"
                    placeholder="Search"
                    class="pl-10 pr-4 py-2 w-full text-black text-sm border outline-none border-grayish center-shadow p-3 rounded-md shadow text-sm border outline-none"
                  />
                  <i class="absolute left-3 top-5 transform -translate-y-1/2 fas fa-search text-grayish"></i>
                </div>
              </div>

              <div class="scrollable-container mt-8 rounded-md">
                <ul class="tab-links overflow-y-scroll px-2 pb-4" style="max-height: 440px;">
                  
                  <li><a href="#job-offer1">
                    
                    <!-- Job Offer 1 -->
                    <div class="job-offer center-shadow flex-col md:flex-row rounded-md text-sm selected p-2">
                      <div class="left-div w-full md:w-full p-4 md:p-2 h-auto md:h-2/5 flex flex-col md:flex-row items-start">
                        <img src="../images/BSU-logo.png" alt="Company Image" class="h-12 w-12 rounded-full mb-2 md:mb-0 md:mr-4">
                        <div class="flex-grow">
                          <div>
                            <h2 class="text-md text-accent font-bold">Web Designer</h2>
                            <p class="text-xs">Bulacan State University</p>
                          </div>
                          <div class="flex items-center mt-1">
                            <i class="fa-solid fa-location-dot text-xs pr-1"></i>
                            <p class="text-xs">Philippines</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-xs pr-1">Posted by:</p>
                            <p class="text-xs text-green-500">Admin</p>
                          </div>
                          <div class="flex items-center justify-between">
                            <div class="flex flex-wrap space-x-1">
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> UI Designer
                                </span>
                              </div>
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> Figma
                                </span>
                              </div>
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> Web Designer
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="flex items-end md:items-center">
                          <i class="far fa-bookmark text-accent text-xl bookmark-icon"></i>
                        </div>
                      </div>
                    </div>
                  </a></li>
  
                  <li><a href="#job-offer2" >
                    <!-- Job Offers 2 -->
                    <div class="job-offer center-shadow flex-col md:flex-row rounded-md text-sm mt-4 p-2">
                      <div class="left-div w-full md:w-full p-4 md:p-2 h-auto md:h-2/5 flex flex-col md:flex-row items-start">
                        <img src="../images/job-logo.jpg" alt="Company Image" class="h-12 w-12 rounded-full mb-2 md:mb-0 md:mr-4">
                        <div class="flex-grow">
                          <div>
                            <h2 class="text-md text-accent font-bold">Game Level Design</h2>
                            <p class="text-xs">Sta Monica Studio</p>
                          </div>
                          <div class="flex items-center mt-1">
                            <i class="fa-solid fa-location-dot text-xs pr-1"></i>
                            <p class="text-xs">CA USA</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-xs pr-1">Posted by:</p>
                            <p class="text-xs text-green-500">Admin</p>
                          </div>
                          <div class="flex items-center justify-between">
                            <div class="flex flex-wrap space-x-1">
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> Playtesting
                                </span>
                              </div>
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> Unreal Engine
                                </span>
                              </div>
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> Asset Creating
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="flex items-end md:items-center  mt-4 md:mt-0">
                          <i class="far fa-bookmark text-accent text-xl bookmark-icon "></i>
                        </div>
                      </div>
                    </div>
                  </a></li>
                  
                  <li><a href="#job-offer3" >
                    <!-- Job Offers 3 -->
                    <div class="job-offer center-shadow flex-col md:flex-row rounded-md text-sm mt-4 p-2 ">
                      <div class="left-div w-full md:w-full p-4 md:p-2 h-auto md:h-2/5 flex flex-col md:flex-row items-start">
                        <img src="../images/ms-logo.webp" alt="Company Image" class="h-12 w-12 rounded-full mb-2 md:mb-0 md:mr-4">
                        <div class="flex-grow">
                          <div>
                            <h2 class="text-md text-accent font-bold">Data Scientist </h2>
                            <p class="text-xs">Microsoft</p>
                          </div>
                          <div class="flex items-center mt-1">
                            <i class="fa-solid fa-location-dot text-xs pr-1"></i>
                            <p class="text-xs">D.C Washington, USA</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-xs pr-1">Posted by:</p>
                            <p class="text-xs text-green-500">Admin</p>
                          </div>
                          <div class="flex items-center justify-between">
                            <div class="flex flex-wrap space-x-1">
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> Data Analysis
                                </span>
                              </div>
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> Machine Learning
                                </span>
                              </div>
                              <div class="tags-con">
                                <span class="text-xs py-0.5 rounded">
                                  <span class="font-semibold" style="font-size: 1rem">·</span> Python
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="flex items-end md:items-center  mt-4 md:mt-0">
                          <i class="far fa-bookmark text-accent text-xl bookmark-icon"></i>
                        </div>
                      </div>
                    </div>
                  </a></li>
                  
                </ul>
              </div>
              
 
            </div>

            <div class="w-3/12 md:w-4/12 p-10"></div>
            
            <!-- CENTER DIV -->
            <div class="center-div w-3/5 md:w-9/12 py-10 px-8">
              <div class="flex justify-end p-4 w-full">
                <button class="py-2 bg-postButton hover:bg-postHoverButton text-white rounded w-28">Post</button>
              </div>
              <div class="content-div center-shadow rounded-md text-sm h-auto">

                <div class="tab-content">

                  <!-- Job Offer 1 -->
                  <div id="job-offer1" class="job-offer-content">
                    <!-- Job Offer 1 content goes here -->
                    <!-- JOB DESC. -->
                    <div class="job-des" id="job-description">
                      <!-- Company Name and Image -->
                      <div class="flex py-10 px-16">
                        <div>
                          <img src="../images/BSU-logo.png" alt="Company Logo" class="w-24 h-24 object-cover rounded-full">
                        </div>
                        <div class="pl-4">
                          <h2 class="text-lg font-bold">Web Designer</h2>
                          <p class="text-sm">Bulacan State University</p>
                          <div class="flex items-center pt-2">
                            <i class="fa-solid fa-location-dot text-sm pr-1 text-gray-400"></i>
                            <p class="text-sm text-gray-400">Philippines</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-sm text-gray-400 pr-1">Posted by:</p>
                            <p class="text-sm text-green-500">Admin</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-sm text-gray-400 pr-1">Posted
                              <span class="font-semibold" style="font-size: 1rem">·</span>
                              22/06/23
                            </p>
                          </div>
                  
                          <!-- Buttons -->
                          <div class="flex items-center space-x-4 mt-4">
                            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded">
                              Apply Now
                              <i class="fas fa-check-circle pl-2"></i>
                            </button>
                            <button class="bg-white hover:bg-blue-600 hover:text-white border-2 border-blue-500 text-blue-500 px-4 py-3 rounded">Save</button>
                          </div>
                        </div>
                      </div>

                      <!-- Horizontal Line -->
                      <div class="flex justify-center px-10">
                        <hr class="w-full h-2 border-black">
                      </div>

                      <!-- Jobs, Company, and Salary -->
                      <div class="flex px-16 py-2 justify-between">
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold text-lg p-2">Job</h3>
                          <ul class="list-disc pl-8">
                            <li class="p-1">10 Applicants</li>
                            <li class="p-1">College Graduate</li>
                            <li class="p-1">Full time</li>
                            <!-- Add more jobs as needed -->
                          </ul>
                        </div>
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold p-2 text-lg">Company</h3>
                          <ul class="list-disc pl-4">
                            <li class="p-1">50-200 Employees</li>
                            <li class="p-1">Marketing & Advertisment</li>
                            
                            <!-- Add more companies as needed -->
                          </ul>
                        </div>
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold p-2 text-lg">Salary</h3>
                          <ul class="list-disc pl-4">
                            <li class="p-1">$35, 000 a yr</li>
                            <!-- Add more salaries as needed -->
                          </ul>
                        </div>
                      </div>
                      
                      <!-- Horizontal Line -->
                      <div class="flex justify-center px-10">
                        <hr class="w-full h-2 border-black">
                      </div>
                      
                      <!-- Project Overview -->
                      <div class="px-10 py-6">
                        <h3 class="text-xl font-bold">Project Overview</h3>
                        <p class="indented text-justify">As a web designer, your role will be to create visually appealing and user-friendly websites that effectively communicate the client's brand and message. 
                          You will utilize your design skills and technical expertise to develop website layouts, select appropriate colors and fonts, and implement interactive elements. 
                          Your ability to collaborate with clients and understand their goals will be crucial in delivering successful web design projects.</p>
                      </div>
                      
                      <!-- Skills -->
                      <div class="px-10 py-6">
                        <h3 class="text-xl font-bold">Skills</h3>
                        <div class="flex flex-wrap">
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">User Interface (UI) Design</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Graphic Design</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">HTML/CSS</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Responsive Design</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Content Management Systems (CMS)</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Collaboration</span>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Qualifications -->
                      <div class="px-10 py-6">
                        <h3 class="font-bold">Qualifications:</h3>
                        <ul class="list-disc pl-8">
                          <li>Experience in web design or a related field, demonstrating a portfolio of past projects and design skills</li>
                          <li>Strong understanding of design principles, including color theory, typography, and layout composition.</li>
                          <li>Proficiency in design software such as Adobe Photoshop, Illustrator, or Sketch for creating and editing visual assets.</li>
                          <li>Knowledge of HTML, CSS, and JavaScript to implement design elements and collaborate effectively with developers.</li>
                          <li>Excellent problem-solving and analytical skills to address design challenges and optimize user experience.</li>
                          <li>Ability to work collaboratively in a team environment, adapting to feedback and incorporating changes as necessary.</li>
                        </ul>
                      </div>
                      
                      <!-- Requirements -->
                      <div class="px-10 py-6">
                        <h3 class="font-bold ">Requirements:</h3>
                        <ul class="list-disc pl-8">
                          <li>Bachelor's degree in web design, graphic design, or a related field is preferred.</li>
                          <li>Previous experience in designing successful websites for clients or organizations, 
                            showcasing a diverse range of design styles and approaches.</li>
                          <li>Proficiency in web design tools and frameworks, such as Adobe XD, Figma, or Bootstrap.</li>
                          <li>Strong attention to detail and ability to meet project deadlines while maintaining high-quality standards.</li>
                          <li>Passion for web design and a commitment to staying up-to-date with industry trends, emerging technologies, and design best practices.</li>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <!-- Job Offer 2 -->
                  <div id="job-offer2"  class="job-offer-content">
                    <!-- Job Offer 2 content goes here -->
                    <!-- JOB DESC. -->
                    <div class="job-des" id="job-description">
                      <!-- Company Name and Image -->
                      <div class="flex py-10 px-16">
                        <div>
                          <img src="../images/job-logo.jpg" alt="Company Logo" class="w-24 h-24 object-cover rounded-full">
                        </div>
                        <div class="pl-4">
                          <h2 class="text-lg font-bold">Game Level Design</h2>
                          <p class="text-sm">Sta. Monica Studio</p>
                          <div class="flex items-center pt-2">
                            <i class="fa-solid fa-location-dot text-sm pr-1 text-gray-400"></i>
                            <p class="text-sm text-gray-400">San Fransisco, California USA</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-sm text-gray-400 pr-1">Posted by:</p>
                            <p class="text-sm text-green-500">Admin</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-sm text-gray-400 pr-1">Posted
                              <span class="font-semibold" style="font-size: 1rem">·</span>
                              14/12/22
                            </p>
                          </div>
                  
                          <!-- Buttons -->
                          <div class="flex items-center space-x-4 mt-4">
                            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded">
                              Apply Now
                              <i class="fas fa-check-circle pl-2"></i>
                            </button>
                            <button class="bg-white hover:bg-blue-600 hover:text-white border-2 border-blue-500 text-blue-500 px-4 py-3 rounded">Save</button>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Horizontal Line -->
                      <div class="flex justify-center px-10">
                        <hr class="w-full h-2 border-black">
                      </div>

                      <!-- Jobs, Company, and Salary -->
                      <div class="flex px-16 py-2 justify-between">
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold text-lg p-2">Job</h3>
                          <ul class="list-disc pl-8">
                            <li class="p-1">30 Applicants</li>
                            <li class="p-1">Knowledgeable in Game Development</li>
                            <li class="p-1">Full time</li>
                            <!-- Add more jobs as needed -->
                          </ul>
                        </div>
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold p-2 text-lg">Company</h3>
                          <ul class="list-disc pl-4">
                            <li class="p-1">200-1000 Employees</li>
                            <li class="p-1">Assets Making and Design</li>
                            
                            <!-- Add more companies as needed -->
                          </ul>
                        </div>
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold p-2 text-lg">Salary</h3>
                          <ul class="list-disc pl-4">
                            <li class="p-1">$80,000 a yr</li>
                            <!-- Add more salaries as needed -->
                          </ul>
                        </div>
                      </div>
                      
                      <!-- Horizontal Line -->
                      <div class="flex justify-center px-10">
                        <hr class="w-full h-2 border-black">
                      </div>

                      <!-- Project Overview -->
                      <div class="px-10 py-6">
                        <h3 class="text-xl font-bold">Project Overview</h3>
                        <p class="indented text-justify">As a game level designer, you will have the exciting opportunity to craft captivating and memorable levels that will challenge and entertain players. 
                          Your creative vision and attention to detail will drive the design and construction of game worlds that seamlessly blend artistry, 
                          gameplay mechanics, and storytelling.</p>
                      </div>
                      
                      <!-- Skills -->
                      <div class="px-10 py-6">
                        <h3 class="text-xl font-bold">Skills</h3>
                        <div class="flex flex-wrap">
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Designing Level Layouts</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Creating Environmental Assets</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Implementing Gameplay Mechanics</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Iterating and Playtesting</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Collaborating with the Team</span>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Qualifications -->
                      <div class="px-10 py-6">
                        <h3 class="font-bold">Qualifications:</h3>
                        <ul class="list-disc pl-8">
                          <li>Experience in game level design or related field</li>
                          <li>Strong understanding of game design principles and mechanics</li>
                          <li>Proficiency in level design tools and software</li>
                          <li>Excellent problem-solving and analytical skills</li>
                          <li>Ability to work collaboratively in a team environment</li>
                        </ul>
                      </div>
                      
                      <!-- Requirements -->
                      <div class="px-10 py-6">
                        <h3 class="font-bold ">Requirements:</h3>
                        <ul class="list-disc pl-8">
                          <li>Bachelor's degree in game design, computer science, or a related field</li>
                          <li>Previous experience in designing levels for successful games</li>
                          <li>Proficiency in scripting or programming languages</li>
                          <li>Strong attention to detail and ability to meet deadlines</li>
                          <li>Passion for gaming and staying up-to-date with industry trends</li>
                        </ul>
                      </div>
                      </div>
                  </div>

                  <!-- Job Offer 3 -->
                  <div id="job-offer3" class="job-offer-content">
                    <!-- Job Offer 2 content goes here -->
                    <!-- JOB DESC. -->
                    <div class="job-des" id="job-description">
                      <!-- Company Name and Image -->
                      <div class="flex py-10 px-16">
                        <div>
                          <img src="../images/ms-logo.webp" alt="Company Logo" class="w-24 h-24 object-cover rounded-full">
                        </div>
                        <div class="pl-4">
                          <h2 class="text-lg font-bold">Data Scientist</h2>
                          <p class="text-sm">Microsoft</p>
                          <div class="flex items-center pt-2">
                            <i class="fa-solid fa-location-dot text-sm pr-1 text-gray-400"></i>
                            <p class="text-sm text-gray-400">D.C, Washington USA</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-sm text-gray-400 pr-1">Posted by:</p>
                            <p class="text-sm text-green-500">Admin</p>
                          </div>
                          <div class="flex items-center">
                            <p class="text-sm text-gray-400 pr-1">Posted
                              <span class="font-semibold" style="font-size: 1rem">·</span>
                              07/13/22
                            </p>
                          </div>
                  
                          <!-- Buttons -->
                          <div class="flex items-center space-x-4 mt-4">
                            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded">
                              Apply Now
                              <i class="fas fa-check-circle pl-2"></i>
                            </button>
                            <button class="bg-white hover:bg-blue-600 hover:text-white border-2 border-blue-500 text-blue-500 px-4 py-3 rounded">Save</button>
                          </div>
                        </div>
                      </div>
    
                      <!-- Horizontal Line -->
                      <div class="flex justify-center px-10">
                        <hr class="w-full h-2 border-black">
                      </div>

                      <!-- Jobs, Company, and Salary -->
                      <div class="flex px-16 py-2 justify-between">
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold text-lg p-2">Job</h3>
                          <ul class="list-disc pl-8">
                            <li class="p-1">15 Applicants</li>
                            <li class="p-1">College Graduate</li>
                            <li class="p-1">Full time</li>
                            <!-- Add more jobs as needed -->
                          </ul>
                        </div>
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold p-2 text-lg">Company</h3>
                          <ul class="list-disc pl-4">
                            <li class="p-1">80-400 Employees</li>
                            <li class="p-1">Data Analytics</li>
                            
                            <!-- Add more companies as needed -->
                          </ul>
                        </div>
                        <div class="w-1/3 p-2">
                          <h3 class="font-bold p-2 text-lg">Salary</h3>
                          <ul class="list-disc pl-4">
                            <li class="p-1">$150, 000 a yr</li>
                            <!-- Add more salaries as needed -->
                          </ul>
                        </div>
                      </div>
                      
                      <!-- Horizontal Line -->
                      <div class="flex justify-center px-10">
                        <hr class="w-full h-2 border-black">
                      </div>

                      <!-- Project Overview -->
                      <div class="px-10 py-6">
                        <h3 class="text-xl font-bold">Project Overview</h3>
                        <p class="indented text-justify">As a data scientist, you will play a crucial role in extracting insights and valuable information from large datasets. 
                        Your expertise in data analysis, statistical modeling, and machine learning will contribute to solving complex business problems and driving data-driven decision-making.</p>
                      </div>

                      <!-- Skills -->
                      <div class="px-10 py-6">
                        <h3 class="text-xl font-bold">Skills</h3>
                        <div class="flex flex-wrap">
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Data Analysis</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Statistical Modeling</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Machine Learning</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Python</span>
                          </div>
                          <div class="tags-con">
                            <span class="text-xs bg-gray-200 px-2 py-1 rounded">Data Visualization</span>
                          </div>
                        </div>
                      </div>

                      <!-- Qualifications -->
                      <div class="px-10 py-6">
                        <h3 class="font-bold">Qualifications:</h3>
                        <ul class="list-disc pl-8">
                          <li>Master's degree in data science, computer science, or a related field</li>
                          <li>Strong understanding of statistics and probability</li>
                          <li>Proficiency in programming languages such as Python or R</li>
                          <li>Experience with data analysis and visualization tools</li>
                          <li>Knowledge of machine learning algorithms and techniques</li>
                        </ul>
                      </div>

                      <!-- Requirements -->
                      <div class="px-10 py-6">
                        <h3 class="font-bold">Requirements:</h3>
                        <ul class="list-disc pl-8">
                          <li>Previous experience as a data scientist or related role</li>
                          <li>Ability to work with large datasets and extract insights</li>
                          <li>Strong problem-solving and analytical skills</li>
                          <li>Excellent communication and presentation skills</li>
                          <li>Ability to work collaboratively in a team environment</li>
                        </ul>
                      </div>
                    </div>
                  </div>

                </div>

                

              </div>
              
            </div>

        </div>
      </div>

    </div>
  </div>
      

  <script src="../student-alumni/js/hompage.js"></script>
  <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
 
</body>

</html>