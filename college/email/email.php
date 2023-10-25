<?php session_start()
?>
<section id="email-section" class=" mx-auto lg:mx-8">
    <!-- Email content -->
    <h1 class="text-xl font-extrabold">EMAIL</h1>
    <p class="text-grayish">Here you can check all the post you have and can create new post</p>
    <div class="mt-5 text-end">

        <!-- The button to open modal -->
        <label for="sendEmailModal" class="daisy-btn btn-primary">Send New Email</label>

    </div>
    <hr class="h-px my-3 bg-grayish border-0 dark\:bg-gray-700" />

    <div class="flex items-center">

        <div class="m-2 p-1">
            <span class="font-semibold">Total Emails Sent</span>
            <?php
            require_once '../php/connection.php';

            $query = 'SELECT * FROM `email` WHERE `personID` = "' . $_SESSION['personID'] . '" ';
            $result = mysqli_query($mysql_con, $query);
            $row = mysqli_num_rows($result);
            echo '<p class="text-5xl font-bold">' . $row . '</p>';
            ?>
        </div>

        <!-- <div class="m-2 p-1">
            <p class="text-sm font-thin">Course</p>
            <select name="college" id="emCol" class="w-full border border-grayish p-2 rounded-lg">
                <option value="">Dummy Data</option>
                <option value="">BS Information Technology</option>
            </select>
        </div> -->

        <!-- Date Picker -->
        <div class="m-2 p-1">
            <p>Filter Emails (from - to)</p>
            <div class="relative border">
                <input type="text" name="daterange" id="daterange" value="01/01/2018 - 01/15/2018" class="rounded " />
                <img class="h-5 w-5  absolute right-4 top-2" src="../assets/icons/calendar.svg" alt="">
            </div>

        </div>

    </div>


    <!--Start Email Table  -->
    <p class="mt-10 font-semibold">Recent Email</p>
    <table class="table-auto w-8/12  font-normal text-gray-600">
        <thead class="bg-accent text-white">
            <tr>
                <th class="text-start">EMAIL ADDRESS</th>
                <th class="text-start">COLLEGE</th>
                <th class="text-start">DATE</th>
                <th class="text-start">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once '../php/connection.php';
            $query = 'SELECT * FROM `email` WHERE `personID` = "' . $_SESSION["personID"] . '" ORDER BY `dateSent` DESC LIMIT 12';
            $result = mysqli_query($mysql_con, $query);
            $row = mysqli_num_rows($result);
            if ($result && $row > 0) {
                while ($data = mysqli_fetch_assoc($result)) {
                    $recipient = $data['recipient'];
                    $colCode  = $data['colCode'];
                    $dateSent  = $data['dateSent'];

                    echo '
                    <tr>
                      <td class="text-start">' . $recipient . '</td>
                      <td class="text-start">' . $colCode . '</td>
                      <td class="text-start">' . $dateSent . '</td>
                      <td class="flex justify-center gap-2">
                        <button class="text-red-400 hover:bg-red-400 hover:text-white px-3 py-1 rounded-md text-xs">Delete</button>
                        <button class="bg-blue-400 text-white hover:bg-blue-500 rounded-md text-xs px-3 py-1">View</button>
                      </td>
                    </tr>';
                }
            } else {
                echo '<tr>
                      <td class="text-blue-400 text-lg text-center w-full">No available email sent</td>
                    </tr>';
            }
            ?>
        </tbody>
    </table>
    <!-- End Email Table -->




    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="sendEmailModal" class="daisy-modal-toggle" />
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-6xl">
            <div class="modal-header py-5">
                <h1 class="text-accent text-2xl text-center font-bold">Send an Email </h1>
            </div>
            <form id="emailForm" class=" ">
                <div class="w-full h-full">

                    <div class="modal-body px-3 h-1/2">
                        <!-- header part -->
                        <p class="font-semibold text-sm">Recipient</p>
                        <div class="flex gap-2 justify-start mb-2">
                            <label for="groupEM">

                                <input type="radio" id="groupEM" name="recipient" checked value="group">
                                Group</label>

                            <label for="individEM">
                                <input type="radio" id="individEM" name="recipient" value="individual">
                                Individual</label>
                        </div>

                        <div id="groupEmail" class="flex gap-1">
                            <select name="selectColToEmail" id="selectColToEmail" class="w-full form-select">
                                <option value="all" selected>All colleges</option>
                                <?php
                                require_once '../php/connection.php';
                                $query = "SELECT * FROM `college`";
                                $result = mysqli_query($mysql_con, $query);
                                $rows = mysqli_num_rows($result);

                                if ($rows > 0) {
                                    while ($data = mysqli_fetch_assoc($result)) {
                                        $colCode = $data['colCode'];
                                        $colName = $data['colname'];

                                        echo '<option value="' . $colCode . '">' . $colName . '</option>';
                                    }
                                } else echo '<option>No college available</option>';
                                ?>
                            </select>


                            <div class="flex gap-1 items-center">
                                <!-- all -->
                                <input id="allEM" name="selectedUser" type="radio" checked value="all">
                                <label for="allEM">All</label>

                                <!-- alumni -->
                                <input id="alumniEM" name="selectedUser" type="radio" value="alumni">
                                <label for="alumniEM">Alumni</label>

                                <!-- student -->
                                <input id="studentEM" name="selectedUser" type="radio" value="Student">
                                <label for="studentEM">Student</label>
                            </div>

                        </div>


                        <div class="relative">
                            <div id="individualEmail" class="flex border border-gray-400 w-full rounded-md p-1 hidden">
                                <img class="inline" src="../images/search-icon.png" alt="">
                                <input class="focus:outline-none w-full" type="text" name="searchEmail" id="searchEmail" placeholder="Search email!">
                            </div>
                            <p id="userNotExist" class="text-sm italic text-accent hidden">User not exist</p>
                            <div id="suggestionContainer" class="absolute p-2 w-full rounded-md z-10"></div>
                        </div>

                        <p class="font-semibold text-sm mt-2">Subject</p>
                        <input class="focus:outline-none w-full border border-gray-400 rounded-md py-2 px-1" type="text" name="emailSubj" id="emailSubj" placeholder="Introduce something great!">

                        <!-- body part -->
                        <p class="font-semibold text-sm mt-2">Description</p>
                        <div class="modal-descript relative w-full h-max border border-gray-400 rounded p-3">
                            <div class="flex flex-col h-full">
                                <textarea id="TxtAreaEmail" class="rar outline-none w-full h-48 p-1 border-b border-gray-300" type="text" placeholder="Say something here..."></textarea>
                            </div>
                        </div>
                        <div id="imgContEmail" class=" hidden flex overflow-x-auto w-full"></div>
                        <div id="fileContEmail" class="hidden"></div>
                        <p class="text-sm text-red-400 hidden" id="errorMsgEM">Sorry we only allow images that has file extension of
                            jpg,jpeg,png</p>
                        <label class="flex justify-start items-center gap-3 mt-1">
                            <i id="galleryIcon" class="fa-solid fa-image"></i>
                            <i id="fileIcon" class="fa-solid fa-paperclip"></i>
                        </label>
                        <input id="imageSelection" type="file" class="hidden">
                        <input id="fileSelection" type="file" class="hidden">
                    </div>

                    <!-- Footer -->
                    <!-- <div class="modal-footer flex items-end flex-row-reverse px-3 mt-2">
                        <button type="submit" id="sendEmail" class="bg-accent h-full py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
                        <button type="button" class="cancelEmail py-2 rounded px-5  hover:bg-slate-400 hover:text-white">Cancel</button>
                    </div>
                </div> -->
                    <div class="daisy-modal-action">
                        <label for="sendEmailModal" class="daisy-btn cancelEmail">Cancel</label>

                        <button type="submit" id="sendEmail" class="bg-accent h-full py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Send Email</button>
                    </div>
            </form>


        </div>
    </div>

    <!-- modal add email message -->
    <div id="modalEmail" class="bg-gray-800 bg-opacity-80 fixed inset-0 h-full w-full flex items-center justify-center 
      text-grayish  top-0 left-0 hidden">
        <form id="emailForm" class="modal-container w-1/3 h-max bg-white rounded-lg p-3">
            <div class="w-full h-full">
                <div class="modal-header py-5">
                    <h1 class="text-accent text-2xl text-center font-bold">Create New Post</h1>
                </div>
                <div class="modal-body px-3 h-1/2">

                    <!-- header part -->
                    <p class="font-semibold text-sm">Recipient</p>
                    <div class="flex gap-2 justify-start mb-2">
                        <label for="groupEM">

                            <input type="radio" id="groupEM" name="recipient" checked value="group">
                            Group</label>

                        <label for="individEM">
                            <input type="radio" id="individEM" name="recipient" value="individual">
                            Individual</label>
                    </div>


                    <div id="groupEmail" class="flex gap-1">
                        <div class=" border border-gray-400 rounded flex px-2 py-2">
                            <select name="selectColToEmail" id="selectColToEmail" class="  form-select">
                                <option value="all" selected>All colleges</option>
                                <?php
                                require_once '../php/connection.php';
                                $query = "SELECT * FROM `college`";
                                $result = mysqli_query($mysql_con, $query);
                                $rows = mysqli_num_rows($result);

                                if ($rows > 0) {
                                    while ($data = mysqli_fetch_assoc($result)) {
                                        $colCode = $data['colCode'];
                                        $colName = $data['colname'];

                                        echo '<option value="' . $colCode . '">' . $colName . '</option>';
                                    }
                                } else echo '<option>No college available</option>';
                                ?>
                            </select>
                        </div>


                        <div class="flex gap-1 items-center">
                            <!-- all -->
                            <input id="allEM" name="selectedUser" type="radio" checked value="all">
                            <label for="allEM">All</label>

                            <!-- alumni -->
                            <input id="alumniEM" name="selectedUser" type="radio" value="alumni">
                            <label for="alumniEM">Alumni</label>

                            <!-- student -->
                            <input id="studentEM" name="selectedUser" type="radio" value="Student">
                            <label for="studentEM">Student</label>
                        </div>

                    </div>


                    <div class="relative">
                        <div id="individualEmail" class="flex border border-gray-400 w-full rounded-md p-1 hidden">
                            <img class="inline" src="../images/search-icon.png" alt="">
                            <input class="focus:outline-none w-full" type="text" name="searchEmail" id="searchEmail" placeholder="Search email!">
                        </div>
                        <p id="userNotExist" class="text-sm italic text-accent hidden">User not exist</p>
                        <div id="suggestionContainer" class="absolute p-2 w-full rounded-md z-10"></div>
                    </div>

                    <p class="font-semibold text-sm mt-2">Subject</p>
                    <input class="focus:outline-none w-full border border-gray-400 rounded-md py-2 px-1" type="text" name="emailSubj" id="emailSubj" placeholder="Introduce something great!">

                    <!-- body part -->
                    <p class="font-semibold text-sm mt-2">Description</p>
                    <div class="modal-descript relative w-full h-max border border-gray-400 rounded p-3">
                        <div class="flex flex-col h-full">
                            <textarea id="TxtAreaEmail" class="rar outline-none w-full h-48 p-1 border-b border-gray-300" type="text" placeholder="Say something here..."></textarea>
                        </div>
                    </div>
                    <div id="imgContEmail" class=" hidden flex overflow-x-auto w-full"></div>
                    <div id="fileContEmail" class="hidden"></div>
                    <p class="text-sm text-red-400 hidden" id="errorMsgEM">Sorry we only allow images that has file extension of
                        jpg,jpeg,png</p>
                    <label class="flex justify-start items-center gap-3 mt-1">
                        <i id="galleryIcon" class="fa-solid fa-image"></i>
                        <i id="fileIcon" class="fa-solid fa-paperclip"></i>
                    </label>
                    <input id="imageSelection" type="file" class="hidden">
                    <input id="fileSelection" type="file" class="hidden">
                </div>

                <!-- Footer -->
                <div class="modal-footer flex items-end flex-row-reverse px-3 mt-2">
                    <button type="submit" id="sendEmail" class="bg-accent h-full py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
                    <button type="button" class="cancelEmail py-2 rounded px-5  hover:bg-slate-400 hover:text-white">Cancel</button>
                </div>
            </div>
        </form>
    </div>

</section>

<script>
    $(document).ready(function() {
        $('#daterange').daterangepicker();
    });

    $('#createNewEmailBtn').on("click", function() {
        $('#modalEmail').removeClass("hidden");
    })
    $('.cancelEmail').on('click', function() {
        console.log('hello')
        $('#modalEmail').addClass("hidden");
    })
    $.getScript("./email/sendMail.js");
</script>