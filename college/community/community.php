<?php session_start(); ?>

<?php

$profilePicture = $_SESSION['profilePicture'];
$personID = $_SESSION['personID'];


?>
<!--community content -->
<section id="community-content">

    <!--community content -->
    <div id="community-tab" class="p-5 ">
        <div class="flex">
            <div class="w-4/6">
                <!-- college -->
                <select id="communityCollege" class="w-1/2 p-2 my-5 outline-none">

                    <?php
                    echo '<option value="' . $_SESSION['colCode'] . '">' . $_SESSION['colname'] . '</option>';

                    ?>
                </select>

                <!-- report number -->
                <select id="communityReportFilter" class="outline-none w-1/3 p-2  my-5">
                    <option value="" selected>All</option>
                    <option value="Nudity">Nudity</option>
                    <option value="Violence">Violence</option>
                    <option value="Terrorism">Terrorism</option>
                    <option value="Hate Speech">Hate Speech</option>
                    <option value="False Information">False Information</option>
                    <option value="Suicide or Self-injury">Suicide or Self-injury</option>
                    <option value="Harassment">Harassment</option>
                </select>

                <div id="communityContainer" class="p-5 flex flex-col gap-3 no-scrollbar"></div>
                <p id="noPostMsgCommunity" class="text-blue-400 text-center hidden">No available post</p>
            </div>
            <!-- report graph -->
            <div class=" w-2/5 border-l border-gray-300">
                <p class="text-center font-bold text-xl">Report Graph</p>
                <canvas id="reportChart"></canvas>
            </div>
        </div>

    </div>


    <!-- deletion post modal -->
    <div class="deleteModalPost modal fixed inset-0 z-50 flex justify-center p-3 hidden">
        <div class="modal-container w-2/5 h-max bg-white rounded-lg text-greyish_black p-3 center-shadow slide-bottom">
            <h3 class="text-lg text-greyish_black text-center ">Are you sure you want to delete post this post?</h3>
            <input id="reasonForDel" class="text-gray-400 py-2 w-full text-center" type="text" placeholder="State your reason for deleting">
            <div class="flex items-center justify-end my-2 gap-2">
                <button class="text-gray-400 hover:text-gray-500 cancelDeletionAdmin">Cancel</button>
                <button id="deleteByAdminBtn" class="bg-accent py-1 px-4 text-white hover:bg-darkAccent rounded-lg">Delete</button>
            </div>
        </div>
    </div>

    <!-- comment -->
    <div id="commentPost" class="post modal fixed inset-0 flex justify-center p-3 z-50 hidden">
        <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 flex flex-col gap-1 slide-bottom">
            <!-- close button -->
            <span class="flex justify-end">
                <iconify-icon id="closeComment" class="rounded-full cursor-pointer p-2 hover:bg-gray-300" icon="ep:close" style="color: #686b6f;" width="20" height="20"></iconify-icon>
            </span>

            <div class="flex gap-2 items-center">
                <img id="postProfile" class="h-10 w-10 rounded-full" src="../assets/icons/person.png" alt="">
                <div>
                    <p id="postFullname" class="text-black"></p>
                    <p id="postUsername" class="text-xs text-gray-400 font-thin"></p>
                </div>
            </div>

            <div id="replacementComment" class="border-l-2 border-gray-400 w-max ml-5 p-3">
                <p class="text-center text-sm italic text-gray-400">Reply to
                    <span id="replyToUsername" class=" font-semibold text-blue-500">username</span>
                </p>
            </div>
            <div class="flex gap-2 ">
                <?php
                if ($profilePicture == "") {
                    echo '<img src="../assets/icons/person.png" alt="Profile Icon" class="w-10 h-10 profile-icon rounded-full" />';
                } else {
                    $srcFormat = 'data:image/jpeg;base64,' . $profilePicture;
                    echo '<img src="' . $srcFormat . '" alt="Profile Icon" class="w-10 h-10 profile-icon rounded-full" />';
                }
                ?>
                <textarea id="commentArea" class="w-full h-28 outline-none text-gray-400" placeholder="Comment your thought!"></textarea>
            </div>

            <button id="commentBtn" class="px-3 py-2 rounded-lg bg-red-950 text-white font-semibold block ml-auto text-sm" disabled>Comment</button>
        </div>
    </div>


    <!-- modal -->
    <div id="modal" class="modal fixed inset-0 h-full w-full flex items-center justify-center 
      text-grayish  top-0 left-0 hidden">
        <div class="modal-container w-1/3 h-2/3 bg-white rounded-lg p-3">
            <div class="modal-header py-5">
                <h1 class="text-accent text-2xl text-center font-bold">Create New Post</h1>
            </div>
            <div class="modal-body px-3">

                <!-- header part -->
                <p class="font-semibold text-sm">College</p>
                <div class="w-full border border-gray-400 rounded flex px-5 py-2">
                    <select name="collegePost" id="collegePost" class="w-full outline-none">
                        <!-- <option value="all" selected>All colleges</option> -->
                        <?php
                        echo '<option value="' . $_SESSION['colCode'] . '">' . $_SESSION['colname'] . '</option>';

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
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="blue" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 7C17 7.53043 16.7893 8.03914 16.4142 8.41421C16.0391 8.78929 15.5304 9 15 9C14.4696 9 13.9609 8.78929 13.5858 8.41421C13.2107 8.03914 13 7.53043 13 7C13 6.46957 13.2107 5.96086 13.5858 5.58579C13.9609 5.21071 14.4696 5 15 5C15.5304 5 16.0391 5.21071 16.4142 5.58579C16.7893 5.96086 17 6.46957 17 7Z" fill="#BCBCBC" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.943 0.25H11.057C13.366 0.25 15.175 0.25 
                    16.587 0.44C18.031 0.634 19.171 1.04 20.066 1.934C20.961 2.829 21.366 3.969 21.56 5.414C21.75 6.825 21.75 8.634 21.75 10.943V11.031C21.75 12.94 21.75 14.502 21.646 15.774C21.542 17.054 21.329 18.121 20.851 19.009C20.641 19.4 20.381 19.751 20.066 20.066C19.171 20.961 18.031 21.366 16.586 21.56C15.175 21.75 13.366 21.75 11.057 
                    21.75H10.943C8.634 21.75 6.825 21.75 5.413 21.56C3.969 21.366 2.829 20.96 1.934 20.066C1.141 19.273 0.731 18.286 0.514 17.06C0.299 15.857 0.26 14.36 0.252 12.502C0.25 12.029 0.25 11.529 0.25 11.001V10.943C0.25 8.634 0.25 6.825 0.44 5.413C0.634 3.969 1.04 2.829 1.934 1.934C2.829 1.039 3.969 0.634 5.414 0.44C6.825 0.25 8.634 0.25 10.943 0.25ZM5.613 1.926C4.335 2.098 3.564 2.426 2.995 2.995C2.425 3.565 2.098 4.335 1.926 5.614C1.752 6.914 1.75 8.622 1.75 11V11.844L2.751 10.967C3.1902 10.5828 3.75902 10.3799 4.34223 10.3994C4.92544 10.4189 5.47944 10.6593 5.892 11.072L10.182 15.362C10.5149 15.6948 10.9546 15.8996 11.4235 15.9402C11.8925 15.9808 12.3608 15.8547 12.746 15.584L13.044 15.374C13.5997 14.9835 14.2714 14.7932 14.9493 14.834C15.6273 14.8749 16.2713 15.1446 16.776 15.599L19.606 18.146C19.892 17.548 20.061 16.762 20.151 15.653C20.249 14.448 20.25 12.946 20.25 11C20.25 8.622 20.248 6.914 20.074 5.614C19.902 4.335 19.574 3.564 19.005 2.994C18.435 2.425 17.665 2.098 16.386 1.926C15.086 1.752 13.378 1.75 11 1.75C8.622 1.75 6.913 1.752 5.613 1.926Z" fill="#BCBCBC" />
                            </svg>
                        </span>
                    </label>
                    <input id="fileGallery" type="file" class="hidden" />
                </div>


            </div>

            <!-- Footer -->
            <div class="modal-footer flex items-end flex-row-reverse px-3">
                <button id="postBtn" class="bg-accent py-2 rounded px-5 text-white font-semibold ms-3 hover:bg-darkAccent">Post</button>
                <button class="cancel py-2 rounded px-5 text-grayish border border-slate-400 hover:bg-slate-400 hover:text-white">Cancel</button>
            </div>
        </div>
    </div>

    <!-- post modal -->
    <div id="modalPost" class="modal fixed inset-0 z-50 h-full w-full p-3 hidden">
        <div class="modal-container w-full h-full bg-white rounded-lg flex relative">
            <span id="closePostModal" class="absolute top-0 right-0 text-center text-2xl cursor-pointer p-3 hover:scale-50 hover:font-bold">x</span>
            <div id="containerSection" class="w-8/12 h-full ">

                <div id="default-carousel" class="relative w-full h-full bg-black" data-carousel="slide">
                    <!-- Carousel wrapper -->
                    <div class="overflow-hidden rounded-lg h-full" id="carousel-wrapper"></div>
                    <!-- Slider indicators -->
                    <div class="absolute z-30 flex space-x-3 -translate-x-1/2 bottom-5 left-1/2" id="carousel-indicators">
                    </div>
                    <!-- Slider controls -->
                    <button id="btnPrev" type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none hover:bg-gray-500 hover:bg-opacity-20" data-carousel-prev>
                        <span class="inline-flex items-center justify-center w-10 h-10 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                <path fill="white" d="m4 10l9 9l1.4-1.5L7 10l7.4-7.5L13 1z" />
                            </svg>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>
                    <button id="btnNext" type="button" class="text-white absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none hover:bg-gray-500 hover:bg-opacity-20">
                        <span class="inline-flex items-center justify-center w-10 h-10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-width="2" d="m7 2l10 10L7 22" />
                            </svg>
                            <span class="sr-only">Next</span>
                        </span>

                    </button>
                </div>

            </div>

            <!-- description -->
            <div id="descriptionInfo" class="w-4/12 h-full p-2 border-l p-3 border-gray-400">
                <div class="flex justify-start gap-2">
                    <img id="profilePic" class="rounded-full border-2 border-accent h-10 w-10" src="" alt="">
                    <div class="flex flex-col">
                        <span id="postFullName" class=" text-greyish_black font-bold"></span>
                        <span id="postUN" class=" text-gray-400 text-xs">username</span>
                    </div>
                </div>
                <p id="postDescript" class=" text-greyish_black font-light text-sm">Description</p>

                <div class="relative">

                    <div class="flex justify-end gap-2 border-t border-gray-400 mt-5 items-center text-gray-400 text-sm py-2 px-3">
                        <img src="../assets/icons/emptyheart.png" alt="">
                        <span id="noOfLikes" class="cursor-pointer w-10 text-center"></span>
                        <img src="../assets/icons/comment.png" alt="">
                        <span id="noOfComment">0</span>
                    </div>
                    <div id="namesOfUser" class="absolute -bottom-2 right-0 bg-black opacity-25 text-gray-300 w-1/3 text-xs p-2 rounded-md hidden"></div>
                </div>

                <!-- comments -->
                <div id="commentContainer" class=" h-3/4 p-2 overflow-auto"></div>
            </div>
        </div>
    </div>

    <!-- status modal -->
    <div id="postStatusModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="postStatus bg-white rounded-md w-2/6 p-5 flex flex-col gap-3">
            <div class="flex justify-between">
                <div class="flex items-center">
                    <img id="profileStatusImg" class="w-10 h-10 rounded-full" alt="" srcset="">

                    <div class="px-2 text-greyish_black">
                        <p id="statusFullnameUser" class="font-bold text-sm"></p>
                        <span class="italic accountUN text-gray-400 text-sm"></span>
                    </div>
                </div>

                <iconify-icon class="closeStatusPost cursor-pointer text-gray-400 hover:text-gray-500 hover:h-7 hover:w-7" icon="ei:close" width="24" height="24"></iconify-icon>
            </div>

            <!-- description -->
            <div>
                <pre id="statusDescript"></pre>
            </div>

            <!-- date -->
            <span id="statusDate" class="text-xs text-gray-500"></span>

            <!-- comment -->
            <div class="flex-col text-sm border-t border-gray-400 py-2 h-full overflow-y-auto">
                <div class="flex gap-2 text-gray-500 text-xs">
                    <p>Likes: <span id="statusLikes"></span></p>
                    <p>Comments: <span id="statusComment"></span></p>
                </div>

                <div id="commentStatus" class="flex flex-col gap-2 p-2 mt-2"></div>
            </div>
        </div>
    </div>


    <div id="restoreModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
        <div class="relative w-1/3 h-max p-3 bg-white rounded-md">
            <h1 class="text-xl text-greyish_black font-bold text-center py-2 border-b border-gray-400">Restore to Profile</h1>
            <p class="text-gray-500 text-sm">Items you restore to your profile can be seen by the audience that was selected
                before they were moved to archive.</p>
            <div class="flex justify-end gap-2 my-2">
                <button id="closeRestoreModal" class="px-3 py-2 rounded-md text-blue-400 hover:bg-gray-300 text-sm">Cancel</button>
                <button id="restorePost" class="px-4 py-2 text-white bg-postButton hover:bg-postHoverButton rounded-md font-semibold text-sm">Restore</button>
            </div>

            <svg id="closeRestore" class="cursor-pointer absolute top-2 right-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 20 20">
                <path fill="#6b7280" d="M2.93 17.07A10 10 0 1 1 17.07 2.93A10 10 0 0 1 2.93 17.07zM11.4 10l2.83-2.83l-1.41-1.41L10 8.59L7.17 5.76L5.76 7.17L8.59 10l-2.83 2.83l1.41 1.41L10 11.41l2.83 2.83l1.41-1.41L11.41 10z" />
            </svg>
        </div>
    </div>

    <!-- deletion modal -->
    <div id="delete-modal" class="modal hidden fixed inset-0 z-50 h-full w-full flex items-center justify-center ">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button" class="closeDeleteBtn absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this post?</h3>
                    <button id="deletePostbtn" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes, I'm sure
                    </button>
                    <button type="button" class="closeDeleteBtn text-gray-400">No, cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- prompt message -->
    <div id="promptMsg" class="w-full absolute top-1 hidden">
        <div class="promptMsg mx-auto shadow-lg rounded-md w-1/4 p-5 mt-2">
            <p id="message" class="text-accent font-semibold text-center text-sm "></p>
        </div>
    </div>
</section>


<!-- <script>
    $(document).ready(function() {
        $.getScript("");
    });
</script> -->
<script type="module" src="./community/postScript.js"></script>