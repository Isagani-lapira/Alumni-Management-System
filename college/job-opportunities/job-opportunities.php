<!-- Job Opportunities Content -->
<section class="p4  mx-auto lg:mx-8">
    <h1 class="text-xl font-extrabold">Job Opportunities </h1>
    <p class="text-grayish  ">Check all the pending job post to be posted</p>
    <img class="jobPostingBack inline cursor-pointer hidden" src="../images/back.png" alt="">

    <div id="jobList">
        <div class="flex mt-5 items-center justify-between w-full">
            <div>
                <button id="addNewbtn" class="px-3 py-1 text-white bg-accent rounded-md hover:bg-darkAccent ">Add
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
                    <th>Date Posted</th>
                    <th>Status</th>
                    <th class="rounded-tr-lg">Action</th>
                </tr>
            </thead>

            <tbody class="text-sm" id="jobTBContent"></tbody>
        </table>
        <p class="hidden jobErrorMsg text-center mt-5 text-accent ">No available data yet</p>
    </div>





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