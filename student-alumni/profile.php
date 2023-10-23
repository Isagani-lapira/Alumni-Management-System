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
        $firstName = $personData['fname'];
        $lastName = $personData['lname'];
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="icon" href="../assets/bulsu_connect_img/bulsu_connect_icon.png" type="image/x-icon">
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

    <span id="promptMsgComment" class="hidden rounded-md slide-bottom fixed bottom-28 px-4 py-2 z-50 bg-accent text-white font-bold">Comment successfully added</span>
    <!-- Navbar -->
    <nav class=" z-50 w-full fixed top-0 grid grid-cols-3 gap-4 p-3 bg-white text-black shadow-lg">
        <!-- Logo -->
        <a href="homepage.php" class="col-span-1 flex items-center">
            <img src="../assets/bulsu_connect_img/bulsu_connect_logo.png" alt="Logo" class=" w-32 h-16" />
        </a>

        <!-- Search Bar -->
        <div class="col-span-1 flex items-center justify-center relative">
            <div class="relative w-full">
                <input type="text" id="searchUser" placeholder="Search" class="pl-10 pr-4 py-3 w-full border-accent center-shadow p-3 rounded-md shadow text-sm border outline-none transparent-search-bar" />
                <i class="absolute left-3 top-1/2 transform -translate-y-1/2 fas fa-search text-accent text-base"></i>
            </div>
            <div id="searchProfile" class="absolute top-16 bg-white rounded-b-lg p-3 z-50 w-full hidden"></div>
        </div>

        <!-- Menu Items -->
        <ul class="col-span-1 flex items-center justify-end space-x-8 px-4">
            <li>
                <a href="#" class="text-blue-600 hover:text-blue-800 items-center flex gap-2">
                    <?php
                    if ($profilepicture == "") {
                        echo '<img id="profilePhoto" src="../assets/icons/person.png" alt="Profile Icon" class="w-12 h-12 rounded-full object-contain bg-white" />';
                    } else {
                        $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                        echo '<img id="profilePhoto" src="' . $srcFormat . '" alt="Profile Icon" class="w-12 h-12 rounded-full object-cover bg-white" />';
                    }
                    ?>
                    <?php
                    echo '<p class="mt-1 font-bold text-greyish_black">' . $fullname . '</p>';
                    ?>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Cover Photo -->
    <div class="relative bgCover">
        <?php
        if ($coverPhoto == "") {
            // Use the default image as the background of the container
            echo '<div class="h-full bg-black w-3/4 mx-auto rounded-md" style="background-image: url(../images/bganim.jpg); background-size: cover; background-position: center;"></div>';
        } else {
            // Use the uploaded image as the background of the container
            $srcFormat = 'data:image/jpeg;base64,' . $coverPhoto;
            echo '<div class="h-full w-3/4 mx-auto bg-no-repeat rounded-md bg-black bg-opacity-50" style="background-image: url(' . $srcFormat . '); 
            background-size: cover; background-position: center bottom;">
            </div>';
        }
        ?>
        <!-- Profile Photo (Intersecting with the Cover Photo) -->
        <div class="profileWrapper absolute bottom-0 flex transform -translate-x-1/4 translate-y-1/2 rounded-full border-4 border-accentBlue overflow-hidden w-44 h-44">
            <?php
            if ($profilepicture == "") {
                echo '<img id="profilePhoto" src="../assets/icons/person.png" alt="Profile Icon" class="w-full h-full object-contain bg-white" />';
            } else {
                $srcFormat = 'data:image/jpeg;base64,' . $profilepicture;
                echo '<img id="profilePhoto" src="' . $srcFormat . '" alt="Profile Icon" class="w-full h-full object-cover bg-white" />';
            }
            ?>
        </div>
    </div>

    <!-- Buttons -->
    <div class="flex items-center justify-between w-3/4 mx-auto my-4">
        <div class="w-1/4"></div>
        <div class="w-3/4 flex items-center">
            <!-- Profile Info -->
            <div class=" w-1/2">
                <!-- Student's name and user handle -->
                <?php
                echo '<p class="font-bold text-3xl text-blue-900">' . $fullname . '</p>';
                echo '<p class="text-gray-600 text-lg">' . $username . '</p>';
                ?>

            </div>
            <div class="flex justify-end gap-2 w-1/2">
                <button id="viewResumeBtn" class="bg-accent hover:bg-darkAccent text-white px-4 py-2 rounded-lg">View Resume</button>
                <button id="modal-openBtn" class="bg-gray-100 hover:bg-gray-200 text-accent border border-accent px-4 py-2 rounded-lg">Edit Profile</button>
            </div>
        </div>

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
            <div id="feedContainer" class="post w-5/6 mx-auto post-width p-3 h-full no-scrollbar">
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
                <p id="loadingData" class="text-gray-400 text-center">Loading data...</p>
                <p id="noProfPostMsg" class="text-blue-400 text-center hidden">No available post</p>
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
                        <h2 id="profileModalFN" class=" md:text-lg font-bold text-gray-700"></h2>
                        <p id="profileModalUN" class="text-gray-500 text-sm"></p>
                    </div>

                    <button class="px-3 md:px-4 py-2 text-xs md:text-sm bg-red-800 text-white rounded-md">Send Email</button>
                </div>

                <h2 class="text-md md:text-lg font-bold mb-2 text-greyish_black">Social Media</h2>

                <!-- social media links -->
                <div class="flex gap-2 border-b border-gray-300 text-sm text-gray-500 py-2 mb-2">

                    <div class="flex-1 flex-col gap-4 justify-center items-center">
                        <!-- facebook -->
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="formkit:facebook" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                            <span id="facebookUN" class="text-center"></span>
                        </div>

                        <!-- instagram -->
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="formkit:instagram" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                            <span id="instagramUN" class="text-center"></span>
                        </div>
                    </div>

                    <div class="flex-1 flex-col gap-3 justify-center items-center">
                        <!-- twitter -->
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="simple-icons:twitter" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                            <span id="twitterUN" class="text-center"></span>
                        </div>

                        <!-- linkedIN -->
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="uiw:linkedin" style="color: #afafaf;" width="20" height="20"></iconify-icon>
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

    <!-- EDIT PROFILE -->
    <div id="profileModalEdit" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
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
                <button class="text-postButton hover:bg-gray-400 px-4 rounded-md py-2" id="cancelProfile">Cancel</button>
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
                <button class="text-postButton hover:bg-gray-400 px-4 rounded-md py-2" id="cancelCover">Cancel</button>
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
                    <button class="px-2 py-1" id="cancelLocation">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 rounded-md py-1" id="saveLocation">Save</button>
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
                    <button class="px-2 py-1" id="cancelEmail">cancel</button>
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
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1 rounded-md" id="saveContact">Save</button>
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
                    <button class="px-2 py-1" id="cancelFB">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1 rounded-md" id="saveFB">Save</button>
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
                    <button class="px-2 py-1" id="cancelIG">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton text-white px-2 py-1 rounded-md" id="saveIG">Save</button>
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
                    <button class="px-2 py-1" id="cancelTweet">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton rounded-md text-white px-2 py-1" id="saveTweet">Save</button>
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
                    <button class="px-2 py-1" id="cancelLinkedIn">cancel</button>
                    <button class="bg-postButton hover:bg-postHoverButton rounded-md text-white px-2 py-1" id="saveLinkedIn">Save</button>
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

    <div id="editResumeModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div id="resumeWrapper" class="relative w-1/2 h-max p-3 bg-white rounded-md">
            <form id="formResume">
                <h1 class="text-lg text-greyish_black font-black text-center py-2 border-b border-gray-400">
                    Edit Resume
                </h1>

                <!-- first page -->
                <div id="pageNo0" class="px-3 flex flex-col gap-3">
                    <!-- personal information -->
                    <div class="personInfoSection">
                        <h1 class=" text-greyish_black font-black">Personal Details</h1>
                        <p class="text-sm text-gray-500">Note: The information provided below depends on your account's personal data.
                            If there are any details that need to be changed, please update your profile.</p>
                        <?php
                        echo '<div class="flex items-center gap-2 mt-4">
                        <div class="flex-1 flex flex-col text-sm text-gray-500">
                            <span class="font-bold">First name</span>
                            <input id="firstnameEdit" type="text" disabled class="p-2 w-full bg-gray-300 rounded-md personalInput" value="' . $firstName . '"
                            placeholder="' . $firstName . '"/>
                        </div>
                        <div class="flex-1 flex flex-col text-sm text-gray-500">
                           <span class="font-bold">Last name</span>
                           <input id="lastnameEdit" type="text" disabled class="p-2 w-full bg-gray-300 rounded-md personalInput" value="' . $lastName . '"
                            placeholder="' . $lastName . '"/>
                        </div>
                        </div>';

                        echo '<div class="flex items-center gap-2 mt-2">
                        <div class="flex-1 flex flex-col text-sm text-gray-500">
                            <span class="font-bold">Address</span>
                            <input id="addressEdit" type="text" disabled class="p-2 w-full bg-gray-300 rounded-md personalInput" value="' . $address . '"
                            placeholder="' . $address . '"/>
                        </div>
                        <div class="flex-1 flex flex-col text-sm text-gray-500">
                           <span class="font-bold">Contact No.</span>
                           <input id="contactNoEdit" type="text" disabled class="p-2 w-full bg-gray-300 rounded-md personalInput" value="' . $contactNo . '"
                            placeholder="' . $contactNo . '"/>
                        </div>
                        </div>';

                        echo '
                        <div class="flex flex-col w-full text-sm text-gray-500 mt-2">
                            <span class="font-bold">Email Address</span>
                            <input id="emailAddEdit" type="text" disabled class="p-2 w-full bg-gray-300 rounded-md" value="' . $personal_email . '"
                            placeholder="' . $personal_email . '"/>
                        </div>'
                        ?>
                    </div>

                    <!-- academic background -->
                    <div class="academicBackground">
                        <h1 class=" text-greyish_black font-black">Academic Background</h1>
                        <!-- primary education -->
                        <div class="primary flex gap-2 text-sm text-gray-500 mt-2 education">
                            <div class="w-1/2">
                                <p class="font-bold px-2">Education Level</p>
                                <input id="degree0" type="text" class=" p-2 w-full rounded-md requiredValue" placeholder="Primary education">
                            </div>

                            <div class="w-1/4">
                                <p class="font-bold text-center">Start year</p>
                                <select id="startYr0" class=" yearSelection p-2 w-full rounded-md requiredValue"></select>
                            </div>

                            <div class="w-1/4">
                                <p class="font-bold text-center">End Year</p>
                                <select id="endYr0" class=" yearSelection p-2 w-full rounded-md requiredValue"></select>
                            </div>

                        </div>

                        <!-- secondary education -->
                        <div class="flex gap-2 text-sm text-gray-500 education">
                            <div class="w-1/2">
                                <input id="degree1" type="text" class="secondary p-2 w-full rounded-md requiredValue" placeholder="Secondary education">
                            </div>

                            <div class="w-1/4">
                                <select id="startYr1" class="secondary yearSelection p-2 w-full rounded-md requiredValue"></select>
                            </div>

                            <div class="w-1/4">
                                <select id="endYr1" class="secondary yearSelection p-2 w-full rounded-md requiredValue"></select>
                            </div>

                        </div>

                        <!-- tertiary education -->
                        <div class="flex gap-2 text-sm text-gray-500 education">
                            <div class="w-1/2">
                                <input id="degree2" type="text" class="tertiary p-2 w-full rounded-md requiredValue" placeholder="Tertiary education">
                            </div>

                            <div class="w-1/4">
                                <select id="startYr2" class=" tertiary yearSelection p-2 w-full rounded-md requiredValue"></select>
                            </div>

                            <div class="w-1/4">
                                <select id="endYr2" class="tertiary yearSelection p-2 w-full rounded-md requiredValue"></select>
                            </div>
                        </div>

                        <p class="text-gray-400 italic text-sm">Note: if you are still a student, please include your anticipated graduate year.</p>
                    </div>

                </div>

                <!-- second page -->
                <div id="pageNo1" class="hidden">
                    <!-- work experience -->
                    <div class="workExpPage">
                        <h3 class=" text-greyish_black font-black">Work Experience</h3>
                        <p class="italic text-xs">Note: Maximum work experience you can add is 4</p>

                        <!-- first work experience -->
                        <div class="flex flex-wrap gap-1 items-center py-2 border-b border-gray-400 experience">
                            <svg id="addWorkExp3" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>

                            <input id="workTitle0" class="flex-1 p-2 rounded-md job-title" type="text" placeholder="Job Title">
                            <input id="workDescript0" class="flex-1 responsibility" type="text" placeholder="Brief discussion of your responsibilities">
                            <input id="workCompanyName0" class="flex-1 p-2 rounded-md company-name" type="text" placeholder="Company/Organization name">

                            <div class="flex-0">
                                <select id="workStartYr0" class="yearSelection p-2 flex-0 rounded-md year">
                                    <option value="" disabled selected>Start Year</option>
                                </select>
                                <select id="workEndYr0" class="yearSelection p-2 flex-0 rounded-md year">
                                    <option value="" disabled selected>End Year</option>
                                </select>
                            </div>



                        </div>

                        <!-- second work experience -->
                        <div class="flex flex-wrap gap-2 items-center py-2 border-b border-gray-400 experience">
                            <svg id="addWorkExp3" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>

                            <input id="workTitle1" class="flex-1 p-2 rounded-md job-title" type="text" placeholder="Job Title">
                            <input id="workDescript1" class="flex-1 responsibility" type="text" placeholder="Brief discussion of your responsibilities">
                            <input id="workCompanyName1" class="flex-1 p-2 rounded-md company-name" type="text" placeholder="Company/Organization name">

                            <div class="flex-0">
                                <select id="workStartYr1" class="yearSelection p-2 flex-0 rounded-md year">
                                    <option value="" disabled selected>Start Year</option>
                                </select>
                                <select id="workEndYr1" class="yearSelection p-2 flex-0 rounded-md year">
                                    <option value="" disabled selected>End Year</option>
                                </select>
                            </div>

                        </div>

                        <!-- third work experience -->
                        <div class="flex flex-wrap gap-2 items-center py-2 border-b border-gray-400 experience">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>

                            <input id="workTitle2" class="flex-1 p-2 rounded-md job-title" type="text" placeholder="Job Title">
                            <input id="workDescript2" class="flex-1 responsibility" type="text" placeholder="Brief discussion of your responsibilities">
                            <input id="workCompanyName2" class="flex-1 p-2 rounded-md company-name" type="text" placeholder="Company/Organization name">

                            <div class="flex-0">
                                <select id="workStartYr2" class="yearSelection p-2 flex-0 rounded-md year">
                                    <option value="" disabled selected>Start Year</option>
                                </select>
                                <select id="workEndYr2" class="yearSelection p-2 flex-0 rounded-md year">
                                    <option value="" disabled selected>End Year</option>
                                </select>
                            </div>

                        </div>

                        <!-- fourth work experience -->
                        <div class="flex flex-wrap gap-2 items-center py-2 border-b border-gray-400 experience">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>

                            <input id="workTitle3" class="flex-1 p-2 rounded-md job-title" type="text" placeholder="Job Title">
                            <input id="workDescript3" class="flex-1 responsibility" type="text" placeholder="Brief discussion of your responsibilities">
                            <input id="workCompanyName3" class="flex-1 p-2 rounded-md company-name" type="text" placeholder="Company/Organization name">

                            <div class="flex-0">
                                <select id="workStartYr3" class="yearSelection p-2 flex-0 rounded-md year">
                                    <option value="" disabled selected>Start Year</option>
                                </select>
                                <select id="workEndYr3" class="yearSelection p-2 flex-0 rounded-md year">
                                    <option value="" disabled selected>End Year</option>
                                </select>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- skills-->
                <div id="pageNo2" id="skillPage" class="hidden">
                    <h1 class=" text-greyish_black font-black">Skills you have</h1>
                    <p class="text-sm text-gray-500">
                        Note: Please provide only the skills that are relevant to the job you are applying for, in order to make yourself stand out. Please also note that the minimum for this part is 3, and the maximum is 6
                    </p>

                    <div class="flex flex-col gap-3 mt-4">
                        <!-- first skill -->
                        <div class="flex gap-2 items-center skillData">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>
                            <input id="skill0" type="text" placeholder="ex: Time management" class="skillInput border-b border-gray-300 w-1/2 p-2">
                        </div>

                        <!-- second skill -->
                        <div class="flex gap-2 items-center skillData">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>
                            <input id="skill1" type="text" placeholder="ex: Time management" class="skillInput border-b border-gray-300 w-1/2 p-2">
                        </div>

                        <!-- third skill -->
                        <div class="flex gap-2 items-center skillData">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>
                            <input id="skill2" type="text" placeholder="ex: Time management" class="skillInput border-b border-gray-300 w-1/2 p-2">
                        </div>

                        <!-- fourth skill -->
                        <div class="flex gap-2 items-center skillData">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>
                            <input id="skill3" type="text" placeholder="ex: Time management" class="skillInput border-b border-gray-300 w-1/2 p-2">
                        </div>

                        <!-- fifth skill -->
                        <div class="flex gap-2 items-center skillData">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>
                            <input id="skill4" type="text" placeholder="ex: Time management" class="skillInput border-b border-gray-300 w-1/2 p-2">
                        </div>

                        <!-- sixth skill -->
                        <div class="flex gap-2 items-center skillData">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <g id="galaAdd0" fill="none" stroke="#686b6f" stroke-dasharray="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="4" stroke-opacity="1" stroke-width="16">
                                    <circle id="galaAdd1" cx="128" cy="128" r="112" />
                                    <path id="galaAdd2" d="M 79.999992,128 H 176.0001" />
                                    <path id="galaAdd3" d="m 128.00004,79.99995 v 96.0001" />
                                </g>
                            </svg>
                            <input id="skill5" type="text" placeholder="ex: Time management" class="skillInput border-b border-gray-300 w-1/2 p-2">
                        </div>

                    </div>
                </div>

                <!-- reference -->
                <div id="pageNo3" class="hidden">
                    <h1 class=" text-greyish_black font-black">References</h1>
                    <p class="text-sm text-gray-500">
                        Please ensure that you have obtained permission to include individuals
                        as your references before listing their contact details on your resume.
                        It is also good practice to select references who can speak positively
                        about your professional abilities and work experiences
                    </p>

                    <!-- first person -->
                    <div class="wrapper my-2 text-gray-500 border-b border-gray-300 py-2 referenceData">
                        <header class="font-bold text-blue-500">Person 1</header>
                        <div class="flex flex-wrap gap-2 text-sm">
                            <div class="flex flex-col">
                                <label for="refFN0" class="font-bold" for="refFN">Full name</label>
                                <input class="referencesInput requiredValue" type="text" id="refFN0" placeholder="ex: Juan Dela Cruz">
                            </div>

                            <div class="flex flex-col">
                                <label for="refJobTitle" class="font-bold" for="refJobTitle0">Job Title</label>
                                <input class="referencesInput requiredValue" type="text" id="refJobTitle0" placeholder="ex: Software Engineer">
                            </div>

                            <div class="flex flex-col">
                                <label for="refContactNo" class="font-bold" for="refContactNo0">Contact No.</label>
                                <input class="referencesInput requiredValue" type="text" id="refContactNo0" placeholder="ex: 09104938530">
                            </div>

                            <div class="flex flex-col">
                                <label for="refEmailAdd" class="font-bold" for="refEmailAdd0">Email Address</label>
                                <input class="referencesInput requiredValue" type="text" id="refEmailAdd0" placeholder="ex: juandelacruz@gmail.com">
                            </div>

                        </div>
                    </div>

                    <!-- second person -->
                    <div class="wrapper my-2 text-gray-500 border-b border-gray-300 py-2 referenceData">
                        <header class="font-bold text-blue-500">Person 2</header>
                        <div class="flex flex-wrap gap-2 text-sm">
                            <div class="flex flex-col">
                                <label for="refFN1" class="font-bold" for="refFNSecond">Full name</label>
                                <input class="referencesInput requiredValue" type="text" id="refFN1" placeholder="ex: Juan Dela Cruz">
                            </div>

                            <div class="flex flex-col">
                                <label class="font-bold" for="refJobTitle1">Job Title</label>
                                <input class="referencesInput requiredValue" type="text" id="refJobTitle1" placeholder="ex: Software Engineer">
                            </div>

                            <div class="flex flex-col">
                                <label class="font-bold" for="refContactNo1">Contact No.</label>
                                <input class="referencesInput requiredValue" type="text" id="refContactNo1" placeholder="ex: 09104938530">
                            </div>

                            <div class="flex flex-col">
                                <label class="font-bold" for="refEmailAdd1">Email Address</label>
                                <input class="referencesInput requiredValue" type="text" id="refEmailAdd1" placeholder="ex: juandelacruz@gmail.com">
                            </div>

                        </div>
                    </div>

                    <div class="wrapper my-2 text-gray-500 border-b border-gray-300 py-2 referenceData">
                        <header class="font-bold text-blue-500">Person 3</header>
                        <div class="flex flex-wrap gap-2 text-sm">
                            <div class="flex flex-col">
                                <label class="font-bold" for="refFN2">Full name</label>
                                <input type="text" id="refFN2" class="requiredValue" placeholder="ex: Juan Dela Cruz">
                            </div>

                            <div class="flex flex-col">
                                <label class="font-bold" for="refJobTitle2">Job Title</label>
                                <input type="text" id="refJobTitle2" class="requiredValue" placeholder="ex: Software Engineer">
                            </div>

                            <div class="flex flex-col">
                                <label class="font-bold" for="refContactNo2">Contact No.</label>
                                <input type="text" id="refContactNo2" class="requiredValue" placeholder="ex: 09104938530">
                            </div>

                            <div class="flex flex-col">
                                <label class="font-bold" for="refEmailAdd2">Email Address</label>
                                <input type="text" id="refEmailAdd2" class="requiredValue" placeholder="ex: juandelacruz@gmail.com">
                            </div>

                        </div>
                    </div>

                </div>


                <!-- resume summary -->
                <div id="pageNo4" class="hidden">
                    <h1 class=" text-greyish_black font-black">Objective</h1>
                    <p class="text-sm text-gray-500">
                        It is a brief statement that highlights your career goals,
                        skills, and what you can bring to the table for potential
                        employers. A summary is typically used for those with some
                        work experience, while an objective is more suitable for
                        entry-level candidates.
                    </p>
                    <textarea id="objectiveInput" class="requiredValue w-full h-40 p-3 border border-gray-300 mt-4 rounded-md text-gray-500 outline-none" placeholder="Add an amazing summary about you"></textarea>
                </div>


                <div class="mt-2 flex justify-end gap-2">
                    <button id="resumeBtnPrev" type="button" class="px-4 py-2 rounded-md hover:bg-gray-300 text-gray-500 hidden">Previous</button>
                    <button id="resumeBtnNext" type="button" class="px-4 py-2 rounded-md bg-blue-400 hover:bg-blue-500 text-white ">Next</button>
                    <button id="resumeBtnUpdate" type="button" class="px-4 py-2 rounded-md bg-green-300 text-white hidden" disabled>Update Resume</button>
                </div>

            </form>

            <button class="closeEditorResume text-white rounded-full items-center flex justify-center hover:border hover:border-white  absolute top-0 -right-11 p-2">
                <iconify-icon icon="ei:close" width="24" height="24"></iconify-icon>
            </button>
        </div>
    </div>

    <div id="viewResumeModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="fixed h-max w-full bg-black bg-opacity-50  flex justify-between p-3 top-0 gap-2">
            <button id="closeViewResume" class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-gray-300 hover:bg-opacity-50">
                <iconify-icon icon="fluent-mdl2:back" style="color: white;" width="24" height="24"></iconify-icon>
            </button>

            <div class="flex gap-2">
                <button id="editResumeBtn" class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-gray-300 hover:bg-opacity-50">
                    <iconify-icon icon="ant-design:edit-outlined" style="color: white;" width="24" height="24"></iconify-icon>
                </button>
                <button id="downloadResume" class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-gray-300 hover:bg-opacity-50">
                    <iconify-icon icon="teenyicons:download-outline" style="color: white;" width="24" height="24"></iconify-icon>
                </button>
                <button id="printResume" class="w-12 h-12 flex items-center justify-center rounded-full hover:bg-gray-300 hover:bg-opacity-50">
                    <iconify-icon icon="fluent:print-32-regular" style="color: white;" width="24" height="24"></iconify-icon>
                </button>
            </div>

        </div>
        <div class="bg-white p-5 h-full overflow-y-auto w-max">
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

    <!-- status modal -->
    <div id="postStatusModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="postStatus bg-white rounded-md w-2/6 p-5 flex flex-col gap-3">
            <div class="flex justify-between">
                <div class="flex items-center">
                    <img id="profileStatusImg" class="w-10 h-10 rounded-full" alt="" srcset="">

                    <div class="px-2 text-greyish_black text-sm ">
                        <p id="statusFullnameUser" class="font-bold"></p>
                        <span class="italic accountUN text-gray-400"></span>
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
            <div class="flex-col text-sm border-t border-gray-400 py-2 commentStatus overflow-y-auto">
                <div class="flex gap-2 text-gray-500 text-xs">
                    <p>Likes: <span id="statusLikes"></span></p>
                    <p>Comments: <span id="statusComment"></span></p>
                </div>

                <div id="commentStatus" class="flex flex-col gap-2 p-2 mt-2"></div>
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
            <h1 class=" text-3xl font-bold text-green-500 text-center">Resume done!</h1>
            <p class=" text-lg text-center text-gray-500">"Your resume is a powerful tool that will help you stand out in the competitive job market."</p>
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

    <div id="delete-comment" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden ">
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
                    <button id="deleteCommentBtn" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes, I'm sure
                    </button>
                    <button type="button" class="text-gray-400 closeReportModal">No, cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="../student-alumni/js/profile.js"></script>
    <script src="../student-alumni/js/searchProfile.js"></script>
    <script src="../student-alumni/js/resumescript.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
</body>

</html>