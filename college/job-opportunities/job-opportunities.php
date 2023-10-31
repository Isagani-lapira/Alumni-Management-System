<!-- Job Opportunities Content -->
<section class="p4  mx-auto lg:mx-8">
    <h1 class="text-xl font-extrabold">Job Opportunities </h1>
    <p class="text-grayish  ">Check all the pending job post to be posted</p>
    <img class="jobPostingBack inline cursor-pointer hidden" src="../images/back.png" alt="">

    <div id="jobList">
        <div class="flex mt-5 items-center justify-between w-full">
            <div>
                <button id="addNewbtn" class="px-3 py-1 text-white bg-accent rounded-md hover:bg-darkAccent">Add
                    New</button>
                <p id="jobMyPost" class="inline cursor-pointer text-sm hover:underline hover:text-accent">My post</p>
            </div>


        </div>

        <h2 class="font-semibold my-4 text-lg">Pending Approval</h2>
        <table class="w-full mt-4 center-shadow" id="unverified-job-table">
            <thead class="bg-accent text-sm text-white p-3">
                <tr>
                    <th class="rounded-tl-lg">Job</th>
                    <th>Posted By</th>
                    <th>Date Posted</th>
                    <th class="rounded-tr-lg">Action</th>
                </tr>
            </thead>

            <tbody class="text-sm" id="jobTBContent"></tbody>
        </table>


        <div class="  ">
            <label for="filter" class="daisy-label daisy-label-text">Filter By: </label>
            <select name="filter" id="" class=" form-select rounded">
                <option value="all" selected>All</option>
                <option value="admin">Admin</option>
                <option value="alumni">Alumni</option>
            </select>

        </div>
        <table class="w-full mt-10 center-shadow" id="jobTable">
            <thead class="bg-accent text-sm text-white p-3">
                <tr>
                    <th class="rounded-tl-lg">Company</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>College</th>
                    <th>Date Posted</th>
                    <th class="rounded-tr-lg">Action</th>
                </tr>
            </thead>

            <tbody class="text-sm" id="jobTBContent"></tbody>
        </table>
        <p class="hidden jobErrorMsg text-center mt-5 text-accent ">No available data yet</p>
    </div>

    <!-- job posting -->
    <div id="jobPosting" class="mt-10 w-full hidden">
        <form id="jobForm" enctype="multipart/form-data">
            <div class="flex text-greyish_black">
                <!-- left side -->
                <div class="w-1/2">

                    <!-- college -->
                    <div class="mb-3">
                        <label for="collegeJob" class="font-bold text-greyish_black block">College</label>
                        <!-- college selection -->
                        <select name="collegeJob" id="collegeJob" class=" border border-grayish p-2 rounded-lg w-4/5 outline-none text-gray-400">
                            <option value="" selected disabled hidden>All</option>
                            <?php
                            // require_once './php/connection.php';
                            // $query = "SELECT * FROM `college`";
                            // $result = mysqli_query($mysql_con, $query);
                            // $rows = mysqli_num_rows($result);

                            // if ($rows > 0) {
                            //     while ($data = mysqli_fetch_assoc($result)) {
                            //         $colCode = $data['colCode'];
                            //         $colName = $data['colname'];

                            //         echo '<option value="' . $colCode . '">' . $colName . '</option>';
                            //     }
                            // } else echo '<option>No college available</option>';
                            ?>
                        </select>
                    </div>

                    <!-- job title -->
                    <div>
                        <label class="font-bold text-greyish_black" for="jobTitle">Job Title</label>
                        <input id="jobTitle" name="jobTitle" class="jobField block p-2 border border-gray-400 w-4/5 outline-none rounded-lg mb-3" type="text" placeholder="e.g Software Engineer">
                    </div>

                    <!-- job description -->
                    <div>
                        <label class="font-bold text-greyish_black mt-5" for="projOverviewTxt">Project Description</label>
                        <textarea class="block message-area jobField border border-solid border-gray-400 h-40 w-4/5 mb-5 resize-none  
                      rounded-lg focus:outline-none text-greyish_black text-sm p-3" name="projDescriptTxt" id="projOverviewTxt" placeholder="Describe the person or provide other information you want to share to other people...."></textarea>
                    </div>

                    <!-- company logo -->
                    <div>
                        <label class="font-bold text-greyish_black mt-5 block my-2" for="projOverviewTxt">Company Logo</label>
                        <label class="bg-accent p-2 rounded-lg text-white" for="jobLogoInput">
                            Choose logo
                            <input id="jobLogoInput" name="jobLogoInput" class="jobField hidden" type="file">
                        </label>
                        <span id="jobFileName" class="mx-3 text-sm">file chosen</span>
                    </div>

                </div>

                <!-- right side -->
                <div class="w-1/2">

                    <!-- company name -->
                    <div>
                        <label class="font-bold text-greyish_black text-sm mt-5" for="jobCompany">Company Name</label>
                        <input id="jobCompany" name="companyName" class="jobField block p-2 border border-gray-400 w-4/5 outline-none rounded-lg mb-3" type="text" placeholder="e.g Accenture">
                    </div>

                    <!-- location -->
                    <div>
                        <label class="font-bold text-greyish_black text-sm mt-5" for="jobLocation">Location</label>
                        <input id="jobLocation" name="jobLocation" class="jobField block p-2 border border-gray-400 w-4/5 outline-none rounded-lg mb-3" type="text" placeholder="e.g Accenture">
                    </div>

                    <!-- job qualification -->
                    <div>
                        <label class="font-bold text-greyish_black text-sm mt-5" for="qualificationTxt">Qualification</label>
                        <textarea class="jobField block message-area border border-solid border-gray-400 h-40 w-4/5 rounded-lg mb-5
                      resize-none p-3 focus:outline-none text-greyish_black text-sm" name="qualificationTxt" id="qualificationTxt"></textarea>
                    </div>

                    <!-- salary -->
                    <div>
                        <label class="font-bold text-greyish_black text-sm mt-5 block" for="minSalary">Salary Range</label>
                        <div class="flex gap-2 mt-2 items-center">
                            <div class="w-1/4 p-2 border border-grayish rounded-md flex items-center gap-2">
                                <i class="fa-solid fa-peso-sign" style="color: #727274;"></i>
                                <input class="jobField w-full" type="number" name="minSalary" id="minSalary" value="0">
                            </div>
                            -
                            <div class="w-1/4 p-2 border border-grayish rounded-md flex items-center gap-2">
                                <i class="fa-solid fa-peso-sign" style="color: #727274;"></i>
                                <input class="jobField w-full" type="number" name="maxSalary" id="maxSalary" value="0">
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- tags -->
            <div>
                <label class="font-bold text-greyish_black text-sm mt-5 block" for="inputSkill">Tags</label>
                <div id="skillDiv" class="flex flex-wrap">
                    <div>
                        <img class="h-12 w-12 inline cursor-pointer" src="../assets/icons/add-circle.png">
                        <input id="inputSkill" class="inputSkill skillInput" type="text" placeholder="Add skill/s that needed">
                    </div>
                </div>
            </div>

            <div class="flex justify-start">
                <button type="submit" class="bg-postButton px-4 py-2 mt-5 hover:bg-postHoverButton text-white rounded-md text-sm">Make
                    a post</button>
            </div>
        </form>
    </div>

    <!-- admin job post -->
    <div id="adminJobPost" class="mt-10 w-full hidden">
        <div id="adminJobPostCont" class="grid grid-cols-4 gap-4 p-7"></div>
    </div>

    <!-- View job post modal -->
    <div id="viewJob" class="modal fixed inset-0 h-full w-full flex items-start justify-center 
        top-0 left-0 p-5 hidden overflow-y-auto">
        <!-- modal body -->
        <div class="w-2/5 bg-white rounded-lg h-max p-5">
            <!-- content -->
            <div class="headerJob flex">
                <img id="jobCompanyLogo" class="h-20 w-20 inline" src="" alt="">
                <div class="w-3/5 ps-3">
                    <span id="viewJobColText" class="text-lg font-semibold"></span>
                    <div class="flex items-center">
                        <p class="text-sm text-gray-600 pr-1">Posted by: </p>
                        <p id="viewJobAuthor" class="text-sm font-semibold text-green-500"></p>
                    </div>

                    <div class="flex items-center">
                        <p class="text-sm text-gray-600 pr-1">Company Name: </p>
                        <p id="viewJobColCompany" class="text-sm text-green-500 font-semibold">Admin</p>
                    </div>

                    <p id="viewPostedDate" class="text-sm text-gray-600 pr-1"></p>
                </div>

            </div>
            <hr class="p-1 border-gray-400 mt-5">


            <p class="text-black font-bold my-3">Project Overview</p>
            <p id="jobOverview" class="text-sm h-max w-full text-gray-600"></p>

            <p class="text-black font-bold text-sm my-3">Skills</p>
            <div id="skillSets" class="flex flex-wrap gap-2 text-gray-600 text-sm"></div>

            <p class="text-black font-bold my-3">Qualification</p>
            <p id="jobQualification" class="text-sm h-max text-gray-600"></p>


            <p class="text-black font-bold my-3">REQUIREMENTS</p>
            <div id="reqCont" class="text-gray-600 text-sm"></div>

            <button class="bg-green-400 text-white px-8 py-3 mt-5 rounded-md block ml-auto">Apply Now</button>
        </div>
    </div>



</section>


<script type="module" src="./job-opportunities/job.js"></script>