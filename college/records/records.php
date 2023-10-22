<!-- report content -->
<section id="report-tab" class=" mx-auto lg:mx-8">
    <h1 class="text-xl font-extrabold uppercase">Records</h1>

    <div class="flex justify-end text-sm text-greyish_black">
        <!-- HISTORY LOGS -->
        <button class="p-2 m-2 border border-grayish text-grayish rounded-md font-semibold">
            Download history logs
            <img class="inline" src="/images/download.png" alt="">
        </button>

        <!-- EXPORT PDF -->
        <button class="p-2 px-4 m-2 border border-accent rounded-md 
                  bg-accent text-white hover:bg-darkAccent font-semibold">Export as PDF
        </button>

    </div>

    <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700" />

    <div class="flex flex-wrap gap-4">

        <select name="type" id="type-user" class=" form-select rounded">
            <option value="all" selected>All</option>
            <option value="student">Student</option>
            <option value="alumni">Alumni</option>
            <!-- php function on batch -->
        </select>
        <!-- search-bar -->
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-2 top-0 mt-3 text-gray-400 "></i>
            <input class="border border-greyish_black w-full rounded-md p-2 pl-7" type="text" name="" id="" placeholder="Typing!">
        </div>
        <!-- end search-bar -->
        <!-- daterangepicker input -->
        <div class="relative">
            <input type="text" name="reportdaterange" id="reportdaterange" value="01/01/2018 - 01/15/2018" class="input-text py-2 px-2">
            <i class="fa-solid fa-calendar  absolute mr-2 right-0 top-3"></i>
        </div>
        <!-- end daterangepicker input -->
        <!-- batch selection -->
        <select name="" id="batch" class=" form-select rounded ">
            <option value="" selected disabled hidden>Batch</option>
            <!-- php function on batch -->
        </select>


        <!-- college selection -->
        <select name="college" id="college" class=" form-select rounded">
            <option value="" selected disabled hidden>Course</option>
            <option value="">BS IT</option>
            <option value="">BS IS</option>
            <!-- <option value="" >BS </option> -->
        </select>

        <!-- employment status selection -->
        <select name="employment" id="employment" class=" form-select rounded">
            <option value="" selected disabled hidden>Employment Status</option>
            <option value="">Employed</option>
            <option value="">Unemployed</option>
            <option value="">Self-employed</option>
            <option value="">Student</option>
            <option value="">Retired</option>
        </select>

    </div>

    <!-- Start Record Table -->
    <table class="table-auto w-full mt-10  rounded-t-md center-shadow daisy-table daisy-table-zebra" id="record-table">
        <thead class="">
            <tr class="bg-accent text-white  rounded-tl-md">
                <th class="rounded-tl-lg">STUDENT NUMBER</th>
                <th>NAME</th>
                <th>CONTACT NUMBER</th>
                <th>BATCH YEAR</th>
                <th>EMPLOYEMENT STATUS</th>
                <th>DETAILS</th>
            </tr>
        </thead>
        <!-- To be filled later -->
        <tbody id="studentTB" class="text-sm">
        </tbody>
    </table>
    <p id="alumniNoRecMsg" class="text-center text-blue-500 hidden">No available data</p>
    <!-- End Record Table -->



</section>




<!-- modal -->
<div id="modal" class="modal fixed inset-0 h-full w-full  items-center justify-center text-grayish  top-0 left-0 hidden">
    <div class="modal-container w-1/3 h-2/3 bg-white rounded-lg p-3">
        <div class="modal-header py-5">
            <h1 class="text-accent text-2xl text-center font-bold">Create something</h1>
        </div>
        <div class="modal-body px-3">

            <!-- header part -->
            <label class="font-semibold text-sm">Header</label>
            <div class="w-full border border-gray-400 rounded flex p-3">
                <input id="inputHeader" class="inputHeader outline-none flex-auto w-full pe-3" type="text" placeholder="ex: Scholarship">
            </div>

            <!-- body part -->
            <label class="font-semibold text-sm mt-2">Description</label>
            <div class="modal-descript relative w-full h-2/3 border border-gray-400 rounded p-3">
                <textarea class="rar outline-none w-full h-full p-1" type="text" placeholder="Say something here..."></textarea>
                <label for="fileGallery">
                    <span id="galleryLogo" class="absolute bottom-1 left-1">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="blue" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 7C17 7.53043 16.7893 8.03914 16.4142 8.41421C16.0391 8.78929 15.5304 9 15 9C14.4696 9 13.9609 8.78929 13.5858 8.41421C13.2107 8.03914 13 7.53043 13 7C13 6.46957 13.2107 5.96086 13.5858 5.58579C13.9609 5.21071 14.4696 5 15 5C15.5304 5 16.0391 5.21071 16.4142 5.58579C16.7893 5.96086 17 6.46957 17 7Z" fill="#BCBCBC" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.943 0.25H11.057C13.366 0.25 15.175 0.25 
                    16.587 0.44C18.031 0.634 19.171 1.04 20.066 1.934C20.961 2.829 21.366 3.969 21.56 5.414C21.75 6.825 21.75 8.634 21.75 10.943V11.031C21.75 12.94 21.75 14.502 21.646 15.774C21.542 17.054 21.329 18.121 20.851 19.009C20.641 19.4 20.381 19.751 20.066 20.066C19.171 20.961 18.031 21.366 16.586 21.56C15.175 21.75 13.366 21.75 11.057 
                    21.75H10.943C8.634 21.75 6.825 21.75 5.413 21.56C3.969 21.366 2.829 20.96 1.934 20.066C1.141 19.273 0.731 18.286 0.514 17.06C0.299 15.857 0.26 14.36 0.252 12.502C0.25 12.029 0.25 11.529 0.25 11.001V10.943C0.25 8.634 0.25 6.825 0.44 5.413C0.634 3.969 1.04 2.829 1.934 1.934C2.829 1.039 3.969 0.634 5.414 0.44C6.825 0.25 8.634 0.25 10.943 0.25ZM5.613 1.926C4.335 2.098 3.564 2.426 2.995 2.995C2.425 3.565 2.098 4.335 1.926 5.614C1.752 6.914 1.75 8.622 1.75 11V11.844L2.751 10.967C3.1902 10.5828 3.75902 10.3799 4.34223 10.3994C4.92544 10.4189 5.47944 10.6593 5.892 11.072L10.182 15.362C10.5149 15.6948 10.9546 15.8996 11.4235 15.9402C11.8925 15.9808 12.3608 15.8547 12.746 15.584L13.044 15.374C13.5997 14.9835 14.2714 14.7932 14.9493 14.834C15.6273 14.8749 16.2713 15.1446 16.776 15.599L19.606 18.146C19.892 17.548 20.061 16.762 20.151 15.653C20.249 14.448 20.25 12.946 20.25 11C20.25 8.622 20.248 6.914 20.074 5.614C19.902 4.335 19.574 3.564 19.005 2.994C18.435 2.425 17.665 2.098 16.386 1.926C15.086 1.752 13.378 1.75 11 1.75C8.622 1.75 6.913 1.752 5.613 1.926Z" fill="#BCBCBC" />
                        </svg>
                    </span>
                </label>
                <input id="fileGallery" type="file" class="hidden" />
            </div>


        </div>

        <!-- Footer -->
        <div class="modal-footer flex items-end flex-row-reverse px-3">
            <button class="bg-accent py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
            <button class="cancel py-2 rounded px-5 text-grayish border border-slate-400 hover:bg-slate-400 hover:text-white">Cancel</button>
        </div>
    </div>
</div>


<script type="module" src="./records/record.js"></script>