<?php session_start()
?>
<section id="email-section" class="p-8">
    <!-- Email content -->
    <h1 class="text-xl font-extrabold">EMAIL</h1>
    <p class="text-grayish">Here you can check all the post you have and can create new post</p>
    <div class="flex flex-row justify-end mt-5 text-end">
        <label for="daisy-email-modal" id="btnEmail" class="bg-accent font-light block text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg">CREATE
            NEW
            EMAIL
        </label>
    </div>
    <hr class="h-px my-3 bg-grayish border-0 dark\:bg-gray-700" />

    <div class="flex items-center">

        <div class="m-2 p-1">
            <span class="font-semibold">Total Emailed</span>
            <?php
            require_once '../php/connection.php';
            $query = 'SELECT * FROM `email` WHERE `personID` = "' . $_SESSION['personID'] . '" ';
            $result = mysqli_query($mysql_con, $query);
            $row = mysqli_num_rows($result);
            echo '<p class="text-5xl font-bold" id="totalEmailed">' . $row . '</p>';
            ?>
        </div>

        <div class="m-2 p-1">
            <!-- <p class="text-sm font-thin">College</p> -->
            <!-- college selection -->
            <!-- <select name="college" id="emCol" class="w-full border border-grayish p-2 rounded-lg">
                    <option value="" selected>All</option>
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
                </select> -->
        </div>


        <div class="m-2 p-1">
            <p>Show email (from - to)</p>
            <div class="w-full flex items-center border border-grayish p-2 rounded-lg">
                <input type="text" name="emDateRange" id="emDateRange" value="Select a date" />
                <label class="" for="emDateRange">
                    <img class="h-5 w-5" src="../../assets/icons/calendar.svg" alt="">
                </label>
            </div>

        </div>

    </div>


    <!-- recent email -->
    <p class="mt-5 font-semibold text-greyish_black">Recent Email</p>
    <table id="emailTable" class="table-auto w-full text-xs font-normal text-gray-800 center-shadow">
        <thead class="bg-accent text-white">
            <tr>
                <th class="text-start rounded-tl-md">SUBJECT</th>
                <th class="text-start rounded-tl-md">RECIPIENTS</th>
                <th class="text-start">DATE</th>
                <th class="text-start rounded-tr-md">VIEW</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</section>

<!-- modal add email message -->
<input type="checkbox" id="daisy-email-modal" class="daisy-modal-toggle" />
<div class="daisy-modal">
    <div class="daisy-modal-box w-full max-w-5xl">

        <!-- Form -->
        <form id="emailForm" class="h-max bg-white rounded-lg p-3">
            <input type="hidden" id="college-hidden" name="college" value='<?= $_SESSION['colCode'] ?>'>
            <div class="w-full h-full">
                <div class="modal-header py-5">
                    <h1 class="text-accent text-2xl text-center font-bold">Send Mail</h1>
                </div>
                <div class="modal-body px-3 h-1/2">

                    <!-- header part -->
                    <div class="flex gap-2 justify-start mb-2">
                        <p class="font-semibold text-sm">Recipient</p>
                        <input type="radio" id="groupEM" name="recipient" checked value="groupEmail">
                        <label for="groupEM">Group</label>

                        <input type="radio" id="individEM" name="recipient" value="individualEmail">
                        <label for="individEM">Individual</label>
                    </div>


                    <div id="groupEmail" class="flex gap-1">

                        <div class="selectColWrapper">
                            <select name="selectColToEmail" id="selectColToEmail" class=" form-select w-full">
                                <?php
                                require_once '../php/connection.php';
                                $query = "SELECT * FROM `college`";
                                $result = mysqli_query($mysql_con, $query);
                                $rows = mysqli_num_rows($result);

                                if ($rows > 0) {
                                    while ($data = mysqli_fetch_assoc($result)) {
                                        $colCode = $data['colCode'];
                                        $colName = $data['colname'];

                                        echo '<option selected value="' . $colCode . '">' . $colName . '</option>';
                                    }
                                } else echo '<option>No college available</option>';
                                ?>
                            </select>
                        </div>


                        <div class="flex gap-1 items-center">

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
                        <div id="suggestionContainer" class="absolute p-2 w-full rounded-md z-10 h-56 overflow-y-auto hidden"></div>
                    </div>

                    <p class="font-semibold text-sm mt-2">Subject</p>
                    <input class="focus:outline-none w-full border border-gray-400 rounded-md py-2 px-1" type="text" name="emailSubj" id="emailSubj" placeholder="Introduce something great!">

                    <!-- body part -->
                    <p class="font-semibold text-sm mt-2">Description</p>
                    <div class="modal-descript relative w-full h-max  border-gray-400 rounded">
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
                    <!-- accept jpeg or jpg for imageSelection -->

                    <input id="imageSelection" type="file" class="hidden" accept="image/jpeg,image/jpg">

                    <input id="fileSelection" type="file" class="hidden" accept="application/pdf">
                </div>

                <!-- Footer -->
                <!-- <div class="modal-footer flex items-end flex-row-reverse px-3 mt-2">
                    <button type="button" class="cancelEmail py-2 rounded px-5  hover:bg-slate-400 hover:text-white">Cancel</button>
                </div> -->
                <div class="daisy-modal-action">

                    <label for="daisy-email-modal" class=" cancelEmail daisy-btn">Cancel</label>

                    <button type="submit" id="sendEmail" class="bg-accent h-full py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent daisy-btn daisy-btn-primary">Send Email</button>
                </div>

            </div>
        </form>

        <!-- end form -->



    </div>
    <label class="daisy-modal-backdrop" for="daisy-email-modal">Close</label>
</div>
<!-- Email Detail modal -->
<!-- email details -->
<div class=" bg-black bg-opacity-50 fixed inset-0 flex flex-col items-center p-3 z-50 emailDetailModal hidden">
    <div class="w-4/5 rounded-md h-4/5 bg-white p-5 overflow-y-auto">
        <header class="flex items-center justify-between">
            <div class="flex gap-2 cursor-pointer closeEmailModal">
                <iconify-icon icon="fluent-mdl2:back" class="text-gray-700" width="24" height="24"></iconify-icon>
                <h2 class="font-bold text-gray-500">Back</h2>
            </div>
            <div class="text-sm text-gray-500 italic">
                <span>Email sent: </span>
                <span class="dateData"></span>
            </div>
        </header>
        <!-- body -->
        <div class="flex flex-col mt-3 text-gray-500 emailModal">
            <h2 class="font-semibold">Subject: <span class="text-based font-normal subject"></span></h2>
            <h2 class="font-semibold">To: <span class="text-based font-normal to"></span></h2>
            <div class="h-4/5">
                <h2 class="font-semibold">Message:</h2>
                <pre class=" text-gray-500 text-justify w-full border border-gray-400 rounded-md overflow-y-auto p-5 messageEmail"></pre>
            </div>
        </div>
    </div>
</div>






<!-- <script type="module" src="./email/email.js"></script> -->
<script type="module" src="./email/sendMail.js"></script>