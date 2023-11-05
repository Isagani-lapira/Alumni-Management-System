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
    <div class="border rounded-lg w-full lg:max-w-xl p-4 shadow-lg my-2 min-h-16 ">
        <div id="aotm-card" class="flex flex-row flex-wrap items-center gap-6 hidden">

            <div class="flex flex-wrap gap-4 ">
                <!-- image -->
                <div class=" daisy-avatar">
                    <div class="w-24 h-24 rounded-full">

                        <!-- stock photo -->
                        <img loading="lazy" id="card-avatar" src="" alt="alumni of the month profile picture">
                    </div>
                </div>

                <!-- details -->

                <div>
                    <h3 id="card-fullname" class="font-bold"></h3>
                    <div class="">
                        <p id="card-job" class="font-bold"></p>
                        <!-- <p>at <span class="font-italic" id="card-company"></span></p> -->
                        <p> <span id="card-course"></span> </p>
                        <p> Batch <span class="" id="card-batch"> </span></p>
                    </div>

                </div>
            </div>
            <!-- edit buttons -->
            <div class="flex flex-wrap gap-4">
                <label for="edit-aotm" data-id="" data-aotm-id="" id="card-edit" class="edit-aotm-btn daisy-btn-primary daisy-btn">Edit</label>
                <label for="delete-aotm" data-id="" id="card-delete" class="delete-aotm daisy-btn daisy-btn-outline daisy-btn-warning">Remove</label>
            </div>
        </div>

        <!-- if there is no alumni this month yet -->
        <div class="text-center mt-4  hidden" id="no-alumni">
            <p class="text-gray-500">There's no alumni of the month yet.</p>
        </div>

        <!-- loading spinner -->
        <div id="card-loading" class="flex items-center justify-center w-full h-full"><span class="daisy-loading daisy-loading-spinner daisy-loading-lg"></span></div>

    </div>

    <!-- Add filter by year -->


    <!-- Record Table-->
    <table class="table-auto w-full mt-10 daisy-table daisy-table-zebra font-normal text-gray-800 rounded-t-lg bg-white" id="alumni-month-table">
        <thead class="bg-accent text-white rounded-t-lg text-center">
            <tr class=" rounded-t-lg">
                <th class="text-start ">DATE AWARDED</th>
                <th class="text-start uppercase">STUDENT NUMBER</th>
                <th>NAME</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody class="divide-y text-left" id="tBodyRecord">

        </tbody>
    </table>
    <!-- End Record Table -->



    <!-- Modal for adding alumni of the month -->
    <input type="checkbox" id="add-alumni-modal" class="daisy-modal-toggle">
    <div class="daisy-modal">
        <div class="daisy-modal-box w-10/12 max-w-full">
            <!-- Exit -->
            <form method="dialog">
                <label for="add-alumni-modal" class="daisy-btn daisy-btn-sm daisy-btn-circle daisy-btn-ghost absolute right-2 top-2">✕</label>
            </form>
            <!-- End Exit Form -->

            <h3 class="font-bold text-xl text-center">Add New Alumni of the Month</h3>

            <!-- make alumni of the year post -->
            <form action="" id="add-aotm-form" class="flex flex-row  divide-x flex-wrap ">

                <input id="add-aotm-username" type="hidden" name="username" value="">
                <div id="aotmRegister" class=" text-greyish_black flex flex-col px-12 space-y-4 flex-1 overflow-auto min-w-fit">
                    <div>
                        <span class="daisy-label-text daisy-label font-bold text-base">Choose a cover image to showcase</span>
                        <!-- Placeholder for Cover Image -->
                        <div class="w-full h-80 relative group rounded-sm">
                            <img loading="lazy" id="cover-img-preview" class="w-full bg-gray-100 rounded-sm object-contain max-h-full h-full block" src="" alt="">
                            <!-- Cover Image Input -->
                            <div class="daisy-form-control w-full max-w-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <label for="cover-image" class="daisy-label">
                                </label>
                                <input required class="daisy-file-input daisy-file-input-bordered w-full max-w-xs" id="cover-image" type="file" accept=".jpg" name="cover-image">
                                <label class="daisy-label">
                                    <span class="daisy-label-text-alt">Use JPG File Format</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
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
                    </div>


                    <div id="alumni-details" class="hidden border rounded-md p-2 m-2 space-y-2 shadow-md ">
                        <!-- Details of the alumni -->
                        <p class="font-semibold">Alumni Detail</p>
                        <div class="flex flex-wrap items-center gap-4 ">

                            <img loading="lazy" class="rounded-full h-14 w-14 border border-accent" src="" id="detail-profile-img">
                            <!-- <p id="detail-student-id"></p> -->
                            <div class="col-span-2 ">
                                <p class="font-bold text-lg" id="detail-fullname"></p>
                                <p id="detail-personal-email"></p>
                                <p id="detail-yearGraduated"></p>
                            </div>
                        </div>

                    </div>

                    <div class="w-full">
                        <label class="font-bold" for="quote">Quotation:</label>
                        <input id="quote" name="quote" class="form-input block rounded w-full" type="text" placeholder="Journey of a thousand miles starts in a single step.">
                        <input id="studentId" name="studentNo" class="hidden" type="hidden">
                        <input id="personId" name="personID" class="hidden" type="hidden">
                    </div>

                    <div>

                        <p class="font-bold block" for="">Description:</p>
                        <textarea name="description" class="form-textarea block rounded resize max-w-full" id="description"></textarea>
                    </div>


                    <div class="border space-y-4 p-4">
                        <h3 class="font-bold">ACHIEVEMENTS:</h3>

                        <div id="achievementFields" class="grid grid-cols-3 gap-4">


                        </div>

                        <button type="button" id="add-achievement-btn" class="daisy-btn">Add Achievement</button>
                    </div>



                    <div class="border space-y-4 p-4">
                        <h3 class="font-bold">TESTIMONIES:</h3>

                        <div id="testimonyFields" class="flex flex-row gap-4">


                        </div>

                        <button type="button" id="add-testimony-btn" class="daisy-btn">Add Testimony</button>
                    </div>






                </div>
                <div class="flex flex-wrap flex-col justify-end">
                    <div class="flex flex-col flex-wrap gap-4 p-4 justify-end">
                        <button class="daisy-btn bg-transparent " id="reset-aotm" type="reset">
                            <i class="fa-solid fa-arrow-rotate-right"></i> Reset Form</button>
                        <button class="daisy-btn daisy-btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Add Alumni of the Month
                        </button>
                    </div>
                </div>
            </form>
            <!-- End Add FORM -->

        </div>
    </div>

    <!-- Modal for editing the alumni of the month -->
    <input type="checkbox" id="edit-aotm" class="daisy-modal-toggle">
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-7xl ">
            <!-- Exit -->
            <form method="dialog">
                <label for="edit-aotm" class="daisy-btn daisy-btn-sm daisy-btn-circle daisy-btn-ghost absolute right-2 top-2">✕</label>
            </form>
            <!-- End Exit Form -->
            <h3 class="font-bold text-xl text-center">Edit Alumni of the Month</h3>


            <form action="" id="edit-aotm-form" class="flex flex-row flex-wrap divide-x">
                <div id="aotmEdit" class="flex-1 text-greyish_black flex flex-col px-12 space-y-4">
                    <input id="edit-aotm-username" type="hidden" name="username" value="">


                    <div>
                        <!-- Placeholder for Cover Image -->
                        <span class="daisy-label-text">Choose a cover image to showcase</span>
                        <div class="w-full h-80 relative group rounded-sm">
                            <img loading="lazy" id="edit-cover-img-preview" class="w-full bg-gray-100 rounded-sm object-contain max-h-full h-full block" src="" alt="">
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
                    </div>

                    <div>
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
                    </div>

                    <div id="edit-alumni-details" class=" w-full max-w-md hidden border shadow-md  p-4">
                        <!-- Details of the alumni -->
                        <p class="font-semibold pb-4">Alumni Detail</p>
                        <div class="flex flex-wrap items-center gap-4  ">

                            <img loading="lazy" class="rounded-full h-14 w-14 border border-accent" src="" id="edit-detail-profile-img">
                            <!-- <p id="detail-student-id"></p> -->
                            <div class="col-span-2 ">
                                <p class="font-bold text-lg" id="edit-detail-fullname"></p>
                                <p id="edit-detail-personal-email"></p>
                                <p id="edit-detail-yearGraduated"></p>
                            </div>
                        </div>

                    </div>

                    <div>
                        <label class="font-bold" for="quote">Quotation:</label>
                        <input id="edit-quote" required name="quote" class="form-input block rounded w-full" type="text" placeholder="Journey of a thousand miles starts in a single step.">
                        <input id="edit-studentId" name="studentNo" class="hidden" type="hidden">
                        <input id="edit-personId" name="personID" class="hidden" type="hidden">
                        <!-- new aotm -id -->
                        <input id="edit-aotm-id" name="aotm-id" class="hidden" type="hidden">
                    </div>

                    <div>

                        <p class="font-bold block" for="">Description:</p>
                        <textarea name="description" required class="form-textarea block rounded resize max-w-full" id="edit-description"></textarea>
                    </div>

                    <div class="border rounded p-4">
                        <p class="font-bold">ACHIEVEMENTS:</p>
                        <div id="edit-achievementFields" class="grid grid-cols-3 gap-4">
                        </div>

                        <button type="button" id="edit-add-achievement-btn" class="daisy-btn">Add Achievement</button>
                    </div>


                    <div class="border space-y-4 p-4">
                        <h3 class="font-bold">TESTIMONIES:</h3>

                        <div id="edit-testimonyFields" class="flex flex-col  gap-4">


                        </div>

                        <button type="button" id="edit-add-testimony-btn" class="daisy-btn">Add Testimony</button>
                    </div>


                </div>
                <div class="flex flex-col justify-end gap-4">
                    <div class="flex flex-col flex-wrap gap-4 p-4 justify-end">
                        <!-- <button class="btn-tertiary bg-transparent " id="edit-reset-aotm" type="reset">
                            <i class="fa-solid fa-arrow-rotate-right"></i>
                            Reset Form
                        </button> -->
                        <button type="submit" name="edit-aotm-record" value="" class="btn-primary">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Alumni of the Month

                        </button>
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

    <div class="form-component-container">

        <div class="achievement-box hidden p-4 border rounded-md ">

            <div class="daisy-form-control w-full max-w-xs">
                <label class="daisy-label">
                    <span class="font-bold daisy-label-text">Achievement Title:</span>

                </label>
                <input type="text" class="daisy-input daisy-input-bordered w-full max-w-xs" name="a-title[]" placeholder="" required>

                <label class="daisy-label">
                    <span class="daisy-label-text-alt hidden">Bottom Left label</span>
                </label>
            </div>

            <div class="daisy-form-control w-full max-w-xs">
                <label class="font-bold daisy-label">
                    <span class="daisy-label-text">Description:</span>

                </label>
                <textarea type="text" name="a-description[]" class=" daisy-input daisy-input-bordered w-full max-w-xs min-h-16" placeholder="" required></textarea>
                <label class="font-bold daisy-label">
                    <span class="daisy-label-text-alt hidden">Bottom Left label</span>
                </label>
            </div>


            <div class="daisy-form-control w-full max-w-xs ">
                <label class="font-bold daisy-label">
                    <span class="daisy-label-text">Date:</span>

                </label>
                <input type="date" name="a-date[]" class="daisy-input daisy-input-bordered w-full max-w-xs" required>


                <label class="daisy-label">
                    <span class="daisy-label-text-alt hidden">Bottom Left label</span>
                </label>
            </div>


            <div class=" w-full max-w-xs mt-2 text-right">
                <button type="button" class="a-remove daisy-btn daisy-btn-warning daisy-btn-outline">Remove</button>
                <input type="hidden" name="achievement" value="good">


            </div>



        </div>
        <div class="filled-achievement-box hidden p-4 border rounded-md ">

            <div class="daisy-form-control w-full max-w-xs">
                <label class="daisy-label">
                    <span class="font-bold daisy-label-text">Achievement Title:</span>

                </label>
                <input type="text" class="daisy-input daisy-input-bordered w-full max-w-xs" name="filled-title" placeholder="" required>

                <label class="daisy-label">
                    <span class="daisy-label-text-alt hidden">Bottom Left label</span>
                </label>
            </div>

            <div class="daisy-form-control w-full max-w-xs">
                <label class="font-bold daisy-label">
                    <span class="daisy-label-text">Description:</span>

                </label>
                <textarea type="text" name="filled-description" class=" daisy-input daisy-input-bordered w-full max-w-xs min-h-16" placeholder="" required></textarea>
                <label class="font-bold daisy-label">
                    <span class="daisy-label-text-alt hidden">Bottom Left label</span>
                </label>
            </div>


            <div class="daisy-form-control w-full max-w-xs ">
                <label class="font-bold daisy-label">
                    <span class="daisy-label-text">Date:</span>

                </label>
                <input type="date" name="filled-date" class="daisy-input daisy-input-bordered w-full max-w-xs" required>


                <label class="daisy-label">
                    <span class="daisy-label-text-alt hidden">Bottom Left label</span>
                </label>
            </div>


            <div class=" w-full max-w-xs mt-2 text-right">
                <button type="button" data-achievement-id="" name="a-id" value="" class="filled-edit-achievement-btn a-edit daisy-btn daisy-btn-info daisy-btn-outline">Edit</button>
                <button type="button" data-achievement-id="" class="filled-delete-achievement-btn daisy-btn daisy-btn-warning daisy-btn-outline">Remove</button>
                <input type="hidden" name="filled-achievement" value="good">
                <input type="hidden" name="filled-achievementID" value="">


            </div>



        </div>

        <div class="testimony-box  p-4 border rounded-md w-full flex flex-col  gap-4 hidden">

            <div class="daisy-form-control w-full max-w-full">

                <label class="font-bold daisy-label" for="person_name">Name:</label>
                <input class="form-input daisy-input rounded" type="text" name="person_name[]" required><br>

            </div>



            <div class="w-full flex flex-wrap gap-4 [&>*]:flex-1 ">
                <div class="daisy-form-control">
                    <label class="font-bold daisy-label" for="relationship">Relationship:</label>
                    <input class="form-input daisy-input rounded" type="text" name="relationship[]" required><br>
                </div>
                <div class="daisy-form-control">
                    <label class="font-bold daisy-label" for="emailAddress">Email Address:</label>
                    <input class="form-input daisy-input rounded" type="email" name="emailAddress[]" required><br>
                </div>
            </div>



            <div class="w-full flex flex-wrap gap-4 [&>*]:flex-1">
                <div class="daisy-form-control ">

                    <label class="font-bold daisy-label" for="companyName">Company Name:</label>
                    <input class="form-input daisy-input rounded" type="text" name="companyName[]" required><br>
                </div>

                <div class="daisy-form-control ">
                    <label class="font-bold daisy-label" for="position">Position:</label>
                    <input class="form-input daisy-input rounded" type="text" name="position[]" required><br>
                </div>

            </div>
            <div class="daisy-form-control w-full max-w-full">
                <label class="font-bold daisy-label" for="message">Testimonial Message:</label><br>
                <textarea class="w-full form-textarea border rounded-md" name="message[]" rows="4" required></textarea><br>
            </div>

            <div class="w-full flex flex-wrap gap-4  [&>*]:flex-1">
                <div class="daisy-form-control ">
                    <label class="font-bold daisy-label" for="date">Date:</label>
                    <input class="form-input daisy-input rounded" type="date" name="date[]" required><br>
                </div>
                <div class="daisy-form-control w-full max-w-sm">
                    <label class="font-bold daisy-label" for="profile_img">Profile Image:</label>
                    <input type="file" required type="file" name="profile_img[]" class=" rounded daisy-file-input daisy-file-input-bordered w-full max-w-xs" />
                </div>
            </div>

            <button type="button" class="t-remove daisy-btn daisy-btn-warning daisy-btn-outline">Remove Testimonial</button>
            <input type="hidden" name="testimonial" value="true">
        </div>
        <div class="filled-testimony-box  p-4 border rounded-md w-full flex flex-col  gap-4 hidden">

            <div class="daisy-form-control w-full max-w-full">

                <label class="font-bold daisy-label" for="filled-person_name">Name:</label>
                <input class="form-input daisy-input rounded" type="text" name="filled-person_name" required><br>

            </div>



            <div class="w-full flex flex-wrap gap-4 [&>*]:flex-1 ">
                <div class="daisy-form-control">
                    <label class="font-bold daisy-label" for="filled-relationship">Relationship:</label>
                    <input class="form-input daisy-input rounded" type="text" name="filled-relationship" required><br>
                </div>
                <div class="daisy-form-control">
                    <label class="font-bold daisy-label" for="filled-emailAddress">Email Address:</label>
                    <input class="form-input daisy-input rounded" type="email" name="filled-emailAddress" required><br>
                </div>
            </div>



            <div class="w-full flex flex-wrap gap-4 [&>*]:flex-1">
                <div class="daisy-form-control ">

                    <label class="font-bold daisy-label" for="filled-companyName">Company Name:</label>
                    <input class="form-input daisy-input rounded" type="text" name="filled-companyName" required><br>
                </div>

                <div class="daisy-form-control ">
                    <label class="font-bold daisy-label" for="filled-position">Position:</label>
                    <input class="form-input daisy-input rounded" type="text" name="filled-position" required><br>
                </div>

            </div>
            <div class="daisy-form-control w-full max-w-full">
                <label class="font-bold daisy-label" for="filled-message">Testimonial Message:</label><br>
                <textarea class="w-full form-textarea border rounded-md" name="filled-message" rows="4" required></textarea><br>
            </div>

            <div class="w-full flex flex-wrap gap-4  [&>*]:flex-1">
                <div class="daisy-form-control ">
                    <label class="font-bold daisy-label" for="filled-date">Date:</label>
                    <input class="form-input daisy-input rounded" type="date" name="filled-date" required><br>
                </div>
                <div class="daisy-form-control w-full max-w-sm">
                    <label class="font-bold daisy-label" for="filled-profile_img">Profile Image:</label>
                    <input type="file" required type="file" name="filled-profile_img" class=" rounded daisy-file-input daisy-file-input-bordered w-full max-w-xs" />
                </div>
            </div>

            <div class="flex flex-wrap flex-row gap-4">
                <button type="button" data-testimony-id="" class="filled-t-edit daisy-btn daisy-btn-info daisy-btn-outline">Edit </button>
                <button type="button" data-testimony-id="" class="filled-t-remove daisy-btn daisy-btn-warning daisy-btn-outline">Remove </button>
                <input type="hidden" name="filled-testimonial" value="true">
                <input type="hidden" name="filled-testimony-id" value="">
            </div>

        </div>

    </div>



    </div>

</section>

<script type="module" src="./alumni-of-the-month/alumni.js"></script>