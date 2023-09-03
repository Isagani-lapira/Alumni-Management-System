<?php
session_start();
?>
<section class="container mx-auto px-8" id="events-page">



    <!-- Refactor to use list -->
    <section id="event-record-list-section" class="transition-all">
        <h1 class="text-2xl font-bold">Events</h1>



        <div class="flex flex-wrap justify-between my-4 items-center">
            <div>
                <h2>Total Posts</h2>

                <p class="text-5xl">
                    <?php
                    require '../php/connection.php';
                    require '../model/Event.php';
                    $event = new Event($mysql_con);
                    echo $event->getTotalEvents($_SESSION['colCode']);

                    ?>
                </p>
            </div>
            <button id="addNewEventBtn" class="btn-primary ">Add New Event</button>
        </div>



        <section class=".mx-12 2xl:mx-auto md:max-w-6xl shadow-lg  rounded-lg overflow-clip  border">
            <ul class="list-none border space-y-4  " id="event-list">
                <!-- Dummy data -->
                <!-- <li class="border border-gray-500 rounded-lg p-2 ">
                     <div class="grid grid-cols-3   ">
                        <img src="https://picsum.photos/200" alt="event image" class="w-48 object-cover">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800 ">Upcoming Alumni Festival</h3>
                            <p class="font-normal  text-gray-400">Event Header</p>
                            <p class="text-gray-400 text-sm ">lorem ipsum...</p>
                        </div>
                        <div>
                            <p class="text-sm  uppercase tracking-wider text-gray-800 opacity-50 ">Event Date and Time</p>
                            <p class="font-medium text-lg text-gray-800">August 10 2023 | 9 PM</p>
                            <p class="text-sm  uppercase tracking-wider text-gray-800 opacity-50 ">Date Posted</p>
                            <p class=" font-medium text-lg text-gray-800">August 10 2023</p>
                            <div class="">
                                <button class="btn-tertiary">Edit Details</button>
                                <i class="fa-solid fa-ellipsis fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </li> -->
            </ul>
            <div class="py-4 px-2 flex justify-end">
                <!-- Pagination -->
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

    </section>




    <!-- CRUD Event Section -->
    <section id="crud-event" class="hidden transition-all">
        <div class="header py-5">
            <h1 class=" text-2xl tex-left font-bold modal-title">
                Event > <span id="event-title" class="text-accent">Add New Event</span>
            </h1>
        </div>
        <!-- Event Form -->
        <form method="POST" enctype="multipart/form-data" id="crud-event-form">
            <div class=" flex flex-row flex-wrap">

                <div class=" left-section flex-1 space-y-4">
                    <div class="input-container">
                        <label class="block font-semibold text-gray-800" for="eventName">
                            Event Name
                        </label>
                        <input required type="text" id="eventName" name="eventName">
                    </div>
                    <div class="input-container">
                        <label class="block font-semibold text-gray-800 " for="headerPhrase">
                            Header Phrase:
                        </label>
                        <input required type="text" id="headerPhrase" name="headerPhrase">
                    </div>
                    <div class="input-container">
                        <label class="block font-semibold text-gray-800" for="category">
                            Category
                        </label>
                        <select name="category" id="category" class="p-2 px-4 bg-white text-gray-800 border rounded ">
                            <option value="">Alumni</option>
                            <option value="">All</option>
                        </select>
                    </div>
                    <div class="input-container">
                        <label class="block font-semibold text-gray-800" for="contactLink">
                            Contact Link
                        </label>
                        <input required type="text" id="contactLink" name="contactLink">
                    </div>
                    <div class="input-container">
                        <label class="block font-semibold text-gray-800" for="eventPlace">
                            Event Place
                        </label>
                        <input required type="text" id="eventPlace" name="eventPlace">
                    </div>
                    <div class="input-container">
                        <label class="block font-semibold text-gray-800" for="about_event" ">
                            Description
                        </label>
                        <textarea name=" about_event" id="about_event" cols="30" rows="10" class="input-textarea"></textarea>
                    </div>
                </div>


                <div class="right-section flex-1 flex justify-between flex-col  ">
                    <div class="up space-y-4">
                        <div class="input-container">
                            <label class="block font-semibold text-gray-800" for="aboutImg">
                                Heading Image
                            </label>
                            <!-- image block in order to display the image -->
                            <div class="image-block">
                                <img src="" alt="" id="aboutImgPreview" class="border
                             border-gray-400 object-cover" width="400" height="200">
                            </div>
                            <input required type="file" id="aboutImg" accept=".jpg" name="aboutImg">
                        </div>
                        <div class="input-container">
                            <label class="block font-semibold text-gray-800" for="eventDate">
                                Event Date
                            </label>
                            <input required type="date" id="eventDate" name="eventDate">
                        </div>
                        <div class="input-container">
                            <label class="block font-semibold text-gray-800" for="eventStartTime">
                                Event Start Time
                            </label>
                            <input required type="time" id="eventStartTime" name="eventStartTime">
                        </div>
                    </div>
                    <!-- End Description -->
                    <div class="down"> <!-- Footer -->
                        <div class="modal-footer flex gap-4">
                            <button type="button" class="cancel py-2 rounded px-5 text-grayish border border-slate-400 hover:bg-slate-400 hover:text-white">Cancel</button>
                            <button id="postBtn" class="bg-accent py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
                            <!-- <button class="btn-tertiary block" type="reset">Clear</button> -->

                        </div>
                    </div>
                </div>
            </div>

        </form>


    </section>
    <!-- End CRUD Event Section -->


</section>




<!-- Edit Event -->
<!-- TODO make the section floating from the main events and refresh the event after the event is updated. -->
<section id="section-edit-event" class="hidden">

    <h2>Events > Edit <span id="edit-event-title"></span></h2>

    <form action="">
        <label for="title">Title: </label>
    </form>

</section>

<!-- End Edit Event -->

<script>
    $(document).ready(function() {
        $.getScript("./event/event.js");
        // resize text area jquery
    });
</script>