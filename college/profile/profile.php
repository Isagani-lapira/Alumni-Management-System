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
} else {
    echo "<h1>" . "No person ID" .  "</h1>";
    die();
}

?>
<section>

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


<script>
    $(document).ready(function() {
        $.getScript("./profile/postScript.js");
    });
</script>