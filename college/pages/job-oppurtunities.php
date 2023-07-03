<section class="p4  mx-auto lg:mx-8">
    <img class="jobPostingBack  cursor-pointer hidden" src="/images/back.png" alt="">
    <h1 class="text-xl font-extrabold">Job Opportunities </h1>
    <p class="text-grayish  ">Check all the pending job post to be posted</p>
    <div id="jobList">
        <div class="flex mt-5 items-center justify-between w-full">
            <button id="addNewbtn" class="px-3 py-1 text-white bg-accent rounded-md hover:bg-darkAccent">Add
                New</button>
            <div class=" items-center flex">

                <input type="radio" name="post" id="rbAll" checked>
                <label class="mx-3" for="rbAll">All</label>

                <input type="radio" name="post" id="rbAdmin">
                <label class="mx-3" for="rbAdmin">Admin</label>

                <input type="radio" name="post" id="rbAlumni">
                <label class="mx-3" for="rbAlumni">Alumni</label>

                <div class="border border-accent rounded-md">
                    <img class="inline" src="/images/search-icon.png" alt="">
                    <input id="jobSearchTitle" type="text" placeholder="Search title">
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

    <div id="jobPosting" class="mt-10 hidden">
        <label class="font-bold text-grayish" for="">Job</label>
        <input class="block p-2 border border-gray-400 w-2/5 outline-none rounded-lg mb-3" type="text" placeholder="e.g Software Engineer">

        <label class="font-bold text-grayish mt-5" for="">Company Name</label>
        <input class="block p-2 border border-gray-400 w-2/5 outline-none rounded-lg mb-3" type="text" placeholder="e.g Accenture">

        <label class="font-bold text-grayish mt-5" for="">Project Overview</label>
        <div class="message-area border border-solid border-gray-400 h-40 w-2/5 rounded-lg mb-5">
            <textarea class="w-full h-full resize-none  rounded-lg p-3 focus:outline-none text-grayish" name="" id="projOverviewTxt"></textarea>
        </div>

        <label class="bg-accent p-2 rounded-lg text-white" for="jobLogoInput">
            Choose logo
            <input id="jobLogoInput" class="hidden" type="file">
        </label>
        <span id="jobFileName" class="mx-3 text-sm">file chosen</span>


        <label class="font-bold text-grayish mt-5 block" for="">Skills</label>
        <div id="skillDiv" class="flex flex-col">
            <div>
                <img class="h-12 w-12 inline cursor-pointer" src="/assets/icons/add-circle.png">
                <input class="inputSkill" type="text" placeholder="Add skill/s that needed">
            </div>
        </div>

        <label class="font-bold text-grayish mt-5" for="">Qualification</label>
        <div class="message-area border border-solid border-gray-400 h-40 w-2/5 rounded-lg mb-5">
            <textarea class="w-full h-full resize-none  rounded-lg p-3 focus:outline-none text-grayish" name="" id="qualificationTxt" placeholder="Describe the person or provide other information you want to
                  share to other people....">
                </textarea>
        </div>

        <label class="font-bold text-grayish mt-5 block" for="">Requirements</label>
        <div id="reqDiv" class="flex flex-col">
            <div>
                <img class="h-12 w-12 inline cursor-pointer" src="/assets/icons/add-circle.png" alt="">
                <input class="inputReq" type="text" placeholder="Add things that an applicants needed">
            </div>
        </div>


        <div>
            <button class="bg-postButton px-5 py-3 block ml-auto hover:bg-postHoverButton text-white rounded-md text-sm">Make
                a post</button>
        </div>
    </div>

</section>