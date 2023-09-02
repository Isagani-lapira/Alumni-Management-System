<?php
session_start();
?>
<section class="container mx-auto px-8" id="events-page hidden">
    <h1 class="text-2xl font-bold">Events</h1>


    <div class="flex flex-wrap justify-end my-4"><button id="addNewEventBtn" class="btn-primary ">Add New Event</button></div>


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


    <!-- Refactor to use list -->
    <section id="event-record-section" class="mx-12 2xl:mx-auto md:max-w-6xl shadow-lg  rounded-lg overflow-clip  border ">
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
    </section>




    <!-- Modal -->
    <div id="crud-event-modal" class="fixed top-0 left-0 inset-0 h-full w-full bg-gray-700 bg-opacity-50 hidden 
     ">
        <div class="h-full flex items-center justify-center 
       ">
            <!-- Modal Content -->
            <div class="modal-container space-y-5  modal-container w-1/3 h-2/3 bg-white rounded-lg p-3 overflow-y-auto">
                <div class="modal-header py-5">
                    <h1 class="text-accent text-2xl text-center font-bold modal-title">
                        Event > <span id="event-title-modal">Add New Event</span>
                    </h1>
                </div>
                <!-- Event Form -->
                <form method="POST" enctype="multipart/form-data" id="crud-event-form">

                    <div class="input-container">
                        <label class="block" for="category">
                            Category
                        </label>
                        <select name="category" id="category">
                            <option value="">Alumni</option>
                            <option value="">All</option>
                        </select>
                    </div>

                    <div class="input-container">
                        <label class="block" for="eventName">
                            Event Name
                        </label>
                        <input required type="text" id="eventName" name="eventName">

                    </div>


                    <div class="input-container">

                        <label class="block" for="headerPhrase">
                            Header Phrase
                        </label>
                        <input required type="text" id="headerPhrase" name="headerPhrase">

                    </div>

                    <div class="input-container">
                        <label class="block" for="contactLink">
                            Contact Link
                        </label>
                        <input required type="text" id="contactLink" name="contactLink">

                    </div>
                    <div class="input-container">

                        <label class="block" for="eventPlace">
                            Event Place
                        </label>
                        <input required type="text" id="eventPlace" name="eventPlace">

                    </div>

                    <div class="input-container">
                        <label class="block" for="about_event" class="block">
                            About Event
                        </label>
                        <textarea name="about_event" id="about_event" cols="30" rows="10" class="input-textarea"></textarea>

                    </div>


                    <div class="input-container">

                        <label class="block" for="aboutImg">
                            About Image
                        </label>
                        <input required type="file" id="aboutImg" accept=".jpg" name="aboutImg">
                    </div>


                    <div class="flex gap-2">
                        <div class="input-container">
                            <label class="block" for="eventDate">
                                Event Date
                            </label>
                            <input required type="date" id="eventDate" name="eventDate">
                        </div>
                        <div class="input-container">
                            <label class="block" for="eventStartTime">
                                Event Start Time
                            </label>
                            <input required type="time" id="eventStartTime" name="eventStartTime">
                        </div>
                    </div>




                    <!-- End Description -->
                    <!-- Footer -->
                    <div class="modal-footer flex items-end flex-row-reverse px-3">
                        <button id="postBtn" class="bg-accent py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
                        <button type="button" class="cancel py-2 rounded px-5 text-grayish border border-slate-400 hover:bg-slate-400 hover:text-white">Cancel</button>
                        <!-- <button class="btn-tertiary block" type="reset">Clear</button> -->


                    </div>

                </form>
            </div>

            <!-- End Modal Content -->

        </div>
    </div>



</section>




<!-- Edit Event -->
<!-- TODO make the section floating from the main events and refresh the event after the event is updated. -->
<section id="section-edit-event">

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