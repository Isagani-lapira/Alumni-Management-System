<!-- Job Opportunities Content -->
<section class="p4  mx-auto lg:mx-8">
    <h1 class="text-xl font-extrabold">Job Opportunities </h1>
    <p class="text-grayish  ">Check all the pending job post to be posted</p>
    <div id="jobPostingBack" class="cursor-pointer flex items-center gap-4 hidden hover:bg-gray-100 my-4 py-4">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Job List
    </div>


    <section id="content-container">
        <section id="read-job-container">
            <div id="jobList">
                <div class="flex mt-5 items-center justify-between w-full">
                    <div>
                        <button id="addNewBtn" class="px-3 py-1 text-white bg-accent rounded-md hover:bg-darkAccent ">Add
                            New</button>
                        <p id="jobMyPost" class="inline cursor-pointer text-sm hover:underline hover:text-accent">My Post</p>
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
                            <th>Date Posted</th>
                            <th>Status</th>
                            <th class="rounded-tr-lg">Action</th>
                        </tr>
                    </thead>

                    <tbody class="text-sm" id="jobTBContent"></tbody>
                </table>
                <p class="hidden jobErrorMsg text-center mt-5 text-accent ">No available data yet</p>
            </div>
        </section>



        <!-- Add JOB -->
        <section id="add-job-container" class="mt-10 w-full hidden">
            <form id="create-new-job-form" enctype="multipart/form-data" method="POST">
                <input type="hidden" name="create-new" value="post">
                <div class="flex text-greyish_black">
                    <!-- left side -->
                    <div class="w-1/2">

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
                            <input type="file" id="jobLogoInput" name="jobLogoInput" class="daisy-file-input daisy-file-input-bordered daisy-file-input-primary w-full max-w-xs" />
                            <!-- <label class="bg-accent p-2 rounded-lg text-white" for="jobLogoInput">
                                Choose logo
                                <input id="jobLogoInput" name="jobLogoInput" class="jobField hidden" type="file">
                            </label>
                            <span id="jobFileName" class="mx-3 text-sm">file chosen</span> -->
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
                                <div class="relative">
                                    <i class="fa-solid fa-peso-sign absolute top-3 left-2" style="color: #727274;"></i>
                                    <input class="jobField w-full form-input pl-8" type="number" name="minSalary" id="minSalary" value="" placeholder="e.g. 10000" min="5000" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                                -
                                <div class="relative ">
                                    <!-- <i class="fa-solid fa-peso-sign" style="color: #727274;"></i> -->

                                    <i class="fa-solid fa-peso-sign absolute top-3 left-2" style="color: #727274;"></i>
                                    <input class="jobField w-full form-input pl-8" type="number" name="maxSalary" id="maxSalary" value="0" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- tags -->
                <div>
                    <label class="font-bold text-greyish_black  my-5 block" for="inputSkill">Tags</label>
                    <div id="skillDiv" class="flex flex-wrap flex-col gap-4 ">
                        <div>
                            <i class="fa-solid fa-circle-plus cursor-pointer"></i>
                            <input id="inputSkill" name="inputSkill1" class="inputSkill skillInput jobField form-input border rounded" type="text" placeholder="Add skill/s that needed">
                        </div>
                        <div>
                            <!-- <img class="h-12 w-12 inline cursor-pointer" src="../assets/icons/add-circle.png"> -->

                            <i class="fa-solid fa-circle-plus cursor-pointer"></i>
                            <input id="inputSkill2" name="inputSkill2" class="inputSkill skillInput jobField form-input border rounded" type="text" placeholder="Add skill/s that needed">
                        </div>
                        <div>
                            <!-- <img class="h-12 w-12 inline cursor-pointer" src="../assets/icons/add-circle.png"> -->
                            <i class="fa-solid fa-circle-plus cursor-pointer"></i>
                            <input id="inputSkill3" name="inputSkill3" class="inputSkill skillInput jobField form-input border rounded" type="text" placeholder="Add skill/s that needed">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 px-4">
                    <button type="reset" class="daisy-btn daisy-btn-outline">Reset</button>
                    <button type="submit" class="daisy-btn daisy-btn-secondary">Make
                        a post</button>
                </div>
            </form>
        </section>

        <!-- End Add JOB -->


    </section>


    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="view-details-modal" class="daisy-modal-toggle" />
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-5xl p-0">

            <div class=" overflow-y-auto no-scrollbar">
                <!-- content -->
                <div class="headerJob flex rounded-t-lg p-5 w-full bg-accent">
                    <div class="w-3/5 ps-3">
                        <span id="viewJobColText" class="text-2xl font-bold text-gray-200"></span>
                        <div class="flex gap-2 items-center">
                            <p class="text-sm text-white"> </p>
                            <p id="viewJobColCompany" class="text-sm font-bold text-white"></p>
                        </div>
                        <div id="skillSets" class="flex flex-wrap gap-2 text-white text-xs my-1 "></div>
                    </div>


                </div>

                <div class="p-5">
                    <h3 class="text-gray-500 font-bold text-xl">Project Overview</h3>
                    <pre id="jobOverview" class="text-sm h-max w-full indented text-gray-500 mb-2 mt-1 whitespace-normal "></pre>

                    <h3 class="text-gray-500 font-bold text-xl">Qualification</h3>
                    <pre id="jobQualification" class="whitespace-normal text-sm h-max indented text-gray-500 mb-2 mt-1"></pre>
                    <h3 class="text-gray-500 font-bold text-xl">LOCATION</h3>
                    <span id="locationJobModal" class="text-gray-500 text-sm my-1"></span>
                </div>
            </div>

            <!-- Skeleton Pulse -->
            <!-- <div class="animate-pulse flex space-x-4">
                <div class="rounded-full bg-slate-200 h-10 w-10"></div>
                <div class="flex-1 space-y-6 py-1">
                    <div class="h-2 bg-slate-200 rounded"></div>
                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="h-2 bg-slate-200 rounded col-span-2"></div>
                            <div class="h-2 bg-slate-200 rounded col-span-1"></div>
                        </div>
                        <div class="h-2 bg-slate-200 rounded"></div>
                    </div>
                </div>

            </div> -->


        </div>
        <label class="daisy-modal-backdrop" for="view-details-modal">Close</label>
    </div>


</section>


<script type="module" src="./job-opportunities/job.js"></script>