<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet" />
    <link href="../style/style.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <link href="../style/style.css" rel="stylesheet" />

    <title>Document</title>
</head>

<body>
    <nav class="p-3 border-b border-accent">
        <section class="logo flex gap-2 items-center">
            <img src="../assets/bulsu-logo.png" class="h-20 w-20">
            <div class="flex flex-col gap-2">
                <input id="formTitle" class="font-bold text-2xl outline-none text-greyish_black" type="text" value="Untitled Graduate Tracer">
                <span id="saveMsg" class="text-gray-400 text-sm flex items-center">Saved</span>
            </div>

        </section>
    </nav>
    <main>
        <section class="subbar w-full flex justify-center mb-5">
            <span id="subBar" class="text-white bg-accent rounded-b-lg w-1/4 text-center py-3 text-lg font-bold">Question
                Categorization</span>
        </section>
        <p class="text-gray-500 italic text-center w-2/5 mx-auto">This section allows you to set a categorization for
            different sets of question to be easily distinguish by the one answering</p>

        <section id="questionnaire" class="w-2/5 block mx-auto my-5">

            <div>
                <div id="categoryContainer" class=" rounded-lg border-t-8 border-accent p-5 justify-between center-shadow">
                    <div id="categoryWrapper" class="flex flex-col gap-2"></div>
                    <button id="btnAddCat" class="text-white px-4 py-2 bg-blue-400 hover:bg-blue-500 my-3 rounded-md">Add
                        category</button>
                </div>
            </div>

            <div id="categorySection"></div>

        </section>
        <footer id="paginationWrapper" class="w-2/5 flex justify-end mx-auto my-3 gap-2">
            <button class="text-gray-500 hover:bg-gray-300 py-2 px-4 rounded-md">Cancel</button>
            <button id="nextpage" class="text-white bg-accent px-4 py-2 font-bold rounded-md hover:bg-darkAccent">Next</button>
        </footer>

    </main>

    <!-- success prompt -->
    <div id="successModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
        <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2">
            <svg class="block mx-auto" width="115px" height="115px" viewBox="0 0 133 133" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="check-group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <circle id="filled-circle" fill="#47CF73" cx="66.5" cy="66.5" r="54.5" />
                    <circle id="white-circle" fill="#FFFFFF" cx="66.5" cy="66.5" r="55.5" />
                    <circle id="outline" stroke="#47CF73" stroke-width="4" cx="66.5" cy="66.5" r="54.5" />
                    <polyline id="check" stroke="#FFFFFF" stroke-width="5.5" points="41 70 56 85 92 49" />
                </g>
            </svg>
            <h1 class=" text-2xl font-bold text-green-500 text-center w-4/5 mx-auto">Graduate tracer created
                successfully!</h1>
            <p class="text-lg text-center text-gray-500">Your graduate tracer has been added to the system.</p>
        </div>
    </div>

    <!-- loading modal -->
    <div id="loadingModal" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
        <div class="modal-container w-1/3 h-max bg-white rounded-lg p-3 text-greyish_black flex flex-col justify-center items-center gap-2">
            <div class="loading-spinner mb-2"></div>
            <p class="text-xl font-bold text-gray-500">Inserting...</p>
        </div>
    </div>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="../js/edittracer.js"></script>
</body>

</html>