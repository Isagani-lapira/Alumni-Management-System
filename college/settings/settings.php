<?php
session_start();
require_once('../model/PersonModel.php');
require_once("../../PHP_process/connection.php");

$colCode = $_SESSION['colCode'];

// Personal Info Data
$model = new PersonModel($mysql_con, $_SESSION['colCode']);
$alumniData = $model->getOneById($_SESSION['personID']);

// username
$username = $_SESSION['username'];

// Login Security Data

// College Profile Info 

$query = "SELECT * FROM college WHERE colCode = '$colCode';";
$result = mysqli_query($mysql_con, $query);
$data = mysqli_fetch_assoc($result);

// var_dump($data);
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



?>

<section class="container p-4">
    <h1 class="font-bold text-2xl">Account Settings</h1>
    <p class="text-gray-400 font-bold text-sm">Manage your Account and Contact Information</p>

    <div class="flex flex-wrap justify-end gap-4">

    </div>
    <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700">
    <!-- Filter  -->

    <div class="flex flex-col h-full">

        <!-- Content will be displayed here when buttons are clicked -->
        <div class="daisy-tabs" id="setting-tab-container">
            <a class="daisy-tab daisy-tab-bordered daisy-tab-active" href="#personal-info-content">Personal Information</a>
            <a class="daisy-tab daisy-tab-bordered " href="#login-security-content">Login and Security</a>
            <a class="daisy-tab daisy-tab-bordered " href="#college-profile-content">College Profile</a>
        </div>

        <div class="text-slate-600 col-span-5 p-4 " id="content-container">
            <!-- Start personal info -->
            <section id="personal-info-content" class="">
                <h2 class="font-bold text-xl mb-12">Edit Personal Information</h2>

                <form name="personal-info-form" id="personal-info-form" enctype="multipart/form-data">
                    <input type="hidden" name="personal-info-form" value="true">
                    <!-- Cover Photo -->
                    <h3 class="font-bold text-lg">Cover Photo</h3>
                    <p class="text-gray-400">Upload a cover photo to be displayed on your profile</p>


                    <!-- Placeholder for Cover Image -->
                    <div class=" h-60 relative group rounded-sm">
                        <img id="cover-img-preview" class="w-full bg-gray-100 rounded-sm object-contain max-h-full h-full block" src="
                            data:image/jpeg;base64,<?= base64_encode($alumniData['cover_photo']) ?> 
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
                                        data:image/jpeg;base64,<?= base64_encode($alumniData['profilepicture']) ?>" alt="">
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
                        <div class="flex flex-wrap gap-4 justify-end">
                            <div class="flex flex-col gap-2">
                                <button id="submitUpdateProfileBtn" type="submit" name="update-profile-account" value="update" class=" btn-primary">
                                    Update Account
                                    <!-- <span class="daisy-loading daisy-loading-spinner daisy-loading-sm"></span> -->
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </section>


            <!-- Start Login Info -->
            <section id="login-security-content" class="hidden">
                <h2 class="font-bold text-xl">Edit Account Settings</h2>
                <!-- Form for email, change password section with password and confirm password and update button  -->

                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="email" class="font-bold daisy-label font-label-text ">Username</label>
                            <div>
                                <input type="text" disabled class="border border-gray-300 rounded  p-2 form-input  daisy-input-bordered daisy-input-disabled disabled:bg-gray-100 " value="<?= $username ?>">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col gap-2">
                            <label for="email" class="font-bold daisy-label font-label-text ">Personal Email</label>
                            <div>
                                <input type="email" name="email" id="email" class="border border-gray-300 rounded-md p-2 form-input daisy-input-bordered" value="<?= $alumniData['personal_email'] ?>">
                                <button id="changeEmailButton" class="daisy-btn ">Change Personal Email</button>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="felx flex-col gap-4">
                    <div class="flex flex-col gap-2">
                        <label for="bulsuemail" class="font-bold daisy-label font-label-text ">BulSu Email</label>
                        <div>
                            <input type="email" name="bulsuemail" id="bulsuemail" class="border border-gray-300 rounded-md p-2 form-input daisy-input-bordered" value="<?= $alumniData['bulsu_email'] ?>">
                            <button id="changeEmailButton" class="daisy-btn ">Change BulSu Email</button>
                        </div>
                    </div>
                </div>




                <!-- Change password button -->
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex flex-col gap-2">

                            <p class=" font-bold daisy-label font-label-text  ">Change Password</p>
                            <label for="changePassModal" class="daisy-btn ">Change Password</label>
                        </div>
                    </div>

                </div>

                <!-- Update Button -->
                <!-- <div class="flex flex-col gap-4 ">
                    <div class="flex flex-wrap gap-4 justify-end">
                        <div class="flex flex-col gap-2">
                            <button class=" btn-primary">Update Account</button>
                        </div>
                    </div>
                </div> -->



            </section>




            <!-- start college pprofile -->
            <section id="college-profile-content" class="hidden">
                <h2 class="font-bold text-xl">Edit College Information</h2>
                <!-- Form for email, change password section with password and confirm password and update button  -->



                <!-- college form  -->
                <!-- Profile Picture -->
                <div class="flex flex-wrap justify-between gap-4 my-4">
                    <div class="flex flex-wrap gap-4">

                        <img id="colLogoPreview" class="w-24 h-24 block mx-auto rounded-full" src="
                            data:image/jpeg;base64,<?= $_SESSION['colLogo'] ?> 
                            " alt="">
                        <div class="flex flex-col justify-center">
                            <h3 class="font-bold text-lg">College Icon</h3>
                            <p class="text-gray-400">Upload the icon to be displayed on the college</p>
                        </div>

                        <div class="flex flex-col justify-center">
                            <button class="bg-slate-600 text-white px-4 py-2 rounded-full">Upload</button>
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
                            <label for="colDescription" class="font-bold">Description</label>
                            <textarea name="colDescription" id="colDescription" cols="60" rows="10" class="form-textarea border border-gray-300 rounded-md p-2"><?= $data['description'] ?></textarea>
                        </div>
                    </div>
                </div>

                <!--    add for dean image -->
                <!-- Dean Picture -->
                <div class="flex flex-wrap justify-between gap-4 my-4">
                    <div class="flex flex-wrap gap-4">

                        <img id="colLogoPreview" class="w-24 h-24 block mx-auto rounded-full" src="
                            data:image/jpeg;base64,<?= $colDeanImg ?> 
                            " alt="">
                        <div class="flex flex-col justify-center">
                            <h3 class="font-bold text-lg">College Dean Picture</h3>
                            <p class="text-gray-400">Upload the picture to be displayed on the dean of the college.</p>
                        </div>

                        <div class="flex flex-col justify-center">
                            <button class="bg-slate-600 text-white px-4 py-2 rounded-full">Upload</button>
                        </div>
                    </div>

                </div>

                <!-- add for college dean name -->
                <div class="flex flex-col gap-4">
                    <div class="flex flex-wrap gap-4 w-8/12 max-w-lg">
                        <div class="flex flex-col gap-2  w-8/12 max-w-lg">
                            <label for="colDean" class="font-bold">Dean Name</label>
                            <input type="text" value="<?= $colDean ?>" name="colDean" id="colDean" class="form-input border border-gray-300 rounded-md p-2">
                        </div>
                    </div>
                </div>



                <!-- Update Button -->
                <div class="flex flex-col gap-4 ">
                    <div class="flex flex-wrap gap-4 justify-end">
                        <div class="flex flex-col gap-2">
                            <button name="update-college" value="update-college" id="update-college-submit-btn" class=" btn-primary">Update College Profile</button>
                        </div>
                    </div>
                </div>



            </section>

        </div>


        <!-- The button to open modal -->


</section>
<input type="checkbox" id="changePassModal" class="daisy-modal-toggle" />
<div class="daisy-modal">
    <div class="daisy-modal-box">
        <h3 class="font-bold text-xl">Update Password</h3>

        <div class="flex flex-col gap-2">
            <div class="daisy-form-control w-full max-w-sm">
                <label class=" font-bold daisy-label">
                    <span class="daisy-label-text">Old Password</span>
                </label>
                <input type="password" name="old-password" id="old-password" class="form-input font-bold  daisy-input-bordered w-full">
                <!-- <input type="text"  class="daisy-input daisy-input-bordered w-full max-w-xs" /> -->
                <label class="daisy-label">
                    <span class="daisy-label-text-alt hidden">Bottom Right label</span>
                </label>
            </div>
            <div class="daisy-form-control w-full max-w-xs">
                <label class=" font-bold daisy-label">
                    <span class="daisy-label-text">New Password</span>
                </label>
                <input type="password" name="password" id="password" class="form-input font-bold  daisy-input-bordered w-full max-w-xs">
                <!-- <input type="text"  class="daisy-input daisy-input-bordered w-full max-w-xs" /> -->
                <label class="daisy-label">
                    <span class="daisy-label-text-alt"> Password must contain both upper and lowercase and special character
                    </span>
                    <span class="daisy-label-text-alt hidden">Bottom Right label</span>
                </label>
            </div>
            <div class="daisy-form-control w-full max-w-xs">
                <label for="confirmPassword" class="font-bold daisy-label">
                    <span class="daisy-label-text">Confirm Password</span>
                </label>
                <input type="password" name="confirmPassword" id="confirmPassword" class="form-input font-bold  daisy-input-bordered w-full max-w-xs">

                <label class="daisy-label">
                    <span class="daisy-label-text-alt">
                    </span>
                    <span class="daisy-label-text-alt hidden">Bottom Right label</span>
                </label>
            </div>
        </div>

        <div class="flex flex-col gap-2">


        </div>

        <div class="flex flex-col gap-2">


        </div>

        <div class="daisy-modal-action">
            <label for="changePassModal" class="daisy-btn">Close</label>
            <button class="daisy-btn">Update Password</button>
        </div>
    </div>
</div>
<script type="module" src="./settings/settings.js"></script>