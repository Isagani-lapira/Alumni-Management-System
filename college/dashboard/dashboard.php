<!-- dashboard content -->
<?php session_start(); ?>

<section id="dashboard-tab" class="container lg:mx-auto">



    <div class="flex m-10 h-2/3  flex-wrap gap-4">
        <!-- Left Welcome Part -->
        <div class="flex-1">
            <!-- Welcome Card -->
            <div class="relative rounded-lg h-max p-10 bg-gradient-to-r from-accent to-darkAccent">
                <img class="absolute -left-2 -top-20" src="/images/standing-2.png" alt="" srcset="" />
                <span class="block text-lg text-white text-right">
                    Welcome Back <br />
                    <span class="font-semibold text-lg">
                        <?= $_SESSION['fullName']; ?>
                    </span>
                </span>
            </div>
            <!-- quick stat cards -->
            <div class="flex flex-wrap columns-3 mt-2 gap-2">
                <!-- total user -->
                <div class="dash-content flex-1 rounded-lg p-4 relative border uppercase shadow-xl">
                    <i class="bi bi-check-circle-fill"></i>
                    <span class="text-textColor text-xs font-bold text relative ">
                        total users registered
                    </span>

                    <div class="text-2xl text-gray-400 font-bold">
                        <div>
                            <span class="text-accent font-bold text-4xl mt-2 relative bottom-0">
                                <?php
                                require_once('../model/Student.php');
                                require_once('../model/AlumniModel.php');

                                require_once('../php/connection.php');
                                $student = new Student($mysql_con);
                                $alumni = new AlumniModel($mysql_con, $_SESSION['colCode']);
                                $studentCount = $student->getTotalCount(
                                    $_SESSION['colCode']
                                );
                                $alumniCount = $alumni->getTotalCount();

                                echo ($alumniCount + $studentCount);
                                ?>

                            </span>
                            <!-- <span>/10,000</span> -->
                        </div>
                        <div class=""><span class="font-extrabold">
                                <?= $studentCount ?>
                            </span> <span class="font-normal capitalize text-lg">students</span></div>
                        <div class="font-normal capitalize"><span class="font-extrabold">
                                <?= $alumniCount ?>

                            </span> <span class="font-normal capitalize text-lg">alumni</span> </div>
                    </div>
                </div>
                <!-- new users -->
                <div class="dash-content flex-1 rounded-lg p-4 relative border uppercase shadow-xl">
                    <span class="text-textColor text-xs font-bold">
                        <i class="bi bi-people-fill"></i>
                        new users this month
                    </span>
                    <p class="text-accent font-bold text-4xl mt-2 absolute bottom-2">50</p>
                </div>

            </div>
        </div>
        <!-- end quick stats -->
        <!-- End Left Welcome Part -->

        <!-- Start recent activities -->
        <div class="flex-1 flex justify-end">
            <div class=" max-lg:relative  lg:w-4/5  font-semibold  border  center-shadow p-5 rounded-lg">
                <p class="  text-accent font-bold">RECENT ACTIVITIES
                    <img class="inline" src="/images/pencil-box-outline.png" alt="" srcset="">
                </p>
                <?php
                require_once('../php/connection.php');
                require_once('../php/logging.php');
                $logs = getRecentCollegeAcivity($mysql_con, $_SESSION['adminID']);
                ?>
                <div class="flex flex-col items-start gap-2">
                    <?php foreach ($logs as  $item) : ?>
                        <div class="recent-announcement  flex justify-stretch actionWrapper items-center">
                            <img class="circle rounded-full bg-gray-400 p-5 h-10 w-10"></img>
                            <div class="text-sm ms-2 ">
                                <p class=" text-gray-600">
                                    <span class="font-extrabold "></span>
                                    <?= ucwords($item['details']) ?>
                                    <span class=" text-white font-semibold p-2 rounded-md">
                                        <?= ucwords($item['action']) ?>
                                    </span>
                                </p>
                                <span class="text-gray-500 font-light "><?= date("F j, Y, \a\t g:i a", strtotime($item['timestamp'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <!-- Open the modal using ID.showModal() method -->
                <div class="flex justify-end">
                    <button class="text-sm text-accent font-semibold mt-3 text-end cursor-pointer" onclick="viewMoreModal.showModal()">View More</button>
                </div>
                <dialog id="viewMoreModal" class="daisy-modal">
                    <div class="daisy-modal-box">
                        <h3 class="font-bold text-lg">Hello!</h3>
                        <p class="py-4">Press ESC key or click the button below to close</p>
                        <div class="daisy-modal-action">
                            <form method="dialog">
                                <!-- if there is a button in form, it will close the modal -->
                                <button class="daisy-btn">Close</button>
                            </form>
                        </div>

                    </div>
                </dialog>
            </div>
        </div>
    </div>
    <!-- End recent-announcement -->

    <!-- Middle Part -->
    <div class="flex flex-wrap m-10">
        <!-- Response by year -->
        <div class="flex-1 max-lg: relative pb-3 font-semibold">
            <p class=" mb-2 text-subheading">RESPONSE BY YEAR </p>
            <div class="w-[40rem] h-96">
                <canvas class="" id="responseByYear"></canvas>
            </div>

        </div>
        <!-- End Response by Year -->

        <!-- Start Non Chart Information -->
        <div class="flex-1 flex flex-col lg:items-end">
            <div class="border w-4/5">
                <div class=" p-5 rounded-lg ms-3">
                    <p class="text-accent font-semibold">TRACER STATUS </p>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                        <p class="font-normal text-greyish_black">Already Answered</p>
                        <span class="text-accent">73%</span>
                    </div>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                        <p class="font-normal text-greyish_black">Haven't answer yet</p>
                        <span class="text-accent">27%</span>
                    </div>
                </div>
                <div class="w-4/5 p-5 rounded-lg ms-3">
                    <p class="text-accent font-semibold">Personal Logs</p>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                        <p class="font-normal text-greyish_black">Total no. of posted announcement</p>
                        <span class="text-accent">10</span>
                    </div>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                        <p class="font-normal text-greyish_black">Total no. of deleted post</p>
                        <span class="text-accent">0</span>
                    </div>
                    <div class=" flex justify-between px-2 py-1 text-sm">
                        <p class="font-normal text-greyish_black">Total no. of posted job</p>
                        <span id="noPostedJob" class="text-accent"></span>
                    </div>
                </div>
            </div>

        </div>
        <!-- End Non Chart Information -->
    </div>
    <!-- End Middle Part -->

</section>

<script type="module" src="./dashboard/dashboard.js"></script>