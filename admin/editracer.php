<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/main.css" rel="stylesheet" />
    <link href="../style/style.css" rel="stylesheet" />
    <link href="../style/tracer.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <link href="../style/style.css" rel="stylesheet" />

    <title>Edit Tracer</title>
</head>

<body>
    <?php
    if (isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        echo "<input id='formID' type='hidden' value = $id";
    } else {
        header('Location: ../admin/admin.php');
    }
    ?>
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
        <p id="noteCategory" class="text-gray-500 italic text-center w-2/5 mx-auto">This section allows you to set a categorization for
            different sets of question to be easily distinguish by the one answering</p>

        <section id="questionnaire" class="w-1/2 h-3/4 block mx-auto my-5">
            <div class="h-full">
                <div id="categoryContainer" class=" rounded-lg border-t-8 border-accent p-5 justify-between center-shadow">
                    <div id="categoryWrapper" class="flex flex-col gap-2"></div>
                    <button id="btnAddCat" class="text-white px-4 py-2 bg-blue-400 hover:bg-blue-500 my-3 rounded-md">Add
                        category</button>
                </div>

                <div id="questions" class="hidden h-full overflow-y-auto no-scrollbar"></div>

                <div id="paginationWrapper" class="flex justify-end my-3 gap-2">
                    <button class="text-gray-500 hover:bg-gray-300 py-2 px-4 rounded-md">Cancel</button>
                    <button id="nextpage" class="text-white bg-accent px-4 py-2 font-bold rounded-md hover:bg-darkAccent">Next</button>
                </div>

            </div>

        </section>

    </main>

    <span id="promptMsgSection" class=" block mx-auto slide-bottom fixed top-0 px-4 py-2 z-50 bg-green-400 hidden text-white rounded-sm font-bold">Section successfully created!</span>
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

    <!-- section modal -->
    <div id="sectionModalcontainer" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
        <input type="hidden" id="catIDHolder">
        <input type="hidden" id="formIDHolder">
        <input type="hidden" id="choiceIDHolder">
        <div id="sectionModal" class="modal-container w-1/2 h-4/5 bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2 border-t-8 border-blue-400 relative">
            <iconify-icon id="addSectionQuestion" title="Add new question for this section" class="iconAddModal p-3 rounded-md center-shadow h-max absolute top-0 right-0" icon="gala:add" style="color: #AFAFAF;" width="24" height="24"></iconify-icon>
            <header class="font-bold text-4xl text-center text-blue-400 py-2 border-b border-gray-300">
                Section
            </header>
            <div id="sectionBody" class="h-full overflow-y-auto"></div>
        </div>
    </div>

    <!-- add new question -->
    <div id="newQuestionModal" class="modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
        <div class="modal-container w-1/3 h-max bg-white rounded-lg text-greyish_black">
            <header class="text-center text-lg font-bold py-3 border-b border-gray-400 mb-2">Add new Question</header>
            <div class="wrapper p-3 w-full mx-auto m-2">
                <input id="newQuestionInputName" type="text" class="w-full text-center text-lg border-b border-gray-400" placeholder="Untitled Question" />
                <!-- body -->
                <div class="p-3 text-gray-400 mb-2">
                    <select id="inputTypeModalNew" class="w-full p-2 outline-none center-shadow mb-2" name="" id="">
                        <option value="Radio">Radio Type</option>
                        <option value="Input">Input Type</option>
                        <option value="Checkbox">Chexbox Type</option>
                        <option value="DropDown">Dropdown Type</option>
                    </select>
                    <!-- options -->
                    <div class="optionContainer">
                        <div class="fieldWrapper flex items-center gap-2">
                            <iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>
                            <input type="text" class="py-2 choicesVal" placeholder="Add choice">
                        </div>
                    </div>
                </div>

                <button id="addOptionmodal" class="flex items-center gap-2 text-gray-400">
                    <iconify-icon icon="gala:add" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                    Add option
                </button>

                <div class="flex items-center justify-end gap-2">
                    <button id="closeQuestionModal" class="text-gray-400 hover:bg-gray-300 py-2 px-3">Cancel</button>
                    <button id="saveNewQuestion" class="bg-blue-400 hover:bg-blue-500 px-4 py-2 rounded-md text-white">Save</button>
                </div>
            </div>
        </div>
    </div>

    <span id="promptMsgNewQuestion" class="block mx-auto slide-bottom fixed top-0 px-4 py-2 z-50 bg-green-400 text-white rounded-sm font-bold hidden">Question successfully added</span>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="../js/edittracer.js"></script>
</body>

</html>