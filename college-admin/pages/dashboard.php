<!-- dashboard content -->
<div id="dashboard-tab">
    <h1 class="text-xl font-extrabold">DASHBOARD</h1>

    <button id="btnAnnouncement" style="margin-left: auto; margin-right: 10px" class="block rounded-lg font-bold text-white bg-accent p-2 hover:bg-darkAccent">
        Create Announcement
    </button>

    <div class="flex m-10 h-2/3 p-2">
        <div class="flex-1">
            <!-- welcome part -->
            <div class="relative rounded-lg h-max p-10 bg-gradient-to-r from-accent to-darkAccent">
                <img class="absolute -left-2 -top-20" src="/images/standing-2.png" alt="" srcset="" />
                <span class="block text-lg text-white text-right">
                    Welcome Back <br />
                    <span class="font-semibold text-lg">
                        Mr. Juan Dela Cruz
                    </span>
                </span>
            </div>

            <div class="flex flex-wrap columns-3 mt-2 gap-2">
                <!-- total user -->
                <div class="dash-content flex-1 rounded-lg p-2 relative border">
                    <img class="inline" src="/images/check-icon.svg" alt="" />
                    <span class="text-textColor text-xs font-bold text relative">
                        TOTAL USERS
                        <br />
                        REGISTERED</span>

                    <p class="text-accent font-bold text-4xl mt-2 relative bottom-0">55000</p>
                </div>

                <!-- Job Posting -->
                <div class="dash-content flex-1 rounded-lg p-2 relative border">
                    <span class="text-textColor text-xs font-bold"><img class="inline mr-1" src="/images/work-icon.svg" />JOB
                        POSTING</span>
                    <p class="text-accent font-bold text-4xl mt-2 absolute bottom-2">1000</p>
                </div>

                <!-- colleges -->
                <div class="dash-content flex-1 rounded-lg p-2 relative border">
                    <span class="text-textColor text-xs font-bold">
                        <img class="inline mr-1" src="/images/graduate-cap.svg" />
                        COLLEGES
                    </span>
                    <p class="text-accent font-bold text-4xl mt-2 absolute bottom-2">12</p>
                </div>

            </div>
        </div>

        <!-- chart -->
        <div class="flex-1">
            <!-- tracer status part -->
            <div class="w-80 mx-auto">
                <canvas id="myChart"></canvas>
            </div>
        </div>

    </div>

    <!-- recent announcement -->
    <div class="m-10 max-lg: relative font-semibold p-3">
        <p class=" mb-2">RECENT ANNOUNCEMENT
            <img class="inline" src="/images/pencil-box-outline.png" alt="" srcset="">
        </p>

        <div class="dash-content p-3">
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

            <!-- view more -->
            <p class="text-accent bottom-0 block text-end cursor-pointer">View more</p>
        </div>

    </div>


    <!-- Response by year -->
    <div class="m-10 max-lg: relative pb-3 font-semibold">
        <p class=" mb-2">RESPONSE BY YEAR </p>
        <div class="response_by_year">
            <canvas class="block mx-auto" style="height:40vh; width:40vw" id="responseByYear"></canvas>
        </div>

    </div>
</div>