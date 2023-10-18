<!-- dashboard content -->
<?php session_start();
?>

<section id="dashboard-tab" class="container">



    <div class="flex m-10 h-2/3 p-2 flex-wrap gap-4">
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

        <!-- recent announcement -->
        <div class=" max-lg: relative font-semibold  border  center-shadow p-5 rounded-lg">
            <p class="  text-accent font-bold">RECENT ACTIVITIES
                <img class="inline" src="/images/pencil-box-outline.png" alt="" srcset="">
            </p>
            <?php
            require_once('../php/connection.php');
            require_once('../php/logging.php');

            $logs = getRecentCollegeAcivity($mysql_con, $_SESSION['adminID']);




            ?>
            <?php foreach ($logs as  $item) : ?>
                <div class="recent-announcement flex justify-stretch my-5">
                    <div class="circle rounded-full bg-gray-400 p-5"></div>
                    <div class="text-sm ms-2 font-extralight">
                        <p class="">
                            <span class="font-extrabold text-black"></span>
                            <?= $item['details'] ?>
                            <span class="bg-yellow-300 text-white font-semibold p-2 rounded-md">
                                <?= $item['action'] ?>
                            </span>
                        </p>
                        <span class="text-grayish text-xs"></span>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- <div class="dash-content p-3 pt-0   rounded-md">
                <div class="recent-announcement flex justify-stretch my-5">
                    <div class="circle rounded-full bg-gray-400 p-5"></div>
                    <div class="text-sm ms-2 font-extralight">
                        <p class="text-grayish"><span class="font-extrabold text-black">CICT</span> added a post
                            <span class="bg-yellow-300 text-white font-semibold p-2 rounded-md">POST</span>
                        </p>
                        <span class="text-grayish text-xs">AUGUST 9, 8:30PM</span>
                    </div>
                </div>

                <div class="recent-announcement flex justify-stretch my-5">
                    <div class="circle rounded-full bg-red-400 p-5"></div>
                    <div class="text-sm ms-2 font-extralight">
                        <p class="text-grayish"><span class="font-extrabold text-black">COE</span> added a new announcement
                            <span class="bg-green-600 text-white font-semibold p-2 rounded-md">ANNOUNCEMENT</span>
                        </p>
                        <span class="text-grayish text-xs">AUGUST 9, 8:30PM</span>
                    </div>
                </div>

                <div class="recent-announcement flex justify-stretch my-5">
                    <div class="circle rounded-full bg-yellow-200 p-5"></div>
                    <div class="text-sm ms-2 font-extralight">
                        <p class="text-grayish"><span class="font-extrabold text-black">COE</span> added a new announcement
                            <span class="bg-violet-400 text-white font-semibold p-2 rounded-md">UPDATE</span>
                        </p>
                        <span class="text-grayish text-xs">AUGUST 9, 8:30PM</span>
                    </div>
                </div>

                 view more -->
            <p class="text-accent bottom-0 block text-end cursor-pointer">View more</p>
        </div>
    </div>
    <!-- End recent-announcement -->




    </div>


    <!-- Middle Part -->
    <div class="flex flex-wrap m-10">
        <!-- Response by year -->
        <div class=" max-lg: relative pb-3 font-semibold">
            <p class=" mb-2 text-subheading">RESPONSE BY YEAR </p>
            <div class="w-[40rem] h-96">
                <canvas class="" id="responseByYear"></canvas>
            </div>

        </div>
        <!-- Start Non Chart Information -->
        <div class="flex-1">
            <div>
                <div class="w-4/5 p-5 rounded-lg ms-3">
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
            </div>

            <div>
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