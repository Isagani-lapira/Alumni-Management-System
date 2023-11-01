 <?php session_start(); ?>
 <!-- ANNOUNCEMENT CONTENT -->
 <section id="announcement-tab" class=" mx-auto lg:mx-8">
     <h1 class="text-xl font-extrabold">COLLEGE NEWS AND UPDATES</h1>
     <p class="text-grayish">
         Here you can make announcement that everyone can see.
     </p>
     <div class="flex flex-row justify-end my-4">
         <label id="announcementBtn" for="add-announcement-modal" class="daisy-btn daisy-btn-primary rounded-lg">Make Announcement
         </label>
     </div>


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


     <!-- Modal for make announcement  -->
     <input type="checkbox" id="add-announcement-modal" class="daisy-modal-toggle">
     <div class="daisy-modal">
         <div class="daisy-modal-box w-11/12 max-w-4xl ">
             <!-- Exit -->
             <form method="dialog">
                 <label for="add-announcement-modal" class="daisy-btn daisy-btn-sm daisy-btn-circle daisy-btn-ghost absolute right-2 top-2">✕</label>
             </form>
             <!-- End Exit Form -->
             <h3 class="font-bold text-xl text-center"> <?= $_SESSION['colCode'] ?> UPDATE</h3>


             <!-- make alumni of the year post -->
             <form action="" id="add-announcement-form" method="POST">
                 <div id="" class=" text-greyish_black flex flex-col px-12 s">

                     <!-- TODO remove this later -->
                     <!-- <p class="mb-4"> <span class="text-red-600 font-bold">*</span> Required</p> -->
                     <span class="daisy-label-text font-bold daisy-label ">Choose a cover image to showcase</span>

                     <!-- Placeholder for Cover Image -->
                     <div class="w-full h-80 relative group rounded-sm">
                         <img id="cover-img-preview" class="w-full bg-gray-100 rounded-sm object-contain max-h-full h-full block" src="" alt="">
                         <!-- Cover Image Input -->
                         <div class="daisy-form-control w-full max-w-xs absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                             <label for="cover-image" class="daisy-label">
                             </label>
                             <input class="daisy-file-input daisy-file-input-bordered w-full max-w-xs" id="cover-image" type="file" accept=".jpg" name="cover-image">
                             <label class="daisy-label">
                                 <span class="daisy-label-text-alt">Use JPG File Format</span>
                             </label>
                         </div>
                     </div>

                     <div class="daisy-form-control w-full max-w-xs">
                         <label class="font-bold daisy-label" for="title">
                             <span class="daisy-label-text"> Title: </span>
                             <span class="daisy-label-text-alt"></span>
                         </label>
                         <input id="title" name="title" class="form-input block rounded" type="text" placeholder="Make an interesting title">
                     </div>

                     <div class="daisy-form-control w-full max-w-xs">
                         <label class="font-bold daisy-label" for="description">
                             <span class="daisy-label-text"> Description:</span>
                         </label>
                         <textarea id="description" cols="60" name="description" class="form-textarea daisy-textarea daisy-textarea-borderedj block rounded resize max-w-full" id="description" placeholder="Add your description here..."></textarea>
                     </div>


                     <div class="daisy-form-control w-full max-w-xs">
                         <label class="daisy-label font-bold daisy-label-text">Add more images ( Optional )</label>
                         <div id="collectionContainer" class="flex flex-wrap gap-2">
                             <!-- adding image -->
                             <div id="addImgCollection" class=" w-24 h-24 rounded-md border border-accent flex justify-center items-center">
                                 <label for="collectionFile">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 20 20">
                                         <path fill="currentColor" d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20a10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16a8 8 0 0 0 0 16z"></path>
                                     </svg>
                                 </label>
                                 <input type="file" name="" id="collectionFile" accept="image/*" class="hidden">
                             </div>
                         </div>
                     </div>


                     <div class="flex flex-wrap gap-4 py-4 justify-end">
                         <button class="btn-tertiary bg-transparent " type="reset" id="reset-btn">Reset Form</button>
                         <button type="submit" class="btn-primary">Add Announcement</button>
                     </div>
                 </div>
             </form>
             <!-- End Add FORM -->

         </div>
     </div>

     <!-- View Modal -->
     <!-- Put this part before </body> tag -->
     <input type="checkbox" id="view-modal" class="daisy-modal-toggle" />
     <div class="daisy-modal">
         <div class="daisy-modal-box w-11/12 max-w-5xl p-0">
             <div id="announcementDetails" class="w-full overflow-y-auto bg-white rounded-md py-3 px-12 text-greyish_black flex flex-col gap-2">
                 <!-- header -->
                 <div class="flex gap-2 items-center py-2">
                     <img src="data:image/jpeg;base64, <?= $_SESSION['colLogo'] ?>" alt="logo of the college" class="w-10 h-10">
                     <span class="ml-2 text-xl font-bold"><?= $_SESSION['colCode'] ?> Update</span>
                 </div>

                 <!-- headline image -->
                 <img id="headline_img" class="h-60 object-cover bg-gray-300 rounded-md" alt="" src="<>">

                 <p class="text-sm text-gray-500">Date Posted: <span id="announceDatePosted"></span></p>
                 <p id="announcementTitle" class="text-2xl text-greyish_black font-black"></p>
                 <pre id="announcementDescript" class=" text-gray-500 text-justify w-full"></pre>

                 <!-- images container -->
                 <div id="imagesContainer" class="my-2">
                     <p class="font-semibold text-blue-400">More images available</p>
                     <div id="imagesWrapper" class="flex flex-wrap gap-2">
                     </div>
                 </div>
             </div>




             <!-- Skeleton Pulse -->
             <!-- <div class="animate-pulse flex space-x-4">
                <div class="rounded-full bg-slate-200 h-10 w-10"></div>
                <div class="flex-1 space-y-6 py-1">
                    <div class="h-2 bg-slate-200 rounded"></div>
                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="h-2 bg-slate-200 rounded col-span-2"></div>
                            <div class="h-2 bg-slate-200 rounded col-span-1"></div>
                        </div>
                        <div class="h-2 bg-slate-200 rounded"></div>
                    </div>
                </div>

            </div> -->


         </div>
         <label class="daisy-modal-backdrop" for="view-modal">Close</label>
     </div>
     <!-- End View Modal -->
 </section>



 <script type="module" src="./announcements/announcement.js"></script>