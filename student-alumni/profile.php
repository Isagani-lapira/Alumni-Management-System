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
        $bday = dateInText($personData['bday']);
        $gender = ucfirst($personData['gender']);
        $contactNo = $personData['contactNo'];
        $personal_email = $personData['personal_email'];
        $bulsu_email = $personData['bulsu_email'];
        $profilepicture = $personData['profilepicture'];
        $coverPhoto = $personData['coverPhoto'];
        $facebookUN = $personData['facebookUN'];
        $instagramUN = $personData['instagramUN'];
        $twitterUN = $personData['twitterUN'];
        $linkedInUN = $personData['linkedInUN'];
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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../css/main.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="../style/profile.css" />
    <link rel="stylesheet" href="../style/style.css" />
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
    <div class="relative bgCover">
        <?php
        if ($coverPhoto == "") {
            // Use the default image as the background of the container
            echo '<div class="h-full bg-black rounded-md" style="background-image: url(../images/bganim.jpg); background-size: cover; background-position: center;"></div>';
        } else {
            // Use the uploaded image as the background of the container
            $srcFormat = 'data:image/jpeg;base64,' . $coverPhoto;
            echo '<div class="h-full bg-no-repeat rounded-md bg-black bg-opacity-50" style="background-image: url(' . $srcFormat . '); background-size: cover; background-position: center top;">
            <img id="profilePhoto" src="' . $srcFormat . '" alt="Profile Icon" class="w-full h-full object-contain" />
            </div>';
        }
        ?>
        <!-- Profile Photo (Intersecting with the Cover Photo) -->
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rounded-full border-4 border-accentBlue overflow-hidden w-56 h-56">
            <?php
            if ($profilepicture == "") {
                echo '<img id="profilePhoto" src="../assets/icons/person.png" alt="Profile Icon" class="w-full h-full object-cover" />';
            } else {
                $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                echo '<img id="profilePhoto" src="' . $srcFormat . '" alt="Profile Icon" class="w-full h-full object-cover" />';
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
        <div class="px-6 max-w-md text-sm w-3/12">
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
            <button id="userPost" class="text-white bg-accent px-3 rounded-lg py-1 font-bold">Posts</button>
            <button id="archievedBtn" class="text-gray-400 hover:bg-gray-300 px-3 rounded-lg py-1 ">Archived</button>
            <hr class=" mt-2 border-gray-300">
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

        <div class="px-6 w-3/12 h-full flex flex-col gap-2">
            <h2 class="text-lg text-accent font-bold">Social Media</h2>
            <hr class="mt-2 border-gray-300">

            <!-- facebook -->
            <div class="flex gap-2 items-center mt-4">
                <iconify-icon icon="formkit:facebook" style="color: #474645;" width="20" height="20"></iconify-icon>
                <?php
                echo '<span >' . $facebookUN . '<span>';
                ?>
            </div>

            <!-- instagram -->
            <div class="flex gap-2 items-center">
                <iconify-icon icon="ri:instagram-fill" style="color: #474645;" width="20" height="20"></iconify-icon>
                <?php
                echo '<span >' . $instagramUN . '<span>';
                ?>
            </div>

            <!-- twitter -->
            <div class="flex gap-2 items-center">
                <iconify-icon icon="ri:twitter-fill" style="color: #474645;" width="20" height="20"></iconify-icon>
                <?php
                echo '<span >' . $twitterUN . '<span>';
                ?>
            </div>

            <!-- linkedIn -->
            <div class="flex gap-2 items-center">
                <iconify-icon icon="mdi:linkedin" style="color: #474645;" width="20" height="20"></iconify-icon>
                <?php
                echo '<span >' . $linkedInUN . '<span>';
                ?>
            </div>

        </div>
    </div>

    <!-- MODALS -->

    <!-- EDIT PROFILE -->
    <div id="profileModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="formUpdate bg-white rounded-md w-2/5 h-4/5 p-5 flex flex-col gap-3 overflow-y-auto">
            <!-- profile picture -->
            <div class="flex justify-between text-greyish_black items-center">
                <p class="text-lg font-bold">Profile Picture</p>
                <label id="profileLbl" for="profilePic">
                    <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
                </label>
                <input type="file" name="profilePic" id="profilePic" class="hidden" accept="image/*">
            </div>
            <!-- Profile Photo (Intersecting with the Cover Photo) -->
            <div class="h-48 w-full flex justify-center">
                <?php
                if ($profilepicture == "") {
                    echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-48 h-48 rounded-full" id="profileImg" />';
                } else {
                    $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                    echo '<img src="' . $srcFormat . '" alt="Profile Icon" class=" w-48 h-48 rounded-full " id="profileImg"/>';
                }
                ?>
            </div>

            <div id="profileBtn" class="flex justify-end gap-2 hidden">
                <button class="text-postButton hover:bg-gray-400 px-4 rounded-md py-2">Cancel</button>
                <button class=" bg-postButton hover:bg-postHoverButton px-4 rounded-md text-white py-2" id="saveProfile">Save</button>
            </div>

            <!-- cover photo -->
            <div class="flex justify-between text-greyish_black items-center">
                <p class="text-lg font-bold">Cover Picture</p>
                <label id="coverLbl" for="coverPic">
                    <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
                </label>
                <input type="file" name="coverPic" id="coverPic" class="hidden" accept="image/*">
            </div>

            <div class=" h-48 w-full flex justify-center">
                <?php
                if ($coverPhoto == "") {
                    echo '<img src="../images/bganim.jpg" alt="Cover Icon" class="w-3/4 h-48 bg-black rounded-md object-cover" id="coverImg" />';
                } else {
                    $srcFormat = 'data:image/jpeg;base64,' . $coverPhoto;
                    echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-3/4  h-48 rounded-md bg-black object-cover" id="coverImg"/>';
                }
                ?>
            </div>

            <div id="coverBtn" class="flex justify-end gap-2 hidden">
                <button class="text-postButton hover:bg-gray-400 px-4 rounded-md py-2">Cancel</button>
                <button class=" bg-postButton hover:bg-postHoverButton px-4 rounded-md text-white py-2" id="saveCover">Save</button>
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
                    <button class="px-2 py-1">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1" id="saveLocation">Save</button>
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
                    <button class="px-2 py-1">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1" id="saveEmail">Save</button>
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
                    <button class="px-2 py-1">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1" id="saveContact">Save</button>
                </div>

            </div>

            <p class="text-lg font-bold">Social Media Username</p>

            <!-- facebook -->
            <div class="flex justify-between text-greyish_black items-center">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-thin flex items-center gap-2">
                        <iconify-icon icon="ic:baseline-facebook" width="20" height="20"></iconify-icon>
                        Facebook Username:
                    </span>

                    <?php
                    echo '<input class="font-bold text-sm flex items-center" disabled type="text" id="editFacebook" value="' . $facebookUN . '" placeholder = "' . $facebookUN . '" >';
                    ?>

                </div>
                <label id="editFBLbl" for="editFacebook">
                    <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
                </label>
                <div id="fbBtn" class="text-sm hidden">
                    <button class="px-2 py-1">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1" id="saveFB">Save</button>
                </div>

            </div>

            <!-- instagram -->
            <div class="flex justify-between text-greyish_black items-center">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-thin flex items-center gap-2">
                        <iconify-icon icon="uim:instagram" width="20" height="20"></iconify-icon>
                        Instagram Username:
                    </span>

                    <?php
                    echo '<input class="font-bold text-sm flex items-center" disabled type="text" id="editInstagram" value="' . $instagramUN . '" placeholder = "' . $instagramUN . '" >';
                    ?>

                </div>
                <label id="editIGLbl" for="editInstagram">
                    <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
                </label>
                <div id="igBtn" class="text-sm hidden">
                    <button class="px-2 py-1">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1" id="saveIG">Save</button>
                </div>

            </div>


            <!-- Twitter -->
            <div class="flex justify-between text-greyish_black items-center">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-thin flex items-center gap-2">
                        <iconify-icon icon="bxl:twitter" width="20" height="20"></iconify-icon>
                        Twitter Username:
                    </span>

                    <?php
                    echo '<input class="font-bold text-sm flex items-center" disabled type="text" id="editTwitter" value="' . $twitterUN . '" placeholder = "' . $twitterUN . '" >';
                    ?>

                </div>
                <label id="editTweetLbl" for="editTwitter">
                    <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
                </label>
                <div id="tweetBtn" class="text-sm hidden">
                    <button class="px-2 py-1">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1" id="saveTweet">Save</button>
                </div>

            </div>

            <!-- linkedIn -->
            <div class="flex justify-between text-greyish_black items-center">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-thin flex items-center gap-2">
                        <iconify-icon icon="raphael:linkedin" width="20" height="20"></iconify-icon>
                        LinkedIn Username:
                    </span>

                    <?php
                    echo '<input class="font-bold text-sm flex items-center" disabled type="text" id="editLinked" value="' . $linkedInUN . '" placeholder = "' . $linkedInUN . '" >';
                    ?>

                </div>
                <label id="editLinkedLbl" for="editLinked">
                    <iconify-icon class="cursor-pointer" icon="fluent:edit-24-filled" style="color: #474645;" width="20" height="20"></iconify-icon>
                </label>
                <div id="linkedBtn" class="text-sm hidden">
                    <button class="px-2 py-1">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1" id="saveLinkedIn">Save</button>
                </div>

            </div>
        </div>
    </div>

    <!-- viewing of post -->
    <div id="viewingPost" class="post modal fixed hidden inset-0 z-50 flex items-center justify-center p-3">
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
                    <?php
                    if ($profilepicture == "") {
                        echo '<img id="profilePic" src="../assets/icons/person.png" alt="Profile Icon" class="rounded-full border-2 border-accent h-10 w-10" />';
                    } else {
                        $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                        echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="rounded-full border-2 border-accent h-10 w-10" />';
                    }
                    ?>
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
                <div id="commentContainer" class=" h-3/4 p-2 overflow-auto">

                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal hidden fixed inset-0 z-50 h-full w-full flex items-center justify-center
            text-grayish  top-0 left-0">
        <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3">
            <div class="modal-header py-5 border-b border-gray-400">
                <h1 class=" text-greyish_black text-2xl text-center font-bold">Create New Post</h1>
            </div>
            <div class="flex items-center mb-2 my-2">
                <!-- set profile image -->
                <?php
                if ($profilepicture == "") {
                    echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 profile-icon rounded-full" />';
                } else {
                    $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                    echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-10 h-10 profile-icon rounded-full" />';
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
                        <textarea id="TxtAreaAnnouncement" class="rar outline-none w-full h-40" type="text" placeholder="Say something here..."></textarea>
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

    <div id="delete-modal" class="modal hidden fixed inset-0 z-50 h-full w-full flex items-center justify-center ">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button" class="closeReportModal absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this product?</h3>
                    <button id="deletePostbtn" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes, I'm sure
                    </button>
                    <button type="button" class="text-gray-400 closeReportModal">No, cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="../student-alumni/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</body>

</html>