<section class="p4  mx-auto lg:mx-8">
    <h1 class="text-xl font-extrabold">Job Opportunities </h1>
    <p class="text-grayish  ">Check all the pending job post to be posted</p>

    <div id="jobList">
        <div class="flex mt-5 items-center justify-between w-full">
            <button id="addNewBtn" class="px-3 py-1 text-white bg-accent rounded-md hover:bg-darkAccent">Add
                New</button>
            <div class=" items-center flex">

                <select name="" id="">
                    <option value="">All</option>
                    <option value="">Admin</option>
                    <option value="">Alumni</option>
                </select>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-2 top-3 text-gray-500"></i>
                    <input id="jobSearchTitle" type="text" placeholder="Search title " class="rounded  pl-8">

                </div>
            </div>

        </div>

        <table class="w-full mt-5">
            <thead class="bg-accent text-sm text-white p-3">
                <tr>
                    <th>Company Logo</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>College</th>
                    <th>Date Posted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-sm">

                <tr>
                    <td>
                        <img class="w-28" src="/assets/company-logo/meralco.png" alt="">
                    </td>
                    <td>Backend Developer</td>
                    <td>Admin</td>
                    <td>CICT</td>
                    <td>04/10/25</td>
                    <td>
                        <button class="viewJobModal bg-blue-400 hover:bg-blue-500 text-white py-1 px-3 rounded-md">View</button>
                    </td>
                </tr>


                <tr>
                    <td>
                        <img class="w-28" src="/assets/company-logo/PNA.png" alt="">
                    </td>
                    <td>Health play specialist</td>
                    <td>Ma. Cristine Legerin</td>
                    <td>CON</td>
                    <td>04/10/25</td>
                    <td>
                        <button class="viewJobModal bg-blue-400 hover:bg-blue-500 text-white py-1 px-3 rounded-md">View</button>
                    </td>
                </tr>


                <tr>
                    <td>
                        <img class="w-28" src="/assets/company-logo/vircon.png" alt="">
                    </td>
                    <td>Accountant</td>
                    <td>Admin</td>
                    <td>CBA</td>
                    <td>04/10/25</td>
                    <td>
                        <button class="viewJobModal bg-blue-400 hover:bg-blue-500 text-white py-1 px-3 rounded-md">View</button>
                    </td>
                </tr>


                <tr>
                    <td>
                        <img class="w-28" src="/assets/company-logo/IA.png" alt="">
                    </td>
                    <td>Interior and spatial designer</td>
                    <td>James Matsugi</td>
                    <td>CAFA</td>
                    <td>04/10/25</td>
                    <td>
                        <button class="viewJobModal bg-blue-400 hover:bg-blue-500 text-white py-1 px-3 rounded-md">View</button>
                    </td>
                </tr>


                <tr>
                    <td>
                        <img class="w-28" src="/assets/company-logo/privateChef.png" alt="">
                    </td>
                    <td>Private Chef</td>
                    <td>Dennice Malengco</td>
                    <td>CHTM</td>
                    <td>04/10/25</td>
                    <td>
                        <button class="viewJobModal bg-blue-400 hover:bg-blue-500 text-white py-1 px-3 rounded-md">View</button>
                    </td>
                </tr>


            </tbody>
        </table>

    </div>

    <!-- JOB POSTING  -->
    <!-- Shows when Add new was clicked -->
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
                            require_once '../../../PHP_process/connection.php';
                            $query = "SELECT * FROM `college`";
                            $result = mysqli_query($mysql_con, $query);
                            $rows = mysqli_num_rows($result);

                            if ($rows > 0) {
                                while ($data = mysqli_fetch_assoc($result)) {
                                    $colCode = $data['colCode'];
                                    $colName = $data['colname'];

                                    echo '<option value="' . $colCode . '">' . $colName . '</option>';
                                }
                            } else echo '<option>No college available</option>';
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
                            <div class="relative w-1/4 rounded-md">
                                <!--  w-1/4 p-2 border border-grayish rounded-md flex items-center gap-2-->
                                <i class="absolute left-2 top-3 fa-solid fa-peso-sign" style="color: #727274; "></i>
                                <input class="rounded-md jobField w-full pl-8" type="number" name="minSalary" id="minSalary" value="0">
                            </div>
                            -
                            <div class="relative w-1/4 ">
                                <!-- w-1/4 p-2 border border-grayish rounded-md flex items-center gap-2 -->
                                <i class="absolute left-2 top-3 fa-solid fa-peso-sign" style="color: #727274;"></i>
                                <input class="rounded-md pl-8 jobField w-full" type="number" name="maxSalary" id="maxSalary" value="0">
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



            <div class="flex justify-between mt-5">
                <!-- Button to get back -->
                <button class="jobPostingBack hidden btn-tertiary" type="button">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </button>
                <button type="submit" class="bg-postButton px-4 py-2  hover:bg-postHoverButton text-white rounded-md text-sm">Make
                    a post</button>
            </div>

        </form>

    </div>




</section>



<script>
    console.log('whaaat');
    $(document).ready(function() {

        // Handle job posting shows
        $('#addNewBtn').on("click", function() {
            console.log('hello');
            $('#jobPosting').show();
            $('#jobList').hide();
            $('.jobPostingBack').show();
        })

        //show the default job posting content
        $('.jobPostingBack').click(function() {
            $('#jobPosting').hide();
            $('#jobList').show();
            $('.jobPostingBack').hide();
            $('#adminJobPost').hide();
        })

    });
</script>