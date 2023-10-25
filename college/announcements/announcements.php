 <!-- ANNOUNCEMENT CONTENT -->
 <section id="announcement-tab" class=" mx-auto lg:mx-8">
     <h1 class="text-xl font-extrabold">ANNOUNCEMENT</h1>
     <p class="text-grayish">
         Here you can make announcement that everyone can see.
     </p>
     <button id="announcementBtn" class="bg-accent font-light block text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg">CREATE NEW POST
     </button>


     <!-- Total Post Content -->
     <!-- <section class="flex items-center border-t-2 border-gray-400 mt-10">

         <div class="m-2 p-1">
             <span class="font-semibold">Total Post</span>
             <p id="totalPost" class="totalPost text-5xl font-bold">0</p>
         </div>


     </section> -->

     <!-- Table for the announcement -->
     <table class="w-full text-sm mt-5 center-shadow daisy-table daisy-table-zebra" id="annoucementTable">
         <thead>
             <tr class="bg-accent text-white">
                 <th class=" rounded-tl-lg">Title</th>
                 <th>Description</th>
                 <th>Date Posted</th>
                 <th class=" rounded-tr-lg">Action</th>
             </tr>
         </thead>
         <tbody id="announcementList" class="text-xs"></tbody>
     </table>
     <p id="noAvailMsgAnnouncement" class="text-center text-blue-400 text-lg hidden">No available data</p>
     <!-- <div class="flex flex-wrap gap-2 justify-end my-2">
         <button id="prevAnnouncement" class="tex-sm px-3 py-1 rounded-md border border-accent">Previous</button>
         <button id="nextAnnouncement" class="text-white bg-accent tex-sm px-4 py-1 rounded-md">Next</button>
     </div> -->
     <!-- End table for the announcement  -->


     <!-- modal -->
     <div id="createNewPostModal" class="fixed top-0 left-0 inset-0 h-full w-full bg-gray-700 bg-opacity-50 hidden 
     ">
         <div class="h-full flex items-center justify-center 
      text-grayish  ">
             <!-- Modal Content -->
             <div class="modal-container space-y-5    modal-container w-1/3 h-2/3 bg-white rounded-lg p-3">
                 <div class="modal-header py-5">
                     <h1 class="text-accent text-2xl text-center font-bold">Create New Post</h1>
                 </div>
                 <!-- Copied Description -->
                 <p class="font-semibold text-sm mt-2">Description</p>
                 <div class="modal-descript relative w-full h-2/3 border border-gray-400 rounded p-3">
                     <div class="flex flex-col h-full">
                         <textarea id="TxtAreaAnnouncement" class="rar outline-none w-full h-5/6 p-1" type="text" placeholder="Say something here..."></textarea>
                         <div id="imgContPost" class=" hidden flex overflow-x-scroll w-full"></div>
                         <p class="text-sm text-red-400 hidden" id="errorMsg">Sorry we only allow images that has file extension of
                             jpg,jpeg,png</p>
                     </div>

                     <label for="fileGallery">
                         <span id="galleryLogo" class="absolute bottom-1 left-1">
                             <i id="galleryLogo" class="fa-regular fa-image fa-xl"></i>
                         </span>
                     </label>
                     <input id="fileGallery" type="file" class="hidden" />
                 </div>
                 <!-- End Description -->
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



 <script type="module" src="./announcements/announcement.js"></script>