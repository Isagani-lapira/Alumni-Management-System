<?php
session_start();
require_once('../model/PersonModel.php');
require_once("../../PHP_process/connection.php");


$alumniData = [];
// get the id of the user from session id
if (isset($_SESSION['personID'])) {

    // Get the person data
    $model = new PersonModel($mysql_con, $_SESSION['colCode']);
    $alumniData = $model->getOneById($_SESSION['personID']);
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
    if ($alumniData['cover_photo'] == null) {
        $alumniData['cover_photo'] = "../assets/default_cover_photo.png";
    } else {
        // encode
        $alumniData['cover_photo'] = " data:image/jpeg;base64," . base64_encode($alumniData['cover_photo']);
    }

    // add a default image if image is null
    if ($alumniData['profilepicture'] == null) {
        $alumniData['profilePicture'] = "../assets/default_profile.png";
    } else {

        // encode
        $alumniData['profilepicture'] = " data:image/jpeg;base64," . base64_encode($alumniData['profilepicture']);
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
                                    <button class="daisy-btn btn-primary" id="edit-college-profile-btn">Edit Profile</button>
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
                                    <img id="adminImg" src="<?= $alumniData['profilepicture'] ?>
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

                </section>


                <!-- start EDIT college profile -->
                <section id="edit-college-profile" class="hidden overflow-auto">
                    <h2 class="font-bold text-xl">Edit College Information</h2>
                    <!-- Form for email, change password section with password and confirm password and update button  -->
                    <form action="POST" id="update-college-form">
                        <input type="hidden" name="update-college-form" value="true">
                        <div class="flex flex-wrap justify-between gap-4 my-4">
                            <!-- Profile Picture -->
                            <div class="flex flex-wrap gap-4">
                                <img id="colLogoPreview" class="w-24 h-24 block mx-auto rounded-full" src="
                            data:image/jpeg;base64,<?= $_SESSION['colLogo'] ?> 
                            " alt="">
                                <div class="flex flex-col justify-center">
                                    <h3 class="font-bold text-lg">College Icon</h3>
                                    <p class="text-gray-400">Upload the icon to be displayed on the college</p>
                                </div>

                                <div class="flex flex-col justify-center">
                                    <input type="file" id="colLogoInput" name="colLogo" class="daisy-file-input w-full max-w-xs " />
                                    <button type="button" id="reset-logo" class="daisy-btn daisy-btn-outline daisy-btn-error">Reset Upload</button>
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
                            <div class="flex flex-wrap gap-4">

                                <img id="deanImgPreview" class="w-24 h-24 block mx-auto rounded-full" src="
                           <?= $colDeanImg ?> 
                            " alt="">
                                <div class="flex flex-col justify-center">
                                    <h3 class="font-bold text-lg">College Dean Picture</h3>
                                    <p class="text-gray-400">Upload the picture to be displayed on the dean of the college.</p>
                                </div>

                                <div class="flex flex-col justify-center">
                                    <input type="file" id="deanImgInput" class="daisy-file-input" name="colDeanImg" />

                                    <button type="button" id="reset-dean" class="daisy-btn daisy-btn-outline daisy-btn-error">Reset Upload</button>
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
                                <button name="update-college" value="update-college" id="update-college-submit-btn" class=" btn-primary">Update College Profile</button>
                            </div>

                        </div>

                    </form>

                </section>



            </section>

            <section id="account-profile-container" class="hidden">
                <!-- profile content -->
                <section id="view-profile-container">
                    <div class="p-3 rounded-md bg-accent flex items-center my-3">
                        <img class="h-36 w-36 rounded-full border-2 border-white" src="<?= $alumniData['profilepicture'] ?>" alt="">
                        <div class="ms-6">
                            <p class="text-lg text-white font-bold">
                                <?= $alumniData['fname'] . ' ' . $alumniData['lname'] ?>
                            </p>
                            <button class=" daisy-btn" id="edit-profile-btn">Edit Profile</button>
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

                <!-- START EDIT PERSONAL INFO -->
                <section id="edit-profile-container" class="hidden">
                    <h2 class="font-bold text-xl mb-12">Edit Personal Information</h2>

                    <form name="personal-info-form" id="personal-info-form" enctype="multipart/form-data">
                        <input type="hidden" name="personal-info-form" value="true">
                        <!-- Cover Photo -->
                        <h3 class="font-bold text-lg">Cover Photo</h3>
                        <p class="text-gray-400">Upload a cover photo to be displayed on your profile</p>


                        <!-- Placeholder for Cover Image -->
                        <div class=" h-60 relative group rounded-sm">
                            <img id="cover-img-preview" class="w-full bg-gray-100 rounded-sm object-contain max-h-full h-full block" src="
                            <?= $alumniData['cover_photo'] ?> 
                        " alt="">
                            <!-- Cover Image Input -->
                            <div class="daisy-form-control w-full max-w-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <label for="cover-img" class="daisy-label">
                                </label>
                                <input class="daisy-file-input daisy-file-input-bordered w-full max-w-xs" id="cover-img" type="file" accept=".jpg" name="cover-img">
                                <label class="daisy-label">
                                    <span class="daisy-label-text-alt">Use JPG File Format</span>
                                </label>
                            </div>
                        </div>




                        <!-- Profile Picture -->
                        <div class="flex flex-wrap justify-between gap-4 my-4">
                            <div class="flex flex-wrap gap-4">
                                <!-- <div class="w-24 h-24 rounded-full bg-gray-300"></div> -->

                                <div class="daisy-avatar">
                                    <div class="w-24 rounded-full">
                                        <img id="personal-img-preview" class=" bg-gray-100 object-contain  " src="
                                        <?= $alumniData['profilepicture'] ?>" alt="a profile picture">
                                    </div>
                                </div>

                                <div class="flex flex-col justify-center">
                                    <h3 class="font-bold text-lg">Profile Picture</h3>
                                    <p class="text-gray-400">Upload a profile picture to be displayed on your profile</p>
                                </div>

                                <div class="flex flex-col justify-center">
                                    <input type="file" name="personal-img" class=" text-white px-4 py-2 rounded-full daisy-file-input " id="personal-img-pic" />
                                </div>
                            </div>

                        </div>

                        <!-- Form that has first name, last name, birth date, gender, address -->
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex flex-col gap-2">
                                    <label for="firstName" class="font-bold">First Name</label>
                                    <input value="<?= $alumniData['fname'] ?>" required type="text" name="firstName" id="firstName" class="border border-gray-300 rounded-md p-2">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label for="lastName" class="font-bold">Last Name</label>
                                    <input value="<?= $alumniData['lname'] ?>" required type="text" name="lastName" id="lastName" class="border border-gray-300 rounded-md p-2">
                                </div>
                            </div>
                        </div>

                        <!-- birth date form input -->

                        <div class="flex flex-row gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="birthday" class="font-bold">Birth Day</label>
                                <input required type="date" name="birthday" id="firstName" class="form-input border border-gray-300 rounded-md p-2" value="<?= $alumniData['bday'] ?>">
                            </div>


                        </div>
                        <p class="daisy-label font-bold">Gender</p>
                        <div class="flex flex-row gap-2">
                            <label for="maleRadio" class="daisy-label">
                                <input type="radio" name="gender" value="male" class="daisy-radio" id="maleRadio" <?php if ($alumniData['gender'] === 'male') echo 'checked'; ?> />

                                Male
                            </label>
                            <label for="femaleRadio" class="daisy-label">
                                <input type="radio" name="gender" value="female" class="daisy-radio" id="femaleRadio" <?php if ($alumniData['gender'] === 'female') echo 'checked'; ?> />

                                Female
                            </label>
                        </div>


                        <!-- Old Address -->
                        <!-- <div class="flex flex-col gap-4">
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="street" class="font-bold">Street</label>
                        <input type="text" name="street" id="street" class="border border-gray-300 rounded-md p-2">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="city" class="font-bold">City</label>
                        <input type="text" name="city" id="city" class="border border-gray-300 rounded-md p-2">
                    </div>
                </div>
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="state" class="font-bold">State</label>
                        <input type="text" name="state" id="state" class="border border-gray-300 rounded-md p-2">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="zip" class="font-bold">Zip</label>
                        <input type="text" name="zip" id="zip" class="border border-gray-300 rounded-md p-2">
                    </div>
                </div>
            </div> -->

                        <!-- Address -->
                        <div class="flex flex-row gap-4">
                            <div class="flex flex-col gap-2">
                                <label for="address" class="font-bold">Address</label>
                                <input required type="text" name="address" id="address" class="form-input border border-gray-300 rounded-md p-2" value="<?= $alumniData['address'] ?>">
                            </div>
                        </div>


                        <!-- Add contact number -->
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap gap-4">
                                <div class="flex flex-col gap-2">
                                    <label for="contactNo" class="font-bold">Contact Number</label>
                                    <input required type="text" name="contactNo" id="contactNo" value="<?= $alumniData['contactNo'] ?>" class="border border-gray-300 rounded-md p-2" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                            </div>


                            <!-- Facebook, Instagram, Twitter, and LinkedIn Profile Input -->
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-wrap gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label for="facebook" class="font-bold">Facebook</label>
                                        <input type="text" name="facebook" id="facebook" class="border border-gray-300 rounded-md p-2" value="<?= $alumniData['facebookUN'] ?>">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="instagram" class="font-bold">Instagram</label>
                                        <input type="text" name="instagram" id="instagram" class="border border-gray-300 rounded-md p-2" value="<?= $alumniData['instagramUN'] ?>">
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-4">
                                    <div class="flex flex-col gap-2">
                                        <label for="twitter" class="font-bold">Twitter</label>
                                        <input type="text" name="twitter" id="twitter" value="<?= $alumniData['twitterUN'] ?>" class="border border-gray-300 rounded-md p-2">
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label for="linkedin" class="font-bold">LinkedIn</label>
                                        <input value="<?= $alumniData['linkedInUN'] ?>" type="text" name="linkedin" id="linkedin" class="border border-gray-300 rounded-md p-2">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- update profile button -->
                        <div class="flex flex-col gap-4 ">
                            <div class="flex flex-wrap gap-4 ">
                                <div class="flex flex-col gap-2">
                                    <button id="submitUpdateProfileBtn" type="submit" name="update-profile-account" value="update" class=" btn-primary">
                                        Update Account
                                        <!-- <span class="daisy-loading daisy-loading-spinner daisy-loading-sm"></span> -->
                                    </button>
                                    <button id="cancel-edit-profile-btn" type="button" class="daisy-btn">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </section>


                <!-- END EDIT PROFILE -->


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