<?php
session_start();
require_once('../model/PersonModel.php');
require_once("../../PHP_process/connection.php");


$personData = [];
// get the id of the user from session id
if (isset($_SESSION['personID'])) {

    // Get the person data
    $model = new PersonModel($mysql_con, $_SESSION['colCode']);
    $personData = $model->getOneById($_SESSION['personID']);
    // Get college data
    $colCode = $_SESSION['colCode'];
    $query = "SELECT * FROM college WHERE colCode = '$colCode';";
    $result = mysqli_query($mysql_con, $query);
    $data = mysqli_fetch_assoc($result);

    $colName = $data['colname'];
    $colEmailAdd = $data['colEmailAdd'];
    $colContactNo = $data['colContactNo'];
    $colWebLink = $data['colWebLink'];
    $colLogo = $data['colLogo'];
    $colDean = $data['colDean'];
    $colDeanImg = $data['colDeanImg'];
    $description = trim($data['description']);

    // cover photo
    if ($personData['cover_photo'] == null) {
        $personData['cover_photo'] = "../assets/default_cover_photo.png";
    } else {
        // encode
        $personData['cover_photo'] = " data:image/jpeg;base64," . base64_encode($personData['cover_photo']);
    }

    // add a default image if image is null
    if ($personData['profilepicture'] == null) {
        $personData['profilePicture'] = "../assets/default_profile.png";
    } else {

        // encode
        $personData['profilepicture'] = " data:image/jpeg;base64," . base64_encode($personData['profilepicture']);
    }

    if ($colDeanImg == null) {
        $colDeanImg = "../assets/default_profile.png";
    } else {

        // encode
        $colDeanImg = " data:image/jpeg;base64," . base64_encode($colDeanImg);
    }
} else {
    echo "<h1>" . "No person ID" .  "</h1>";
    die();
}

?>
<section class=" min-h-full">

    <h2 class="daisy-menu-title text-xl text-slate-800">Your Profile</h2>
    <div class="flex flex-row h-full max-h-full  border">


        <section class="px-4 mr-4 border border-t-0 " id="">

            <ul class="daisy-menu daisy-menu-md space-y-2 " id="profile-tab-container">
                <li>
                    <a class="daisy-menu-item daisy-active " href="#college-profile-container">College Profile</a>
                </li>
                <li>
                    <a class=" daisy-menu-item  " href="#account-profile-container">Community Profile</a>
                </li>

            </ul>

        </section>



        <section id="content-container" class="flex-1 mt-4 overflow-auto">


            <section id="college-profile-container" class="overflow-auto">
                <section id="view-college-profile" class="overflow-auto ">
                    <h1 class="text-lg font-extrabold">
                        College Profile
                    </h1>
                    <div class="px-10">
                        <div class="grid grid-cols-2 h-max p-5">
                            <img id="colLogo" class="w-6/12 aspect-square block mx-auto" src="
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

                                <!-- * Edit Profile Button -->
                                <div class="flex justify-normal mt-5">
                                    <button class="daisy-btn btn-primary" id="edit-college-profile-btn">Edit College Profile</button>
                                </div>
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
                                    <img id="adminImg" src="<?= $personData['profilepicture'] ?>
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

                            <!-- <h1>TODO: Add Courses</h1> -->
                            <ul>
                                <li>BS Information Technology</li>
                            </ul>
                        </div>
                    </div>

                </section>


                <!-- start EDIT college profile -->
                <section id="edit-college-profile" class="hidden overflow-auto">
                    <h2 class="font-bold text-xl">Edit College Information</h2>
                    <!-- Form for email, change password section with password and confirm password and update button  -->
                    <form action="POST" id="update-college-form">
                        <input type="hidden" name="update-college-form" value="true">
                        <div class="flex flex-wrap justify-between gap-4 my-4">
                            <!-- Profile Picture -->
                            <div class="flex flex-wrap flex-col gap-4">
                                <div class="flex flex-wrap gap-4">
                                    <img id="colLogoPreview" class="w-24 h-24 block mx-auto rounded-full" src="
                                                                data:image/jpeg;base64,<?= $_SESSION['colLogo'] ?> 
                                                                " alt="">
                                    <div class="flex flex-col justify-center">
                                        <h3 class="font-bold text-lg">College Icon</h3>
                                        <p class="text-gray-400">Upload the icon to be displayed on the college</p>
                                    </div>
                                </div>

                                <div class="flex flex-row items-center">
                                    <input type="file" id="colLogoInput" name="colLogo" class="daisy-file-input w-full max-w-xs " />
                                    <button type="button" id="reset-logo" class="daisy-btn daisy-btn-outline daisy-btn-sm">Reset</button>
                                </div>
                            </div>

                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col gap-2 w-8/12 max-w-lg">
                                <div class="flex flex-col gap-2">
                                    <label for="colName" class="font-bold">College Name</label>
                                    <input type="text" value="<?= $colName ?>" name="colName" id="colName" class="form-input border border-gray-300 rounded-md p-2">
                                </div>

                            </div>

                            <div class="flex flex-col gap-2 w-8/12 max-w-lg">
                                <div class="flex flex-col gap-2">
                                    <label for="colCode" class="font-bold">College Code</label>
                                    <input type="text" value="<?= $colCode ?>" id="colCode" class="border border-gray-300 rounded-md p-2">
                                </div>
                            </div>

                        </div>

                        <!-- add form for number  -->
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap gap-4 w-8/12 max-w-lg">
                                <div class="flex flex-col gap-2">
                                    <label for="colContactNo" class="font-bold">Contact Number</label>
                                    <input type="text" value="<?= $colContactNo ?>" name="colContactNo" id="colContactNo" class="form-input border border-gray-300 rounded-md p-2">
                                </div>
                                <div class="flex flex-col gap-2 w-8/12 max-w-lg">
                                    <label for="colEmailAdd" class="font-bold">Email Address</label>
                                    <input type="text" value="<?= $colEmailAdd ?>" name="colEmailAdd" id="colEmailAdd" class="border border-gray-300 rounded-md p-2">
                                </div>
                            </div>
                        </div>

                        <!-- add for email address -->
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap gap-4 w-8/12 max-w-lg">
                                <div class="flex flex-col gap-2">
                                    <label for="colWebLink" class="font-bold">Website Link</label>
                                    <input type="text" value="<?= $colWebLink ?>" name="colWebLink" id="colWebLink" class="form-input border border-gray-300 rounded-md p-2">
                                </div>
                            </div>

                        </div>
                        <!-- add for description -->
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap gap-4 w-8/12 max-w-lg">
                                <div class="flex flex-col gap-2">
                                    <label for="description" class="font-bold">Description</label>
                                    <textarea name="description" id="colDescription" cols="60" rows="10" class="form-textarea border border-gray-300 rounded-md p-2"><?= htmlspecialchars($description) ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!--    add for dean image -->
                        <!-- Dean Picture -->
                        <div class="flex flex-wrap justify-between gap-4 my-4">
                            <div class="flex flex-col gap-4">

                                <div class="flex flex-wrap gap4">
                                    <img id="deanImgPreview" class="w-24 h-24 block mx-auto rounded-full" src="
                                                               <?= $colDeanImg ?> 
                                                                " alt="">
                                    <div class="flex flex-col justify-center">
                                        <h3 class="font-bold text-lg">College Dean Picture</h3>
                                        <p class="text-gray-400">Upload the picture to be displayed on the dean of the college.</p>
                                    </div>
                                </div>

                                <div class="flex flex-row items-center">
                                    <input type="file" id="deanImgInput" class="daisy-file-input" name="colDeanImg" />

                                    <button type="button" id="reset-dean" class="daisy-btn daisy-btn-outline daisy-btn-sm">Reset</button>
                                </div>
                            </div>

                        </div>

                        <!-- add for college dean name -->
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap gap-4 w-8/12 max-w-lg">
                                <div class="flex flex-col gap-2  w-8/12 max-w-lg">
                                    <label for="colDean" class="font-bold">College Dean Name</label>
                                    <input type="text" value="<?= $colDean ?>" name="colDean" id="colDean" class="form-input border border-gray-300 rounded-md p-2">
                                </div>
                            </div>
                        </div>



                        <!-- Update Button -->
                        <div class="flex flex-col lg:flex-row justify-end gap-4 ">

                            <!-- cancel-edit-college-profile-btn -->
                            <button id="cancel-edit-college-profile-btn" class="btn-tertiary" type="button">Cancel</button>
                            <div class="flex flex-col gap-2">
                                <button type="submit" name="update-college" value="update-college" id="update-college-submit-btn" class=" btn-primary">Update College Profile</button>
                            </div>

                        </div>

                    </form>

                </section>



            </section>

            <section id="account-profile-container" class="hidden">
                <!-- profile content -->
                <section id="view-profile-container">
                    <div class="p-3 rounded-md bg-accent flex items-center my-3">
                        <img class="h-36 w-36 rounded-full border-2 border-white" src="<?= $personData['profilepicture'] ?>" alt="">
                        <div class="ms-6">
                            <p class="text-lg text-white font-bold">
                                <?= $personData['fname'] . ' ' . $personData['lname'] ?>
                            </p>
                            <!-- todo -->
                            <!-- <a class=" daisy-btn" href="#settings" id="edit-profile-btn">Edit Profile</a> -->
                        </div>
                    </div>
                    <div class="flex text-grayish">

                        <!-- Profile Information section -->
                        <section class="w-1/4 text-sm m-2 p-4 mr-5 border text-gray-800">
                            <p class="font-bold text-accent">About</p>
                            <div class="flex mt-3 justify-start">
                                <img src="/assets/icons/person.png" alt="">
                                <span class="px-2"><?= ucfirst($personData['gender']) ?></span>
                            </div>

                            <div class="flex mt-3">
                                <img src="/assets/icons/cake.png" alt="">
                                <span class="px-2">Born <?= $personData['bday'] ?></span>
                            </div>

                            <div class="flex mt-3">
                                <img class="ps-1 messageIcon" src="/assets/icons/Location.png" alt="">
                                <span class="px-3"><?= $personData['address'] ?></span>
                            </div>

                            <div class="flex mt-3">
                                <img class="ps-1 " src="/assets/icons/Message.png" alt="">
                                <span class="px-4"><?= $personData['bulsu_email'] ?></span>
                            </div>

                            <div class="flex mt-3">
                                <img class="ps-1" src="/assets/icons/Call.png" alt="">
                                <span class="px-4"><?= $personData['contactNo'] ?></span>
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
                                    <h2>No More Posts Available </h2>
                                </div>

                            </div>
                        </section>
                    </div>


                </section>




            </section>

        </section>

    </div>


</section>


<script>
    $(document).ready(function() {
        $.getScript("./profile/postScript.js");
    });
</script>

<script type="module" src="./profile/profile.js"></script>