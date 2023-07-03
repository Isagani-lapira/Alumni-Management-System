<section class="container p-4">
    <h1 class="font-bold text-2xl">Alumni of the Month</h1>
    <p class="text-gray-400 font-bold text-sm">Make post for alumni of the month</p>

    <button class="block bg-accent py-1 px-3 text-white ml-auto rounded-md">
        Export List
    </button>
    <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700">
    <!-- Filter  -->
    <div class="flex justify-evenly text-xs">

        <div class="flex border border-grayish w-full rounded-md p-1">
            <img class="inline " src="../images/search-icon.png" alt="">
            <input class="outline-none" type="text" name="" id="aomSearch" placeholder="Typing!">
        </div>

        <!-- gender -->
        <div class="flex items-center gap-2 ms-2">
            <input type="radio" name="aomGender" id="aomAll" checked="">
            <label for="aomAll">All</label>
            <input type="radio" name="aomGender" id="aomMale">
            <label for="aomMale">Male</label>
            <input type="radio" name="aomGender" id="aomFemale">
            <label for="aomFemale">Female</label>
        </div>

        <!-- range -->
        <div class="w-full flex justify-evenly border p-2 mx-2">
            <input type="text" name="aoydaterange" id="aoydaterange" value="01/01/2018 - 01/15/2018">
            <label class="" for="aoydaterange">
                <img class="h-5 w-5" src="../assets/icons/calendar.svg" alt="">
            </label>
        </div>

        <!-- batch -->
        <select name="batch" id="aomBatch" class="w-full p-1">
            <option value="" selected="" disabled="" hidden="">Batch</option>
        </select>

        <!-- college -->
        <select name="employment" id="aomCollege" class="w-full p-1">
            <option value="" selected="" disabled="" hidden="">College</option>
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

            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Leslie Alexander</span>
                    </div>
                </td>
                <td class="text-center">(704) 555-0127</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-yellow-300 text-yellow-500">Alumni</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>

            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Floyd Miles</span>
                    </div>
                </td>
                <td class="text-center">(208) 555-0112</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-green-300 text-green-700">STUDENT</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>


            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Cameron Williamson</span>
                    </div>
                </td>
                <td class="text-center">(239) 555-0108</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-yellow-300 text-yellow-500">Alumni</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>


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

            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Leslie Alexander</span>
                    </div>
                </td>
                <td class="text-center">(704) 555-0127</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-yellow-300 text-yellow-500">Alumni</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>

            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Floyd Miles</span>
                    </div>
                </td>
                <td class="text-center">(208) 555-0112</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-green-300 text-green-700">STUDENT</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>


            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Cameron Williamson</span>
                    </div>
                </td>
                <td class="text-center">(239) 555-0108</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-yellow-300 text-yellow-500">Alumni</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>

            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Floyd Miles</span>
                    </div>
                </td>
                <td class="text-center">(208) 555-0112</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-green-300 text-green-700">STUDENT</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>


            <tr class="h-14">
                <td class="student-num__val text-start font-bold">2020101933</td>
                <td>
                    <div class="flex items-center justify-start">
                        <div class="w-10 h-10 rounded-full border border-accent"></div>
                        <span class="ml-2">Cameron Williamson</span>
                    </div>
                </td>
                <td class="text-center">(239) 555-0108</td>
                <td class="text-center">
                    <span class="py-1 px-2 rounded-lg font-semibold bg-yellow-300 text-yellow-500">Alumni</span>
                </td>
                <td class="text-center text-blue-400 font-light hover:cursor-pointer hover:text-accentBlue hover:font-semibold">VIEW PROFILE</td>
            </tr>

        </tbody>
    </table>
    <!-- End Record Table -->
</section>

<script>
    // Date picker
    $('#aoydaterange').daterangepicker();
</script>