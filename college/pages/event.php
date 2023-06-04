<div class="container mx-auto">
    <h1 class="text-2xl font-bold">Events</h1>

    <button class="btn-primary ">Add New</button>


    <!-- Table Start -->
    <div class="  mx-auto max-w-6xl shadow-lg   rounded-lg overflow-clip  border">
        <table class="  px-2 overflow-y-scroll py-2  table-auto ">
            <thead class="p  bg-accent text-white rounded-t-md">
                <tr>
                    <th class="py-4 ">Checkbox</th>
                    <th>Picture</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Date Posted</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" value="test" class=" "></td>
                    <td class="w-64 border "><img class="object-cover " src="../assets/event-images/02.png" alt=""></td>
                    <td class="font-bold text-lg">Upcoming Alumni Festival</td>
                    <td class="font-bold text-lg">Admin</td>
                    <td class="max-w-prose">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ratione quae, commodi repudiandae sed accusantium optio omnis qui beatae sit voluptatem, laboriosam rerum. Consequatur nostrum </td>
                    <td>04/10/24</td>
                </tr>

            </tbody>

        </table>
        <div class="py-4 px-2 flex justify-between">
            <button class="btn-secondary bg-gray-200 border border-gray-400 hover:bg-accent py-2 px-5 text-gray-900 hover:text-white font-normal rounded-md">Select All</button>
            <!-- Pagination Start -->
            <div>
                <div class="pagination space-x-2">
                    <a href="#">&lt;</a>
                    <a href="#" class="underline font-bold text-lg">1</a>
                    <a href="#" class="  text-lg">2</a>
                    <a href="#" class="  text-lg">3</a>
                    <a href="#" class="  text-lg">4</a>
                    <a href="#" class="  text-lg">..</a>
                    <a href="#" class="  text-lg">last</a>
                    <a href="#" class="  text-lg">&gt;</a>
                </div>
            </div>
        </div>
    </div>

</div>