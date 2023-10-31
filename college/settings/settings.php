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

        <div class="text-slate-600 col-span-5 p-4 " id="content-container">
            <!-- Start personal info -->

            <!-- Start Login Info -->
            <section id="login-security-content" class="">
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