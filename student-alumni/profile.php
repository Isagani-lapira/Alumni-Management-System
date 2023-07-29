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
    $query = "SELECT 'student' AS user_details, student.personID
            FROM student
            WHERE student.username = '$username'
            UNION
            SELECT 'alumni' AS user_details, alumni.personID
            FROM alumni
            WHERE alumni.username = '$username'";

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
        $coverPhoto = $personData['coverPhoto'];
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../css/main.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="../style/profile.css" />
    <!-- <link rel="stylesheet" type="text/css" href="/style/mstyle.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        /* Transparent navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(3px);
            z-index: 20;
        }

        .transparent-search-bar {
            background-color: transparent;
        }

        /* Set the color of the placeholder text */
        .transparent-search-bar::placeholder {
            color: #6A6A6A;
            /* Change this to your desired color */
        }

        .content {
            height: 550px;
        }
    </style>
</head>

<body class="bg-gray-100 scrollable-container">

    <!-- Navbar -->
    <nav class="navbar grid grid-cols-3 gap-4 p-3 bg-white text-black shadow-lg">
        <!-- Logo -->
        <a href="homepage.php" class="col-span-1 flex items-center">
            <img src="../images/BSU-logo.png" alt="Logo" class="w-10 h-10" />
            <span class="ml-2 text-xl font-bold text-gray-800">BulSU Connect</span>
        </a>

        <!-- Search Bar -->
        <div class="col-span-1 flex items-center justify-center">
            <div class="relative w-full">
                <input type="text" placeholder="Search" class="pl-10 pr-4 py-3 w-full text-accent text-sm border-2 outline-none border-accent center-shadow p-3 rounded-md shadow text-sm border outline-none transparent-search-bar" />
                <i class="absolute left-3 top-1/2 transform -translate-y-1/2 fas fa-search text-accent text-base"></i>
            </div>
        </div>

        <!-- Menu Items -->
        <ul class="col-span-1 flex items-center justify-end space-x-8 px-4">
            <li>
                <a href="../student-alumni/homepage.php" class="text-gray-800 hover:text-gray-600 font-bold">Home</a>
            </li>
            <li>
                <a href="#" class="text-gray-800 hover:text-gray-600 font-bold">Jobs</a>
            </li>
            <li class="relative">
                <a href="#" class="text-gray-800 hover:text-gray-600 font-bold" id="notificationsLink">Notifications</a>
                <div id="notificationsDropdown" class="absolute right-0 mt-2 w-64 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                    <div class="p-4">
                        <h3 class="text-lg font-bold">Notifications</h3>
                        <div class="mt-4">
                            <button id="allNotificationsBtn" class="mr-2 text-gray-600 hover:text-gray-800">All</button>
                            <button id="unreadNotificationsBtn" class="text-gray-600 hover:text-gray-800">Unread</button>
                        </div>
                        <div class="mt-4">
                            <!-- add notif -->
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <a href="#" class="text-blue-600 hover:text-blue-800">
                    <img src="../images/ye.jpg" alt="Profile Picture" class="w-12 h-12 rounded-full border-2 border-accentBlue" />
                </a>
            </li>
        </ul>
    </nav>

    <!-- Cover Photo -->
    <div class="relative">
        <?php
        if ($coverPhoto == "") {
            echo '<img src="../images/bganim.jpg" alt="Profile Icon" class="w-full h-96 object-cover" />';
        } else {
            $srcFormat = 'data:image/jpeg;base64,' . $coverPhoto;
            echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-full h-96 object-cover" />';
        }
        ?>
        <!-- <img src="../images/bganim.jpg" alt="Cover Photo" class="w-full h-96 object-cover"> -->
        <!-- Profile Photo (Intersecting with the Cover Photo) -->
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rounded-full border-4 border-accentBlue overflow-hidden w-56 h-56">
            <?php
            if ($profilepicture == "") {
                echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-full h-full object-cover" />';
            } else {
                $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-full h-full object-cover" />';
            }
            ?>
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex justify-end mt-4 space-x-4 px-8 pt-8">
        <button class="bg-accent hover:bg-darkAccent text-white px-4 py-2 rounded-lg"><a href="../student-alumni/resume.html">View Resum</a>e</button>
        <button id="modal-openBtn" class="bg-gray-100 hover:bg-gray-200 text-accent border border-accent px-4 py-2 rounded-lg">Edit Profile</button>
    </div>

    <!-- Profile Info -->
    <div class="mt-8 mx-auto max-w-md text-center">
        <!-- Green circle and "Student" label beside each other -->
        <div class="flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" class="text-green-500 mr-2">
                <path fill="currentColor" d="M12 22q-2.075 0-3.9-.788t-3.175-2.137q-1.35-1.35-2.137-3.175T2 12q0-2.075.788-3.9t2.137-3.175q1.35-1.35 3.175-2.137T12 2q2.075 0 3.9.788t3.175 2.137q1.35 1.35 2.138 3.175T22 12q0 2.075-.788 3.9t-2.137 3.175q-1.35 1.35-3.175 2.138T12 22Z" />
            </svg>
            <p class="text-sm text-green-500 font-semibold">Student</p>
        </div>
        <!-- Student's name and user handle -->
        <?php
        echo '<p class="mt-1 text-3xl font-bold text-blue-900">' . $fullname . '</p>';
        echo '<p class="mt-1 text-gray-600">' . $username . '</p>';
        ?>

    </div>

    <!-- Content -->
    <div class="content flex mt-8">
        <!-- About Section -->
        <div class="px-6 max-w-md text-sm">
            <div class="text-left">
                <h2 class="text-lg text-accent font-bold border-b border-gray-300 py-1">About</h2>
                <div class="mt-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class=" text-gray-700">
                            <path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v1c0 .55.45 1 1 1h14c.55 0 1-.45 1-1v-1c0-2.66-5.33-4-8-4z" />
                        </svg>
                        <?php
                        echo '<p class="py-2 px-2 text-gray-700">' . $gender . '</p>';
                        ?>

                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class=" text-gray-700">
                            <path fill="currentColor" d="M11.5.5c.5.25 1.5 1.9 1.5 3S12.33 5 11.5 5S10 4.85 10 3.75S11 2 11.5.5m7 8.5C21 9 23 11 23 13.5c0 1.56-.79 2.93-2 3.74V23H3v-5.76c-1.21-.81-2-2.18-2-3.74C1 11 3 9 5.5 9H10V6h3v3h5.5M12 16a2.5 2.5 0 0 0 2.5-2.5H16a2.5 2.5 0 0 0 2.5 2.5a2.5 2.5 0 0 0 2.5-2.5a2.5 2.5 0 0 0-2.5-2.5h-13A2.5 2.5 0 0 0 3 13.5A2.5 2.5 0 0 0 5.5 16A2.5 2.5 0 0 0 8 13.5h1.5A2.5 2.5 0 0 0 12 16Z" />
                        </svg>
                        <?php
                        echo ' <p class="py-2 px-2 text-gray-700">' . $bday . ' </p>';
                        ?>

                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class=" text-gray-700">
                            <path fill="currentColor" d="M12 11.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Z" />
                        </svg>
                        <?php
                        echo '<p class="py-2 px-2 text-gray-700">' . $address . '</p>';
                        ?>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class=" text-gray-700">
                            <path fill="currentColor" d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5l-8-5V6l8 5l8-5v2z" />
                        </svg>
                        <?php
                        echo '<p class="py-2 px-2 text-gray-700">' . $personal_email . '</p>';
                        ?>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" class=" text-gray-700">
                            <path fill="currentColor" fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42a18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                        </svg>
                        <?php
                        echo '<p class="py-2 px-2 text-gray-700">' . $contactNo . '</p>';
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center div for user's posts -->
        <div class="px-6 w-1/2 h-full">
            <h2 class="text-lg text-accent font-bold">Posts</h2>
            <hr class="mt-2 border-gray-300">
            <div id="feedContainer" class="post w-5/6 mx-auto post-width h-full no-scrollbar">
                <!-- Make Post && Profile -->
                <div id="makePostProfile" class="post p-3 input-post-width mx-auto rounded-md center-shadow w-full my-2">
                    <div class="flex items-center">
                        <!-- set profile image -->
                        <?php
                        if ($profilepicture == "") {
                            echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 rounded-full profile-icon" />';
                        } else {
                            $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                            echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="rounded-full w-10 h-10 profile-icon" />';
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

            </div>
        </div>

    </div>

    <!-- MODALS -->

    <!-- EDIT PROFILE -->
    <div id="profileModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded shadow-lg overflow-x-auto hide-scrollbar" style="width: 500px; height: 600px;">
            <!-- Profile Picture -->
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">Profile Picture</h2>
                <button id="editProfilePicBtn" class="focus:outline-none">
                    <i class="fas fa-edit text-gray-600 hover:text-gray-700"></i>
                </button>
            </div>

            <!-- Center the image -->
            <div class="flex items-center justify-center">
                <?php
                if ($profilepicture == "") {
                    echo '<img id="profilePic" src="../assets/icons/person.png" alt="Profile Icon" class="w-full h-full object-cover" alt="Profile Picture"/>';
                } else {
                    $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                    echo '<img id="profilePic" src="' . $srcFormat . '" alt="Profile Icon" class="w-32 h-32 rounded-full mt-2 mb-4 
                    border-2 border-accentBlue" alt="Profile Picture" />';
                }
                ?>

            </div>

            <!-- File input for Profile Picture -->
            <input type="file" id="profilePicInput" accept="image/*" class="hidden">

            <!-- Cover Photo -->
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">Cover Photo</h2>
                <button id="editCoverPhotoBtn" class="focus:outline-none">
                    <i class="fas fa-edit text-gray-600 hover:text-gray-700"></i>
                </button>
            </div>

            <!-- Center the cover photo -->
            <div class="flex items-center justify-center">
                <img id="coverPhoto" src="../images/bganim.jpg" alt="Cover Photo" class="w-full h-40 object-cover mt-2 mb-4 rounded-lg">
            </div>

            <!-- File input for Cover Photo -->
            <input type="file" id="coverPhotoInput" accept="image/*" class="hidden">


            <!-- Customize Information -->
            <div class="mt-6">
                <h2 class="text-lg font-bold mb-2">Customize Your Information</h2>

                <!-- Location -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="pr-1"><i class="fa-solid fa-location-dot text-gray-500"></i></div>
                        <h3 class="text-sm text-gray-700 mr-2">Lives in</h3>
                        <?php
                        echo '<p id="locationText" class="text-sm mr-4 font-bold">' . $address . '</p>';
                        ?>
                        <!-- Editable input field (initially hidden) -->
                        <input type="text" id="locationInput" class="hidden text-sm text-gray-600 font-bold border-b border-gray-500 outline-none">
                    </div>
                    <button id="editLocationBtn" class="focus:outline-none">
                        <i class="fa-solid fa-pen text-gray-600 hover:text-gray-700 text-sm p-2"></i>
                    </button>
                </div>

                <!-- Email Address -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="pr-1"><i class="fa-regular fa-envelope text-gray-500"></i></div>
                        <?php
                        echo '<p id="emailText" class="text-sm mr-4 font-bold">' . $personal_email . '</p>';
                        ?>
                        <!-- Editable input field (initially hidden) -->
                        <input type="text" id="emailInput" class="hidden text-sm text-gray-600 font-bold border-b border-gray-500 outline-none">
                    </div>
                    <button id="editEmailBtn" class="focus:outline-none">
                        <i class="fa-solid fa-pen text-gray-600 hover:text-gray-700 text-sm p-2"></i>
                    </button>
                </div>

                <!-- Contact Number -->
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="pr-1"><i class="fa-solid fa-phone text-gray-500"></i></div>
                        <?php
                        echo '<p id="contactText" class="text-sm mr-4 font-bold">' . $contactNo . '</p>';
                        ?>
                        <!-- Editable input field (initially hidden) -->
                        <input type="text" id="contactInput" class="hidden text-sm text-gray-600 font-bold border-b border-gray-500 outline-none">
                    </div>
                    <button id="editContactBtn" class="focus:outline-none">
                        <i class="fa-solid fa-pen text-gray-600 hover:text-gray-700 text-sm p-2"></i>
                    </button>
                </div>
            </div>

            <!-- Social Media -->
            <h2 class="text-lg font-bold">Social Media Accounts</h2>
            <!-- Add form elements for Facebook, Instagram, and Twitter here -->

            <!-- Facebook -->
            <div class="flex mt-1 items-center">
                <i class="fa-brands fa-facebook text-gray-500"></i>
                <div class="ml-1 flex w-4/6 rounded-md border border-gray-500">
                    <div class="py-1 px-1 rounded-l-md text-sm bg-gray-300">facebook.com/</div>
                    <div id="facebookText" class="editText pl-1 py-1 text-sm whitespace-nowrap overflow-hidden"></div>
                    <!-- Editable input field (initially hidden) -->
                    <input type="text" id="facebookInput" class="hidden text-sm text-gray-600 font-bold outline-none ml-auto">
                </div>
                <button id="editFacebookBtn" class="focus:outline-none ml-auto">
                    <i class="fa-solid fa-pen text-gray-600 hover:text-gray-700 text-sm p-2"></i>
                </button>
            </div>

            <!-- Instagram -->
            <div class="flex mt-2 items-center">
                <i class="fa-brands fa-instagram text-gray-500"></i>
                <div class="ml-1 flex w-4/6 rounded-md border border-gray-500">
                    <div class="py-1 px-1 rounded-l-md text-sm bg-gray-300">instagram.com/</div>
                    <div id="instagramText" class="editText pl-1 py-1 text-sm whitespace-nowrap overflow-hidden"></div>
                    <!-- Editable input field (initially hidden) -->
                    <input type="text" id="instagramInput" class="hidden text-sm text-gray-600 font-bold outline-none ml-auto">
                </div>
                <button id="editInstagramBtn" class="focus:outline-none ml-auto">
                    <i class="fa-solid fa-pen text-gray-600 hover:text-gray-700 text-sm p-2"></i>
                </button>
            </div>

            <!-- Twitter -->
            <div class="flex mt-2 items-center">
                <i class="fa-brands fa-twitter text-gray-500"></i>
                <div class="ml-1 flex w-4/6 rounded-md border border-gray-500">
                    <div class="py-1 px-1 rounded-l-md text-sm bg-gray-300">twitter.com/</div>
                    <div id="twitterText" class="editText pl-1 py-1 text-sm whitespace-nowrap overflow-hidden"></div>
                    <!-- Editable input field (initially hidden) -->
                    <input type="text" id="twitterInput" class="hidden text-sm text-gray-600 font-bold outline-none ml-auto">
                </div>
                <button id="editTwitterBtn" class="focus:outline-none ml-auto">
                    <i class="fa-solid fa-pen text-gray-600 hover:text-gray-700 text-sm p-2"></i>
                </button>
            </div>

            <!-- Edit Resume -->
            <div class="flex justify-center mt-2">
                <div class="text-sm text-gray-600">Want to edit your resume?</div>
                <a href="/student-alumni/EditResume.html" class="text-sm text-blue-600 ml-1 hover:text-blue-800 hover:underline">Click here.</a>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end mt-6">
                <button id="modal-cancelBtn" class="mr-2 text-gray-600 hover:text-gray-800 hover:underline">Cancel</button>
                <button id="modal-saveBtn" class="text-accent hover:text-accent-dark">Save</button>
            </div>
        </div>
    </div>


    <script src="../student-alumni/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</body>

</html>