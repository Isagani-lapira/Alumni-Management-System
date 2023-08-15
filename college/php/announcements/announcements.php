 <!-- ANNOUNCEMENT CONTENT -->
 <section id="announcement-tab" class=" mx-auto lg:mx-8">
     <h1 class="text-xl font-extrabold">ANNOUNCEMENT</h1>
     <p class="text-grayish">Here you can check all shared messages with whole University</p>
     <button id="announcementBtn" class="bg-accent font-light block text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg">CREATE NEW POST
     </button>


     <!-- Announcement content -->
     <div id="announcement-content">
     </div>

     <div class="flex items-center border-t-2 border-gray-400 mt-10">

         <div class="m-2 p-1">
             <span class="font-semibold">Total Post</span>
             <p class="text-5xl font-bold">12</p>
         </div>

         <div class="m-2 p-1">
             <p class="text-sm font-thin">Course</p>
             <!-- college selection -->
             <select name="college" id="announcementCol" class="w-full border border-grayish p-2 rounded-lg">
                 <option value="" selected disabled hidden>BS Computer Science</option>
             </select>
         </div>


         <div class="m-2 p-1">
             <p>Show post (from - to)</p>
             <div class="relative">
                 <input type="text" name="daterange" id="daterange" value="01/01/2018 - 01/15/2018" class="rounded " />
                 <img class="h-5 w-5  absolute right-4 top-2" src="../assets/icons/calendar.svg" alt="">
             </div>

         </div>

     </div>




     <!-- recent post -->
     <div class="w-full">
         <!-- Post -->
         <div class="post p-3 w-2/3">
             <div class="border shadow-lg center-shadow p-3 rounded-md">
                 <div class="flex justify-start items-center">
                     <div class="flex items-center">
                         <img class="h-12 border-2 border-accent rounded-full" src="../images/Mr.Jayson.png" alt="">
                         <p class="text-start px-3 text-sm font-semibold">Jayson Batoon</p>
                     </div>
                     <img class="ml-auto" src="../assets/more_horiz.png" alt="">
                 </div>

                 <p class="text-sm mt-2">BulSU Laboratory High School Moving Up Ceremony | June 1, 2023</p>
                 <img class="my-2 rounded-md" src="" alt="">
                 <div class="flex py-2 items-center">
                     <img class="h-5" src="../assets/icons/heart.png" alt="">
                     <span class="ms-2 text-sm">1,498</span>
                     <img class="ms-2 h-5" src="../assets/icons/comment.png" alt="">
                     <span class="ms-2 text-sm">3,000</span>
                 </div>
             </div>
         </div>

         <!-- Post -->
         <div class="post p-3 w-2/3">
             <div class="border shadow-lg  center-shadow p-3 rounded-md">
                 <div class="flex justify-start items-center">
                     <div class="flex items-center">
                         <img class="h-12 border-2 border-accent rounded-full" src="../images/Mr.Jayson.png" alt="">
                         <p class="text-start px-3 text-sm font-semibold">Jayson Batoon</p>
                     </div>
                     <img class="ml-auto" src="../assets/more_horiz.png" alt="">
                 </div>

                 <p class="text-sm mt-2">BulSU Laboratory High School Moving Up Ceremony | June 1, 2023</p>
                 <img class="my-2 rounded-md" src="" alt="">
                 <div class="flex py-2 items-center">
                     <img class="h-5" src="../assets/icons/heart.png" alt="">
                     <span class="ms-2 text-sm">1,498</span>
                     <img class="ms-2 h-5" src="../assets/icons/comment.png" alt="">
                     <span class="ms-2 text-sm">3,000</span>
                 </div>
             </div>
         </div>
     </div>



     <!-- modal -->
     <div id="createNewPostModal" class="fixed top-0 left-0 inset-0 h-full w-full bg-gray-700 bg-opacity-50 hidden">
         <div class="h-full flex items-center justify-center 
      text-grayish  ">
             <!-- Modal Content -->
             <div class="modal-container space-y-5 h-1/3 w-1/3  bg-white rounded-lg p-3">
                 <div class="modal-header py-5">
                     <h1 class="text-accent text-2xl text-center font-bold">Create New Post</h1>
                 </div>
                 <div class="modal-body px-3">
                     <!-- header part -->
                     <p class="font-semibold text-sm">College</p>
                     <!-- <div class="w-full border border-gray-400 rounded flex px-5 py-2"> -->
                     <select name="collegePost" id="collegePost" class="w-full outline-none">
                         <option value="all" selected>All colleges</option>
                         <?php
                            // require_once '../PHP_process/connection.php';
                            // $query = "SELECT * FROM `college`";
                            // $result = mysqli_query($mysql_con, $query);
                            // $rows = mysqli_num_rows($result);
                            // if ($rows > 0) {
                            //     while ($data = mysqli_fetch_assoc($result)) {
                            //         $colCode = $data['colCode'];
                            //         $colName = $data['colname'];
                            //         echo '<option value="' . $colCode . '"class="w-full">' . $colName . '</option>';
                            //     }
                            // } else echo '<option>No college available</option>';
                            ?>
                     </select>
                 </div>
                 <!-- body part -->
                 <label class="font-semibold text-sm mt-2" for="TxtAreaAnnouncement">Description</label>
                 <div class="flex flex-col h-full pb-10">
                     <textarea id="TxtAreaAnnouncement" class="text-black rar outline-none w-full h-5/6 p-1 pb-10" type="text" placeholder="Say something here..."></textarea>
                 </div>
                 <label for="fileGallery" class="block">
                     <i id="galleryLogo" class="fa-regular fa-image fa-xl"></i>
                     <input id="fileGallery" type="file" class="hidden" />
                 </label>
                 <!-- Footer -->
                 <div class="modal-footer flex items-end flex-row-reverse px-3">
                     <button id="postBtn" class="bg-accent py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
                     <button class="cancel py-2 rounded px-5 text-grayish border border-slate-400 hover:bg-slate-400 hover:text-white">Cancel</button>
                 </div>
             </div>
             <!-- End Modal Content -->

         </div>
     </div>

 </section>


 <script>
     $(document).ready(function() {
         $('#daterange').daterangepicker();
     });
     //  Handles button clicks
     $('#announcementBtn').on("click", function() {
         $('#createNewPostModal').removeClass("hidden");
     })
     $('.cancel').on("click", function() {
         $('#createNewPostModal').addClass("hidden")
     })
 </script>