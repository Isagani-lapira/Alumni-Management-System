<?php
session_start();
require_once('../model/PersonModel.php');
require_once("../../PHP_process/connection.php");


$alumniData = [];
// get the id of the user from session id
if (isset($_SESSION['personID'])) {
    $model = new PersonModel($mysql_con, $_SESSION['colCode']);
    // todo refactor later
    $alumniData = $model->getOneById($_SESSION['personID']);
    // Get college data
    // Add session info from the college info
    $colCode = $_SESSION['colCode'];
    $query = "SELECT * FROM college WHERE colCode = '$colCode';";
    $result = mysqli_query($mysql_con, $query);
    $data = mysqli_fetch_assoc($result);


    $colName = $data['colname'];
    // colEmailAdd` varchar(70) DEFAULT NULL,
    /**
     *   `colContactNo` 
     * `colWebLink` 
     *    `colLogo` 
     * `colDean` 
     * `colDeanImg` 
     */
    // get the columns
    $colEmailAdd = $data['colEmailAdd'];
    $colContactNo = $data['colContactNo'];
    $colWebLink = $data['colWebLink'];
    $colLogo = $data['colLogo'];
    $colDean = $data['colDean'];
    $colDeanImg = $data['colDeanImg'];


    // add a default image if image is null
    if ($alumniData['profilepicture'] == null) {
        $alumniData['profilePicture'] = "https://www.w3schools.com/howto/img_avatar.png";
    } else {

        // encode
        $alumniData['profilepicture'] = base64_encode($alumniData['profilepicture']);
    }

    if ($colDeanImg == null) {
        $colDeanImg = "base64https://www.w3schools.com/howto/img_avatar.png";
    } else {

        // encode
        $colDeanImg = base64_encode($colDeanImg);
    }
    $description = $data['description'];
} else {
    echo "<h1>" . "No person ID" .  "</h1>";
    die();
}

?>
<section>
    <div class="flex flex-col ">
        <section class="daisy-tabs" id="profile-tab-container">
            <a class="daisy-tab daisy-tab-lg daisy-tab-bordered daisy-tab-active" href="#college-profile-container">College Profile</a>
            <a class="daisy-tab daisy-tab-lg daisy-tab-bordered " href="#account-profile-container">Community Profile</a>
        </section>

        <section id="content-container">


            <section id="college-profile-container" class="">
                <div class="individual-col h-full ">
                    <h1 class="text-xl font-extrabold">
                        College Profile
                    </h1>
                    <div class="px-10">
                        <div class="grid grid-cols-2 h-max p-5">
                            <img id="colLogo" class="w-1/2 block mx-auto" src="
                            data:image/jpeg;base64,<?= $_SESSION['colLogo'] ?> 
                            " alt="">

                            <div class="college-info text-xs">
                                <h1 id="colName" class="text-2xl font-extrabold">
                                    <?= $colName ?>
                                </h1>
                                <p class="text-gray-600 mt-3 font-medium">Number</p>
                                <p id="colContact" class="text-greyish_black text-sm font-semibold">
                                    <?= $colContactNo ?>
                                </p>

                                <p class="text-gray-600 mt-2 font-medium">Email Address</p>
                                <p id="colEmail" class="text-greyish_black text-sm font-semibold">
                                    <?= $colEmailAdd ?>
                                </p>

                                <p class="text-gray-600 mt-2 font-medium">Website</p>
                                <a id="colWebLink" target="_blank" class="text-sm text-blue-600 font-semibold">
                                    <?= $colWebLink ?>
                                </a>
                            </div>

                        </div>

                        <div class="flex justify-center gap-5 my-7">
                            <div class="dean">
                                <div class="text-center">
                                    <img id="deanImg" src="<?= $colDeanImg ?>" class="w-32 h-32 mx-auto rounded-md" alt="">
                                    <p id="colDean" class="text-accent font-medium"></p>
                                    <p class="text-gray-500 text-sm">DEAN, <?= $_SESSION['colCode'] ?></p>
                                </div>
                            </div>

                            <div class="coordinator">
                                <div class="text-center">
                                    <img id="adminImg" src="<?= $colDeanImg ?>
                                    " class="w-32 h-32  mx-auto rounded-md" alt="">
                                    <p id="colAdminName" class="text-accent font-medium"></p>
                                    <p class="text-gray-500 text-sm">Alumni Coordinator, <?= $_SESSION['colCode'] ?></p>
                                </div>
                            </div>

                        </div>

                        <div class="description mt-3 w-9/12">
                            <h1 class="text-xl font-extrabold ">ABOUT US <span id="collegeCode">
                                </span></h1>
                            <p class="py-3">

                                <?= $data['description'] ?>
                            </p>
                        </div>


                        <div class="courses-offered my-10 w-8/12">
                            <h3 class="text-xl font-extrabold mb-5">Courses Offered</h3>

                            <h1>TODO: Add Courses</h1>
                        </div>
                    </div>

                </div>

            </section>

            <section id="account-profile-container" class="hidden">
                <!-- profile content -->
                <div class="p-3 rounded-md bg-accent flex items-center my-3">
                    <img class="h-36 w-36 rounded-full border-2 border-white" src="/images/Mr.Jayson.png" alt="">
                    <div class="ms-6">
                        <p class="text-lg text-white font-bold">
                            <?= $alumniData['fname'] . ' ' . $alumniData['lname'] ?>
                        </p>
                        <p class="text-blue-300 hover:cursor-pointer hover:text-blue-500">Edit Profile</p>
                    </div>
                </div>
                <div class="flex text-grayish">

                    <!-- Profile Information section -->
                    <section class="w-1/4 text-sm m-2 p-4 mr-5 border text-gray-800">
                        <p class="font-bold text-accent">About</p>
                        <div class="flex mt-3 justify-start">
                            <img src="/assets/icons/person.png" alt="">
                            <span class="px-2"><?= ucfirst($alumniData['gender']) ?></span>
                        </div>

                        <div class="flex mt-3">
                            <img src="/assets/icons/cake.png" alt="">
                            <span class="px-2">Born <?= $alumniData['bday'] ?></span>
                        </div>

                        <div class="flex mt-3">
                            <img class="ps-1 messageIcon" src="/assets/icons/Location.png" alt="">
                            <span class="px-3"><?= $alumniData['address'] ?></span>
                        </div>

                        <div class="flex mt-3">
                            <img class="ps-1 " src="/assets/icons/Message.png" alt="">
                            <span class="px-4"><?= $alumniData['bulsu_email'] ?></span>
                        </div>

                        <div class="flex mt-3">
                            <img class="ps-1" src="/assets/icons/Call.png" alt="">
                            <span class="px-4"><?= $alumniData['contactNo'] ?></span>
                        </div>

                    </section>

                    <!-- Posts Section -->
                    <section class="w-full space-y-2">
                        <div id="feedContainer" class="flex flex-col gap-2 w-full no-scrollbar z-0">
                            <div class="flex gap-2 text-greyish_black text-sm my-2 border-b border-gray-300 p-3">
                                <button id="availablePostBtn" class="invert-accent rounded-md px-5 py-1">Post</button>
                                <button id="archievedBtnProfile" class="rounded-md px-5 py-1">Archived</button>
                            </div>

                        </div>

                        <!-- Shows only when there is no data yet -->
                        <div class="flex align-middle justify-center ">
                            <div class="bg-accent rounded p-3 text-white hidden no-data-class">
                                <h2>No Post Available Yet</h2>
                            </div>

                        </div>
                    </section>
                </div>


            </section>

        </section>

    </div>


</section>


<script>
    $(document).ready(function() {
        $.getScript("./profile/postScript.js");
        $.getScript("./profile/profile.js");
    });
</script>