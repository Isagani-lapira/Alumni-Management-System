<section id="email-section" class=" mx-auto lg:mx-8">
    <!-- Email content -->
    <h1 class="text-xl font-extrabold">EMAIL</h1>
    <p class="text-grayish">Here you can check all the post you have and can create new post</p>
    <div class="mt-5 text-end">
        <button id="btnEmail" class="bg-accent font-light block text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg">CREATE
            NEW
            MESSAGE
        </button>
        <span class="text-xs text-greyish_black hover:font-medium py-3 cursor-pointer">DELETE POST</span>
    </div>
    <hr class="h-px my-3 bg-grayish border-0 dark\:bg-gray-700" />

    <div class="flex items-center">

        <div class="m-2 p-1">
            <span class="font-semibold">Total Message</span>
            <p class="text-5xl font-bold">12</p>
        </div>

        <div class="m-2 p-1">
            <p class="text-sm font-thin">Course</p>
            <!-- college selection -->
            <select name="college" id="emCol" class="w-full border border-grayish p-2 rounded-lg">
                <option value="" selected disabled hidden>BS Computer Science</option>
            </select>
        </div>


        <div class="m-2 p-1">
            <p>Show post (from - to)</p>
            <div class="w-full flex border border-grayish p-2 rounded-lg">
                <input type="text" name="emDateRange" id="emDateRange" value="01/01/2018 - 01/15/2018" />
                <label class="" for="emDateRange">
                    <img class="h-5 w-5" src="../assets/icons/calendar.svg" alt="">
                </label>
            </div>

        </div>

    </div>


    <!-- recent email -->
    <p class="mt-10 font-semibold">Recent Email</p>
    <table class="table-auto w-8/12  font-normal text-gray-600">
        <thead>
            <tr>
                <th class="text-start">EMAIL ADDRESS</th>
                <th class="text-start">COLLEGE</th>
                <th class="text-start">DATE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-start">To All</td>
                <td class="text-start">CICT</td>
                <td class="text-start">03/02/2020</td>
            </tr>
            <tr>
                <td class="text-start">lapiraisagani@gmail.com</td>
                <td class="text-start">CICT</td>
                <td class="text-start">03/02/2020</td>
            </tr>
            <tr>
                <td class="text-start">patrickpronuevo@gmail.com</td>
                <td class="text-start">COED</td>
                <td class="text-start">03/02/2020</td>
            </tr>
        </tbody>
    </table>


</section>