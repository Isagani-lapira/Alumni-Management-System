<section class="container p-4">
    <h1 class="font-bold text-2xl">Alumni of the Month</h1>
    <p class="text-gray-400 font-bold text-sm">Make post for alumni of the month</p>

    <div class="flex flex-wrap justify-end gap-4">
        <button class="btn-tertiary">
            Export List
        </button>
        <!-- <button class="btn-primary" id="addAlumniBtn">
            Add Alumni
        </button> -->
        <!-- <button class="daisy-btn btn-primary " onclick="my_modal_4.showModal()">Add Alumni</button> -->
        <!-- The button to open modal -->
        <label for="add-alumni-modal" class="daisy-btn">Add This Month's Alumni</label>

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
    <!-- refresh record -->
    <div class="flex justify-end gap-4 mt-4">
        <button class="btn-tertiary" id="refreshRecord">
            Refresh Record
        </button>
    </div>
    <!-- Record Table-->
    <table class="table-auto w-full mt-10 text-xs font-normal text-gray-800 rounded-t-lg">
        <thead class="bg-accent text-white rounded-t-lg">
            <tr class=" rounded-t-lg">
                <th class="text-start uppercase">Student Number</th>
                <th>NAME</th>
                <th>DETAILS</th>
            </tr>
        </thead>
        <tbody class="text-sm" id="tBodyRecord">

            <!-- <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Wade Warren</span>
                    </div>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr> -->


        </tbody>
    </table>
    <!-- End Record Table -->


    <!-- MODAL OLD -->
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

    <!-- Modal NEW for adding alumni of the month -->
    <!-- TODO use searchbar to search the alumni for users. -->
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
                <div id="aotmRegister" class=" text-greyish_black flex flex-col px-12">
                    <span class="daisy-label-text">Choose Cover Image</span>

                    <!-- Placeholder for Cover Image -->
                    <div class="w-full h-40 relative group">
                        <img id="cover-img-preview" class="w-full bg-red-300 object-cover max-h-full h-full block" src="" alt="">
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


                    <!-- Placeholder for profile image -->
                    <!-- Profile Image Input -->
                    <!-- <span class="daisy-label-text"> Choose Profile Image</span>

                    <div class="flex justify-center relative group">
                        <img id="profile-img-preview" class="w-40 h-40 rounded-full peer" src="../../assets/default_profile.png" alt="">
                        <div class="daisy-form-control  w-full max-w-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <label for="profile-image" class="daisy-btn w-9 mx-auto ">
                                Choose Image
                            </label>
                            <input class="daisy-file-input hidden daisy-file-input-bordered w-full max-w-xs" id="profile-image" type="file" accept=".jpg" name="profile-image">
                            <label class="daisy-label mx-auto bg-gray-100 rounded mt-2">
                                <span class="daisy-label-text-alt text-slate-600 ">Use JPG File Format</span>
                            </label>
                        </div>
                    </div> -->

                    <label class="font-bold" for="fullname">Fullname</label>
                    <input id="fullname" name="fullname" class="form-input block rounded" type="text" placeholder="e.g Patrick Joseph Pronuevo">

                    <label class="font-bold" for="studentNo">Student ID</label>
                    <input id="studentNo" name="studentNo" class="form-input block rounded" required type="text" placeholder="e.g Patrick Joseph Pronuevo">
                    <label class="font-bold" for="personID">Person ID</label>
                    <input id="studentNo" name="personID" class="form-input block rounded" required type="text" placeholder="User23/09/24 10:14:03-644">

                    <label class="font-bold" for="quote">Quotation</label>
                    <input id="quote" name="quote" class="form-input block rounded" type="text" placeholder="Journey of a thousand miles starts in a single step.">


                    <p class="font-bold block" for="">Description</p>
                    <textarea name="description" class="form-textarea block rounded resize max-w-full" id="description"></textarea>

                    <div class="flex flex-wrap gap-4 py-4 justify-end">
                        <button class="btn-tertiary bg-transparent " type="reset">Reset Form</button>
                        <button class="btn-primary">Add Alumni of the Month</button>
                    </div>
                </div>
            </form>
            <!-- End Add FORM -->

        </div>
    </div>


</section>

<script>
    // Date picker
    $('#aoydaterange').daterangepicker();

    // get the script for alumni.js
    $.getScript("./alumni-of-the-month/alumni.js");

    // preview the image after changing the input
    $('#cover-image').change(function() {
        let reader = new FileReader();
        reader.onload = (e) => {
            $('#cover-img-preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });
    // $('#profile-image').change(function() {
    //     let reader = new FileReader();
    //     reader.onload = (e) => {
    //         $('#profile-img-preview').attr('src', e.target.result);
    //     }
    //     reader.readAsDataURL(this.files[0]);
    // });

    // on form submit
    $('#add-aotm-form').submit(async function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        // add some sweet alert dialog
        const confirmation = await Swal.fire({
            title: "Confirm?",
            text: "Are you sure to add alumni with these details?",
            icon: "info",
            showCancelButton: true,
            // confirmButtonColor: CONFIRM_COLOR,
            // cancelButtonColor: CANCEL_COLOR,
            confirmButtonText: "Yes, Add it!",
        });
        if (confirmation.isConfirmed) {
            console.log("submitting add alumni form");

            const isSuccessful = await postNewAlumni(this);
            console.log(isSuccessful);
            if (isSuccessful.response === "Successful") {
                // show the success message
                Swal.fire("Success!", "Alumni has been added.", "success");
                // remove the form data
                $("#add-aotm-form")[0].reset();
                $("#cover-image-preview").attr("src", "");
                // $("#profile-image-preview").attr("src", "");
                $("#add-alumni-modal").prop("checked", false);
            } else {
                Swal.fire("Error", "Alumni is not added due to error.", "info");

            }
        } else {
            Swal.fire("Cancelled", "Add alumni cancelled.", "info");

        }

    });

    async function postNewAlumni(form, url = './alumni-of-the-month/addAlumni.php') {
        // get the form data
        const formData = new FormData(form);
        const response = await fetch(url, {
            method: "POST",
            body: formData,
        });
        return response.json();
    }
</script>