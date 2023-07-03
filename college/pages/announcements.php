 <!-- ANNOUNCEMENT CONTENT -->
 <section id="announcement-tab" class=" mx-auto lg:mx-8">
     <h1 class="text-xl font-extrabold">ANNOUNCEMENT</h1>
     <p class="text-grayish">Here you can check all shared messages with whole University</p>
     <button id="btnAnnouncement" class="bg-accent font-light block text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg">CREATE NEW POST
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
             <div class="w-full flex border border-grayish p-2 rounded-lg">
                 <input type="text" name="daterange" id="daterange" value="01/01/2018 - 01/15/2018" />
                 <label class="" for="daterange">
                     <img class="h-5 w-5" src="../assets/icons/calendar.svg" alt="">
                 </label>
             </div>

         </div>

     </div>




     <!-- recent post -->
     <div class="w-full">
         <!-- Post -->
         <div class="post p-3 w-2/3">
             <div class="center-shadow p-3 rounded-md">
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
             <div class="center-shadow p-3 rounded-md">
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






 </section>