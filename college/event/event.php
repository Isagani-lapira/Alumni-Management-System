<?php
session_start();
?>
<section class="container mx-auto px-8">
    <h1 class="text-2xl font-bold">Events</h1>


    <div class="flex flex-wrap justify-end my-4"><button class="btn-primary ">Add New Event</button></div>


    <hr>
    <h2>Total Posts</h2>
    <p class="text-5xl">
        <?php
        require '../php/connection.php';
        require '../model/Event.php';
        $event = new Event($mysql_con);
        echo $event->getTotalEvents($_SESSION['colCode']);

        ?>
    </p>


    <!-- Table Start -->
    <section id="event-table-record-section" class="mx-12 2xl:mx-auto md:max-w-6xl shadow-lg  rounded-lg overflow-clip  border ">
        <table class="overflow-y-scroll py-2  table-auto table-alternate-color w-full">
            <thead class="  bg-accent text-white rounded-t-md">
                <tr>
                    <th>Header Image</th>
                    <th>Name</th>
                    <th>Header</th>
                    <th>Event Date & Time</th>
                    <th>Date Posted</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="event-record-tbody" class="">

                <!-- <tr>
                    <td class="w-48 border "><img class="object-cover block" src="../assets/event-images/02.png" alt=""></td>
                    <td class="font-bold text-gray-600 hover:text-blue-500 cursor-pointer">Upcoming Alumni Festival</td>
                    <td class="font-medium ">We are the world</td>
                    <td class="max-w-prose">10 PM august 23 2023 </td>
                    <td>04/10/24</td>
                    <td><button class="btn-tertiary">Archive</button></td>
                </tr> -->

            </tbody>

        </table>

        <div class="py-4 px-2 flex justify-end">
            <!-- <button class="btn-secondary bg-gray-200 border border-gray-400 hover:bg-accent py-2 px-5 text-gray-900 hover:text-white font-normal rounded-md">Select All</button> -->
            <!-- Pagination Start -->
            <!-- <div>
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
            </div> -->
            <!-- Button Pagination -->
            <div class="inline-flex">
                <button id="prevPostsBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
                    Prev
                </button>
                <button id="nextPostsBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                    Next
                </button>
            </div>
        </div>
    </section>


    <!-- Pagination End -->

    <!-- Form for new event -->

    <!-- * eventID, eventName, eventDate, colCode, about_event, 
 * contactLink, aboutImg, headerPhrase, eventPlace,eventStartTime
 * 
 * date_posted, adminID,  
 * 
 * what's needed is : -->
    <!-- TODO add modal later -->
    <section class="add-event-section hidden">
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