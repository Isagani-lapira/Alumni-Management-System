<section class="container mx-auto">
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


    <!-- Pagination End -->

    <!-- Form for new event -->

    <!-- * eventID, eventName, eventDate, colCode, about_event, 
 * contactLink, aboutImg, headerPhrase, eventPlace,eventStartTime
 * 
 * date_posted, adminID,  
 * 
 * what's needed is : -->
    <!-- TODO add ui later -->
    <section class="add-event-section">
        <form action="./event/addEvent.php" method="POST" enctype="multipart/form-data">
            <h2>Create new event</h2>



            <div class="input-container">
                <label class="block" for="eventName">
                    Event Name
                </label>
                <input type="text" id="eventName" name="eventName">

            </div>

            <div class="input-container">
                <label class="block" for="eventDate">
                    Event Date
                </label>
                <input type="date" id="eventDate" name="eventDate">

            </div>


            <div class="input-container">
                <label class="block" for="about_event" class="block">
                    About Event
                </label>
                <textarea name="about_event" id="about_event" cols="30" rows="10" class="input-textarea"></textarea>

            </div>

            <div class="input-container">
                <label class="block" for="contactLink">
                    Contact Link
                </label>
                <input type="text" id="contactLink" name="contactLink">

            </div>
            <div class="input-container">

                <label class="block" for="aboutImg">
                    About Image
                </label>
                <input type="file" id="aboutImg" accept=".jpg" name="aboutImg">

            </div>

            <div class="input-container">

                <label class="block" for="headerPhrase">
                    Header Phrase
                </label>
                <input type="text" id="headerPhrase" name="headerPhrase">

            </div>
            <div class="input-container">

                <label class="block" for="eventPlace">
                    Event Place
                </label>
                <input type="text" id="eventPlace" name="eventPlace">

            </div>

            <div class="input-container">
                <label class="block" for="eventStartTime">
                    Event Start Time
                </label>
                <input type="time" id="eventStartTime" name="eventStartTime">

            </div>


            <button class="btn-primary" type="submit">Submit</button>
            <button class="btn-tertiary" type="reset">Clear</button>

        </form>

    </section>

</section>


<script>
    $(document).ready(function() {
        $.getScript("./event/event.js");
        // resize text area jquery
    });
</script>