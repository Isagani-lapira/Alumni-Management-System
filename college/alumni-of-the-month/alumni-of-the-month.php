<section class="container p-4">
    <h1 class="font-bold text-2xl">Alumni of the Month</h1>
    <p class="text-gray-400 font-bold text-sm">Make post for alumni of the month</p>

    <button class="block bg-accent py-1 px-3 text-white ml-auto rounded-md">
        Export List
    </button>
    <hr class="h-px my-5 bg-grayish border-0 dark\:bg-gray-700">
    <!-- Filter  -->
    <div class="flex flex-wrap gap-4">

        <div class="relative rounded">
            <i class="fa-solid fa-magnifying-glass absolute top-3 left-3"></i>
            <input class="pl-8" type="text" name="" id="aomSearch" placeholder="Search name">
        </div>

        <!-- Sex -->
        <select name="" id="" class="rounded">
            <option value="">All</option>
            <option value="">Male</option>
            <option value="">Female</option>
        </select>


        <!-- range -->
        <div class="relative">
            <input class="pr-8 rounded" type="text" name="aoydaterange" id="aoydaterange" value="01/01/2018 - 01/15/2018">
            <label class="absolute top-3 right-3" for="aoydaterange">
                <i class="fa-solid fa-calendar"></i>
            </label>
        </div>

        <!-- batch -->
        <select name="batch rounded" id="aomBatch" class=" p-1 w-64">
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