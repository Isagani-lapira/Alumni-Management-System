<?php
session_start();

// Get the total of the posts
require "../php/connection.php";
$username = $_SESSION["username"];
$sql = "SELECT COUNT(*) AS total FROM post WHERE username = '$username'";
$result = mysqli_query($mysql_con, $sql);
$row = mysqli_fetch_assoc($result);
$total = $row["total"];


?>


<!-- Make post CONTENT -->
<section id="announcement-tab" class=" mx-auto lg:mx-8">
    <h1 class="text-xl font-extrabold">POST</h1>
    <p class="text-grayish">
        Here you can check all the post you have and can create new posts.
    </p>

    <div class="flex justify-end">
        <!-- The button to open modal -->
        <label for="newPostModal" id="announcementBtn" class="daisy-btn bg-accent font-light text-sm ml-auto text-white hover:bg-darkAccent px-3 py-3 rounded-lg">CREATE NEW POST
        </label>
    </div>



    <!-- Total Post -->
    <section class="flex items-center border-t-2 border-gray-400 mt-10">

        <div class="m-2 p-1">
            <span class="font-semibold">Total Post</span>
            <p id="totalPost" class="totalPost text-5xl font-bold">
                <?= $total ?>

            </p>
        </div>


        <!-- Check if this is still possible -->
        <!-- <div class="m-2 p-1">
             <p>Show post (from - to)</p>
             <div class="relative border-black">
                 <input type="text" name="daterange" id="daterange" value="01/01/2018 - 01/15/2018" class="rounded  w-full p-2" />
                 <i class="fa-solid fa-calendar-days absolute right-4 top-3"></i>
             </div> -->

        </div>

    </section>
    <!-- End Total Post -->

    <!-- <tr class="hidden">
                     <td class="text-center" colspan="5">No available post</td>
                 </tr> -->

    <!-- Table Start -->
    <div id="announcementContainer" class="w-full text-xs ">
        <table id="postTable" class="w-full center-shadow">
            <thead class="rounded-tl-lg sorting_disabled">
                <tr class="bg-accent text-white">
                    <th class="rounded-tl-lg">Message</th>
                    <th>No. of likes</th>
                    <th>No. of comments</th>
                    <th>Date posted</th>
                    <th class="rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody id="postTBody">
                <tr class="loading-row">
                    <td colspan="5" class="text-center">
                        <span class="loading-table daisy-loading daisy-loading-dots daisy-loading-lg"></span>
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Loading...</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Table End -->
        <!-- <p id="noPostMsg" class="text-blue-400 hidden text-lg text-center">No available post</p>
         <div id="paginationBtnPost" class="flex justify-end gap-2 px-2 mt-2">
             <button id="prevPost" class="border border-accent hover:bg-accent hover:text-white px-3 py-1 rounded-md">Previous</button>
             <button id="nextPost" class="bg-accent hover:bg-darkAccent text-white px-5 py-1 rounded-md">Next</button>
         </div> -->
    </div>
    <!-- Table End -->



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

    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="newPostModal" class="daisy-modal-toggle" />
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-6xl">
            <div class="modal-header py-5">
                <h2 class="text-accent text-2xl text-center font-bold">Create New Post</h2>
            </div>
            <form action="" method="POST" id="make-post-form">
                <label for="description">Description</label>
                <div class="modal-descript relative w-full h-2/3 border border-gray-400 rounded p-3">
                    <div class="flex flex-col h-full">
                        <textarea name="description" id="description" class=" outline-none w-full h-5/6 p-1" type="text" placeholder="Say something here..."></textarea>
                        <div id="imgContPost" class=" hidden flex overflow-x-scroll w-full"></div>
                        <p class="text-sm text-red-400 hidden" id="errorMsg">Sorry we only allow images that has file extension of
                            jpg,jpeg,png</p>
                    </div>

                    <label for="fileGallery">
                        <span id="galleryLogo" class="absolute bottom-1 left-1">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="blue" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7C17 7.53043 16.7893 8.03914 16.4142 8.41421C16.0391 8.78929 15.5304 9 15 9C14.4696 9 13.9609 8.78929 13.5858 8.41421C13.2107 8.03914 13 7.53043 13 7C13 6.46957 13.2107 5.96086 13.5858 5.58579C13.9609 5.21071 14.4696 5 15 5C15.5304 5 16.0391 5.21071 16.4142 5.58579C16.7893 5.96086 17 6.46957 17 7Z" fill="#BCBCBC"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.943 0.25H11.057C13.366 0.25 15.175 0.25 
                    16.587 0.44C18.031 0.634 19.171 1.04 20.066 1.934C20.961 2.829 21.366 3.969 21.56 5.414C21.75 6.825 21.75 8.634 21.75 10.943V11.031C21.75 12.94 21.75 14.502 21.646 15.774C21.542 17.054 21.329 18.121 20.851 19.009C20.641 19.4 20.381 19.751 20.066 20.066C19.171 20.961 18.031 21.366 16.586 21.56C15.175 21.75 13.366 21.75 11.057 
                    21.75H10.943C8.634 21.75 6.825 21.75 5.413 21.56C3.969 21.366 2.829 20.96 1.934 20.066C1.141 19.273 0.731 18.286 0.514 17.06C0.299 15.857 0.26 14.36 0.252 12.502C0.25 12.029 0.25 11.529 0.25 11.001V10.943C0.25 8.634 0.25 6.825 0.44 5.413C0.634 3.969 1.04 2.829 1.934 1.934C2.829 1.039 3.969 0.634 5.414 0.44C6.825 0.25 8.634 0.25 10.943 0.25ZM5.613 1.926C4.335 2.098 3.564 2.426 2.995 2.995C2.425 3.565 2.098 4.335 1.926 5.614C1.752 6.914 1.75 8.622 1.75 11V11.844L2.751 10.967C3.1902 10.5828 3.75902 10.3799 4.34223 10.3994C4.92544 10.4189 5.47944 10.6593 5.892 11.072L10.182 15.362C10.5149 15.6948 10.9546 15.8996 11.4235 15.9402C11.8925 15.9808 12.3608 15.8547 12.746 15.584L13.044 15.374C13.5997 14.9835 14.2714 14.7932 14.9493 14.834C15.6273 14.8749 16.2713 15.1446 16.776 15.599L19.606 18.146C19.892 17.548 20.061 16.762 20.151 15.653C20.249 14.448 20.25 12.946 20.25 11C20.25 8.622 20.248 6.914 20.074 5.614C19.902 4.335 19.574 3.564 19.005 2.994C18.435 2.425 17.665 2.098 16.386 1.926C15.086 1.752 13.378 1.75 11 1.75C8.622 1.75 6.913 1.752 5.613 1.926Z" fill="#BCBCBC"></path>
                            </svg>
                        </span>
                    </label>
                    <input id="fileGallery" type="file" class="hidden">
                </div>
                <div class="daisy-modal-action">
                    <label for="newPostModal" class="daisy-btn">Cancel</label>
                    <button type="submit" class="daisy-btn btn-primary px-4 font-bold">POST</button>


                </div>
            </form>
        </div>
    </div>

    <input type="checkbox" id="archive-modal" class="daisy-modal-toggle" />
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-6xl">
            <h3 class="text-lg font-bold">Archive Modal!</h3>
            <p class="py-4">This modal works with a hidden checkbox!</p>

            <div class="daisy-modal-action">
                <label for="archive-modal" class="daisy-btn">Close!</label>
            </div>
        </div>
        <label class="daisy-modal-backdrop" for="archive-modal">Close</label>
    </div>
    <!-- Put this part before </body> tag -->

    <input type="checkbox" id="view-modal" class="daisy-modal-toggle" />
    <div class="daisy-modal">
        <div class="daisy-modal-box w-11/12 max-w-6xl">
            <h3 class="text-lg font-bold">

            </h3>



            <div class="postStatus bg-white rounded-md w-2/6 p-5 flex flex-col gap-3">
                <div class="flex justify-between">
                    <div class="flex items-center">
                        <img id="profileStatusImg" class="w-10 h-10 rounded-full" alt="" src="">

                        <div class="px-2 text-greyish_black">
                            <p id="postFullName" class="font-bold text-sm"></p>
                            <span id="postUN" class="italic accountUN text-gray-400 text-sm"></span>
                        </div>
                    </div>

                    <iconify-icon class="closeStatusPost cursor-pointer text-gray-400 hover:text-gray-500 hover:h-7 hover:w-7" icon="ei:close" width="24" height="24"></iconify-icon>
                </div>

                <!-- description -->
                <div>
                    <pre id="statusDescript">

                    </pre>
                </div>

                <!-- date -->
                <span id="statusDate" class="text-xs text-gray-500">

                </span>

                <!-- comment -->
                <div class="flex-col text-sm border-t border-gray-400 py-2 h-full overflow-y-auto">
                    <div class="flex gap-2 text-gray-500 text-xs">
                        <p>Likes: <span id="statusLikes">0</span></p>
                        <p>Comments: <span id="statusComment">0</span></p>
                    </div>

                    <div id="commentStatus" class="flex flex-col gap-2 p-2 mt-2"></div>
                </div>
            </div>
            <div class="daisy-modal-action">
                <label for="view-modal" class="daisy-btn">Close!</label>
            </div>
        </div>
        <label class="daisy-modal-backdrop" for="view-modal">Close</label>
    </div>
</section>


<script src="./make-post/post.js" type="module"></script>