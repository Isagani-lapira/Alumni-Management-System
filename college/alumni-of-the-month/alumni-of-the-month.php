<section class="container p-4">
    <h1 class="font-bold text-2xl">Alumni of the Month</h1>
    <p class="text-gray-400 font-bold text-sm">Make post for alumni of the month</p>

    <div class="flex flex-wrap justify-end gap-4">
        <button class="btn-tertiary">
            Export List
        </button>
        <button class="btn-primary" id="addAlumniBtn">
            Add Alumni
        </button>
    </div>
    <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700">
    <!-- Filter  -->
    <div class="flex flex-wrap gap-4">

        <div class="relative rounded">
            <i class="fa-solid fa-magnifying-glass absolute top-3 left-3"></i>
            <input class="pl-8 py-2 px-4 rounded border border-zinc-500" type="text" name="" id="aomSearch" placeholder="Search name">
        </div>

        <!-- Sex -->
        <select name="sex-option" id="" class="rounded border bg-transparent px-2 border-zinc-500">
            <option value="">All</option>
            <option value="">Male</option>
            <option value="">Female</option>
        </select>


        <!-- range -->
        <div class="relative">
            <input class="pr-8 rounded px-4 py-2
            border  border-zinc-500
            
            " type="text" name="aoydaterange" id="aoydaterange" value="01/01/2018 - 01/15/2018">
            <label class="absolute top-2 right-3" for="aoydaterange">
                <i class="fa-solid fa-calendar"></i>
            </label>
        </div>

        <!-- batch -->
        <select name="batch" id="aomBatch" class="p-1 w-64 rounded px-4 py-2 bg-transparent
            border  border-zinc-500">
            <option value="" selected="" disabled="" hidden="">Batch</option>
            <option value="">2023</option>
        </select>



    </div>


    <!-- End Filter Section -->
    <!-- Record Table-->
    <table class="table-auto w-full mt-10 text-xs font-normal text-gray-800 rounded-t-lg">
        <thead class="bg-accent text-white rounded-t-lg">
            <tr class=" rounded-t-lg">
                <th class="text-start uppercase">Student Number</th>
                <th>NAME</th>
                <th>CONTACT NUMBER</th>
                <th>USER TYPE</th>
                <th>DETAILS</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Wade Warren</span>
                    </div>
                </td>
                <td class="text-center">09104905440</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-green-300 text-green-700">STUDENT</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>


        </tbody>
    </table>
    <!-- End Record Table -->


    <!-- Modal for adding new email -->
    <!-- modal add email message -->
    <div id="modalAlumni" class="bg-gray-800 bg-opacity-80 fixed inset-0 h-full w-full flex items-center justify-center 
      text-grayish  top-0 left-0 hidden">
        <form id="add-alumni-month-form" class="modal-container w-1/3 h-max bg-white rounded-lg p-3">
            <div class="w-full h-full">
                <div class="modal-header py-5">
                    <h1 class="text-accent text-2xl text-center font-bold">Add New Alumni</h1>
                </div>
                <div class="modal-body px-3 h-1/2">

                    <div class="relative">
                        <div id="individualEmail" class="flex border border-gray-400 w-full rounded-md p-1 hidden">
                            <img class="inline" src="../images/search-icon.png" alt="">
                            <input class="focus:outline-none w-full" type="text" name="searchEmail" id="searchEmail" placeholder="Search email!">
                        </div>
                        <p id="userNotExist" class="text-sm italic text-accent hidden">User not exist</p>
                        <div id="suggestionContainer" class="absolute p-2 w-full rounded-md z-10"></div>
                    </div>

                    <p class="font-semibold text-sm mt-2">Name</p>

                    <input class="focus:outline-none w-full border border-gray-400 rounded-md py-2 px-1" type="text" name="emailSubj" id="emailSubj" placeholder="Make a new alumni of the month!">


                </div>

                <!-- Footer -->
                <div class="modal-footer flex items-end flex-row-reverse px-3 mt-2">
                    <button type="submit" id="sendEmail" class="bg-accent h-full py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
                    <button type="button" class="cancelModal py-2 rounded px-5  hover:bg-slate-400 hover:text-white">Cancel</button>
                </div>
            </div>
        </form>
    </div>


</section>

<script>
    // Date picker
    $('#aoydaterange').daterangepicker();

    // get the script for alumni.js
    $.getScript("./alumni-of-the-month/alumni.js");
</script>