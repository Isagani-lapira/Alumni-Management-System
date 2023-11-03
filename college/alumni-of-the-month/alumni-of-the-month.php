<section class="container p-4" id="alumni-of-the-month-container">
    <h1 class="font-bold text-2xl">Alumni of the Month</h1>
    <p class="text-gray-400 font-bold text-sm">Make post for alumni of the month</p>

    <div class="flex flex-wrap justify-end gap-4">
        <!-- get it later -->
        <!-- <button class="btn-tertiary">
            Export List
        </button> -->
        <!-- <button class="btn-primary" id="addAlumniBtn">
            Add Alumni
        </button> -->
        <!-- <button class="daisy-btn btn-primary " onclick="my_modal_4.showModal()">Add Alumni</button> -->
        <!-- The button to open modal -->
        <label for="add-alumni-modal" class="daisy-btn" id="add-alumni-label">Add This Month's Alumni</label>

    </div>
    <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700">
    <!-- Filter  -->
    <div class="flex flex-wrap gap-4">

        <!-- <div class="relative rounded">
            <i class="fa-solid fa-magnifying-glass absolute top-3 left-3"></i>
            <input class="pl-8 py-2 px-4 rounded border border-zinc-500" type="text" name="" id="aomSearch" placeholder="Search name">
        </div> -->

        <!-- Sex -->
        <!-- <select name="sex-option" id="" class="rounded border bg-transparent px-2 border-zinc-500">
            <option value="">All</option>
            <option value="">Male</option>
            <option value="">Female</option>
        </select> -->


        <!-- range -->
        <!-- <div class="relative">
            <input class="pr-8 rounded px-4 py-2
            border  border-zinc-500
            
            " type="text" name="aoydaterange" id="aoydaterange" value="01/01/2018 - 01/15/2018">
            <label class="absolute top-2 right-3" for="aoydaterange">
                <i class="fa-solid fa-calendar"></i>
            </label>
        </div> -->

        <!-- batch -->
        <!-- <select name="batch" id="aomBatch" class="p-1 w-64 rounded px-4 py-2 bg-transparent
            border  border-zinc-500">
            <option value="" selected="" disabled="" hidden="">Batch</option>
            <option value="">2023</option>
        </select> -->



    </div>


    <!-- End Filter Section -->
    <!-- refresh record -->
    <!-- <div class="flex justify-end gap-4 mt-4">
        <button class="btn-tertiary" id="refreshRecord">
            Refresh Record
        </button>
    </div> -->

    <!-- Add Preview of the Alumni of teh Month -->

    <h2 class="font-bold text-xl p-2 py-4">This Month's Alumni of the Month:</h2>
    <div class="border rounded-lg w-full lg:max-w-xl p-4 shadow-lg my-2 ">
        <div id="aotm-card" class="flex flex-row flex-wrap items-center gap-6 hidden">

            <div class="flex flex-wrap gap-4 ">
                <!-- image -->
                <div class=" daisy-avatar">
                    <div class="w-24 rounded-full">

                        <!-- stock photo -->
                        <img id="card-avatar" src="" alt="alumni of the month profile picture">
                    </div>
                </div>

                <!-- details -->

                <div>
                    <h3 id="card-fullname"></h3>
                    <div class="">
                        <p id="card-job" class="font-bold"></p>
                        <p>at <span class="font-italic" id="card-company"></span></p>
                        <p id="card-course"> | Batch <span class="font-bold" id="card-batch"> </span></p>
                    </div>

                </div>
            </div>
            <!-- edit buttons -->
            <div class="flex flex-wrap gap-4">
                <label for="edit-aotm" data-id="" id="card-edit" class="edit-aotm btn-primary">Edit</label>
                <label for="delete-aotm" data-id="" id="card-delete" class="delete-aotm btn-tertiary">Remove</label>
            </div>
        </div>

        <!-- if there is no alumni this month yet -->
        <div class="text-center mt-4" id="no-alumni">
            <p class="text-gray-500">There's no alumni of the month yet.</p>
        </div>

    </div>

    <!-- Add filter by year -->


    <!-- Record Table-->
    <table class="table-auto w-full mt-10 text-xs font-normal text-gray-800 rounded-t-lg bg-white" id="alumni-month-table">
        <thead class="bg-accent text-white rounded-t-lg">
            <tr class=" rounded-t-lg">
                <th class="text-start ">DATE AWARDED</th>
                <th class="text-start uppercase">STUDENT NUMBER</th>
                <th>NAME</th>
                <th>DETAILS</th>
            </tr>
        </thead>
        <tbody class="divide-y" id="tBodyRecord">

        </tbody>
    </table>
    <!-- End Record Table -->



    <!-- Modal for adding alumni of the month -->
    <input type="checkbox" id="add-alumni-modal" class="daisy-modal-toggle">
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-5xl ">
            <!-- Exit -->
            <form method="dialog">
                <label for="add-alumni-modal" class="daisy-btn daisy-btn-sm daisy-btn-circle daisy-btn-ghost absolute right-2 top-2">✕</label>
            </form>
            <!-- End Exit Form -->
            <h3 class="font-bold text-xl text-center">Add New Alumni of the Month</h3>


            <!-- make alumni of the year post -->
            <form action="" id="add-aotm-form">
                <div id="aotmRegister" class=" text-greyish_black flex flex-col px-12 s">
                    <span class="daisy-label-text">Choose a cover image to showcase</span>

                    <!-- Placeholder for Cover Image -->
                    <div class="w-full h-80 relative group rounded-sm">
                        <img id="cover-img-preview" class="w-full bg-gray-100 rounded-sm object-contain max-h-full h-full block" src="" alt="">
                        <!-- Cover Image Input -->
                        <div class="daisy-form-control w-full max-w-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <label for="cover-image" class="daisy-label">
                            </label>
                            <input class="daisy-file-input daisy-file-input-bordered w-full max-w-xs" id="cover-image" type="file" accept=".jpg" name="cover-image">
                            <label class="daisy-label">
                                <span class="daisy-label-text-alt">Use JPG File Format</span>
                            </label>
                        </div>
                    </div>

                    <!-- TODO maybe add a little radio group here if alumni is not on the database. -->
                    <label for="searchQuery" class="font-bold">Search Alumni From Your College:</label>
                    <div class="flex  flex-wrap  flex-row   text-gray-500 relative" id="searchContainer">


                        <div class="relative rounded w-full">
                            <i class="fa-solid fa-magnifying-glass absolute top-3 right-3"></i>
                            <input autocomplete="off" class="form-input rounded pr-8 w-full" type="text" name="searchQuery" id="searchQuery" placeholder="Search">
                        </div>
                        <ul class="bg-slate-100 border border-gray-100 w-full mt-2 absolute top-full inset-x-0 hidden" id="searchList">
                            <!-- TODO add some animation to this -->

                        </ul>
                    </div>
                    <div id="alumni-details" class="hidden border rounded-md p-2 m-2 space-y-2 shadow-md ">
                        <!-- Details of the alumni -->
                        <p class="font-semibold">Details of the alumni</p>
                        <div class="flex flex-wrap items-center gap-4 ">

                            <img class="rounded-full h-14 w-14- border border-accent" src="" id="detail-profile-img">
                            <!-- <p id="detail-student-id"></p> -->
                            <div class="col-span-2 ">
                                <p class="font-bold text-lg" id="detail-fullname"></p>
                                <p id="detail-personal-email"></p>
                                <p id="detail-yearGraduated"></p>
                            </div>
                        </div>

                    </div>
                    <label class="font-bold" for="quote">Quotation:</label>
                    <input id="quote" name="quote" class="form-input block rounded" type="text" placeholder="Journey of a thousand miles starts in a single step.">
                    <input id="studentId" name="studentNo" class="hidden" type="hidden">
                    <input id="personId" name="personID" class="hidden" type="hidden">


                    <p class="font-bold block" for="">Description:</p>
                    <textarea name="description" class="form-textarea block rounded resize max-w-full" id="description"></textarea>

                    <div class="flex flex-wrap gap-4 py-4 justify-end">
                        <button class="btn-tertiary bg-transparent " id="reset-aotm" type="reset">Reset Form</button>
                        <button class="btn-primary">Add Alumni of the Month</button>
                    </div>
                </div>
            </form>
            <!-- End Add FORM -->

        </div>
    </div>

    <!-- Modal for editing the alumni of the month -->
    <input type="checkbox" id="edit-aotm" class="daisy-modal-toggle">
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-5xl ">
            <!-- Exit -->
            <form method="dialog">
                <label for="edit-aotm" class="daisy-btn daisy-btn-sm daisy-btn-circle daisy-btn-ghost absolute right-2 top-2">✕</label>
            </form>
            <!-- End Exit Form -->
            <h3 class="font-bold text-xl text-center">Edit Alumni of the Month</h3>


            <form action="" id="edit-aotm-form">
                <div id="aotmEdit" class=" text-greyish_black flex flex-col px-12 s">
                    <span class="daisy-label-text">Choose a cover image to showcase</span>

                    <!-- Placeholder for Cover Image -->
                    <div class="w-full h-80 relative group rounded-sm">
                        <img id="edit-cover-img-preview" class="w-full bg-gray-100 rounded-sm object-contain max-h-full h-full block" src="" alt="">
                        <!-- Cover Image Input -->
                        <div class="daisy-form-control w-full max-w-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <label for="edit-cover-image" class="daisy-label">
                            </label>
                            <input class="daisy-file-input daisy-file-input-bordered w-full max-w-xs" id="edit-cover-image" type="file" accept=".jpg" name="cover-image">
                            <label class="daisy-label">
                                <span class="daisy-label-text-alt">Use JPG File Format</span>
                            </label>
                        </div>
                    </div>

                    <!-- TODO maybe add a little radio group here if alumni is not on the database. -->
                    <label for="edit-searchQuery" class="font-bold">Search Alumni From Your College:</label>
                    <div class="flex  flex-wrap  flex-row   text-gray-500 relative" id="searchContainer">


                        <div class="relative rounded w-full">
                            <i class="fa-solid fa-magnifying-glass absolute top-3 right-3"></i>
                            <input autocomplete="off" class="form-input rounded pr-8 w-full" type="text" name="searchQuery" id="edit-searchQuery" placeholder="Search">
                        </div>
                        <ul class="bg-slate-100 border border-gray-100 w-full mt-2 absolute top-full inset-x-0 hidden" id="edit-searchList">
                            <!-- TODO add some animation to this -->

                        </ul>
                    </div>
                    <div id="edit-alumni-details" class=" w-full max-w-md hidden border shadow-md  p-4">
                        <!-- Details of the alumni -->
                        <p class="font-semibold pb-4">Details of the alumni</p>
                        <div class="flex flex-wrap items-center gap-4  ">

                            <img class="rounded-full h-14 w-14 border border-accent" src="" id="edit-detail-profile-img">
                            <!-- <p id="detail-student-id"></p> -->
                            <div class="col-span-2 ">
                                <p class="font-bold text-lg" id="edit-detail-fullname"></p>
                                <p id="edit-detail-personal-email"></p>
                                <p id="edit-detail-yearGraduated"></p>
                            </div>
                        </div>

                    </div>

                    <label class="font-bold" for="quote">Quotation:</label>
                    <input id="edit-quote" name="quote" class="form-input block rounded" type="text" placeholder="Journey of a thousand miles starts in a single step.">
                    <input id="edit-studentId" name="studentNo" class="hidden" type="hidden">
                    <input id="edit-personId" name="personID" class="hidden" type="hidden">
                    <!-- new aotm -id -->
                    <input id="edit-aotm-id" name="aotm-id" class="hidden" type="hidden">


                    <p class="font-bold block" for="">Description:</p>
                    <textarea name="description" class="form-textarea block rounded resize max-w-full" id="edit-description"></textarea>

                    <div class="flex flex-wrap gap-4 py-4 justify-end">
                        <button class="btn-tertiary bg-transparent " id="reset-aotm" type="reset">Reset Form</button>
                        <button type="submit" name="edit-aotm-record" value="" class="btn-primary">Edit Alumni of the Month</button>
                    </div>
                </div>
            </form>
            <!-- End Add FORM -->

        </div>
    </div>



    <!-- view modal for the alumni of the month -->
    <input type="checkbox" id="view-modal" class="daisy-modal-toggle">
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-5xl ">
            <!-- Exit -->
            <form method="dialog">
                <label for="view-modal" class="daisy-btn daisy-btn-sm daisy-btn-circle daisy-btn-ghost absolute right-2 top-2">✕</label>
            </form>
            <!-- End Exit Form -->
            <h3 class="font-bold text-xl text-center">Alumni of the Month</h3>


        </div>
        <label for="view-modal" class="daisy-modal-backdrop">Close</label>
    </div>

</section>

<script type="module" src="./alumni-of-the-month/alumni.js"></script>