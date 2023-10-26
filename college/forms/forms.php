<section class=" mx-auto   p-5 lg:mx-8 overflow-auto">
    <h1 class="text-xl font-extrabold">Alumni Tracer Form</h1>
    <p class="text-grayish">See the relevant information that are gathered</p>

    <div class="flex gap-2 justify-end mb-2">
        <button id="tracerbtn" class="text-gray-400 hover:text-gray-500">Tracer form</button>
        <button id="deployTracerBtn" class="px-3 py-2 bg-accent hover:bg-darkAccent text-white rounded-md font-bold">Deploy Tracer</button>
    </div>

    <div id="formReport" class="border border-t-grayish h-full overflow-y-auto">
        <div class="flex gap-2 justify-evenly">
            <div class="h-2/5 w-1/2 p-5 flex flex-col">
                <h1 class="text-lg font-extrabold">Completion Chart</h1>
                <canvas class="w-full h-full" id="completionChart"></canvas>
            </div>

            <div class="h-2/5 w-1/2 p-5 flex flex-col">
                <h1 class="text-lg font-extrabold px-5">College Alumni Chart</h1>
                <canvas class="w-full h-5/6" id="respondentPerCol"></canvas>
            </div>
        </div>

        <div class="center-shadow rounded-lg p-3 m-2">
            <h3 class="font-semibold text-lg text-greyish_black">GET THE GRAPH OF A SPECIFIC QUESTION</h3>

            <div class="flex mt-2 items-center">
                <div class=" w-3/4 flex">
                    <!-- category -->
                    <div class="text-sm text-gray-500 flex flex-col w-1/2">
                        <label for="categorySelection" class="px-2">CATEGORY</label>
                        <select id="categorySelection" class="border border-gray-400 rounded-lg p-2 m-2">
                            <option value="" selected>--Your Choice--</option>
                        </select>
                    </div>
                    <!-- question -->
                    <div class="text-sm text-gray-500 flex flex-col w-1/2">
                        <label for="questionSelection" class="px-2">QUESTION</label>
                        <select id="questionSelection" class="border border-gray-400 rounded-lg p-2 m-2">
                            <option value="" selected>--Your Choice--</option>
                        </select>
                    </div>

                    <!-- graph type -->
                    <div class="text-sm text-gray-500 flex flex-col w-1/2">
                        <label for="typeChartSelection" class="px-2">GRAPH/CHART</label>
                        <select id="typeChartSelection" class="border border-gray-400 rounded-lg p-2 m-2">
                            <option value="" selected>--Your Choice--</option>
                            <option value="bar">Bar Type</option>
                            <option value="bubble">Bubble Type</option>
                            <option value="doughnut">Doughnut Type</option>
                            <option value="pie">Pie Type</option>
                            <option value="line">Line Type</option>
                        </select>
                    </div>

                </div>

                <div class="w-1/2 flex items-center justify-end">
                    <button id="displayChart" class="px-4 py-2 rounded-lg off">Display</button>
                </div>

            </div>

            <p class="text-sm italic text-gray-500">Note: Select a category first</p>
        </div>

        <div class="center-shadow rounded-lg p-3 m-2 h-full flex justify-center">
            <canvas id="chartPerQuestion"></canvas>
        </div>
    </div>

    <!-- repository -->
    <div id="tracerRepo" class="h-full border border-t-grayish p-5 w-full hidden">
        <div class="flex justify-end my-2">
            <div id="previewFormBtn" class="w-max cursor-pointer hover:font-bold hover:text-accent flex items-center gap-2">
                <iconify-icon icon="icon-park-outline:preview-open" width="24" height="24"></iconify-icon>
                <span>Preview</span>
            </div>
        </div>

        <div id="TracerWrapper" class="flex w-full h-full mx-auto p-2 gap-2">

            <div id="categoryWrapper" class="flex flex-col gap-2 w-1/3 p-1  h-full"></div>
            <!-- question set -->
            <div class="flex-1 h-full center-shadow p-3 relative">
                <input id="categoryName" class="w-full py-2 text-xl text-greyish_black border-b border-gray-400 font-semibold mb-3" disabled />
                <span id="btnSaveChanges" class="absolute top-5 right-2 text-gray-400 text-xs flex items-center gap-2 hidden" id="savedChanges">
                    <iconify-icon icon="dashicons:saved" style="color: #afafaf;" width="20" height="20"></iconify-icon>
                </span>
                <div id="questionSetContainer" class="overflow-y-auto flex flex-col gap-2 py-2"></div>
            </div>

        </div>
    </div>

    <!-- preview -->
    <div id="previewForm" class="p-3 flex flex-col border-t border-gray-500 h-full overflow-y-auto hidden">
        <div>
            <div id="backFromPreviewBtn" class="flex items-center gap-3 w-max">
                <iconify-icon icon="fluent-mdl2:back" class="text-gray-800 cursor-pointer" width="24" height="24"></iconify-icon>
                <span class="font-semibold">Back</span>
            </div>

        </div>

        <h2 class="font-bold text-center text-lg">Alumni Tracer Form</h2>
        <div id="previewContainer" class="flex flex-col p-5 gap-3 border border-gray-300 rounded-md">
            <h3 id="categoryNamePrev" class="font-bold text-lg text-start"></h3>
        </div>

        <div class="mt-2">
            <button id="previousPreviewBtn" class="hidden text-gray-500 hover:text-lg">Previous</button>
            <button id="nextPreviewBtn" class="mt-2 w-max py-2 px-5 rounded-md bg-accent text-white hover:bg-darkAccent">Next</button>
        </div>

    </div>



    <!-- MODAL -->
    <!-- section modal -->
    <div id="sectionModalcontainer" class="post modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
        <input type="hidden" id="catIDHolder">
        <input type="hidden" id="formIDHolder">
        <input type="hidden" id="choiceIDHolder">
        <div id="sectionModal" class="modal-container h-4/5 bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2 border-t-8 border-accent relative">
            <iconify-icon id="addSectionQuestion" title="Add new question for this section" class="iconAddModal p-3 rounded-md center-shadow h-max absolute top-1 right-1" icon="gala:add" style="color: #AFAFAF;" width="24" height="24"></iconify-icon>
            <header class="font-bold text-4xl text-center text-accent py-2 border-b border-gray-300">
                Section
            </header>
            <div id="sectionBody" class="h-full overflow-y-auto py-2 flex flex-col gap-2"></div>
        </div>
    </div>

    <!-- add new question -->
    <div id="newQuestionModal" class="modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">
        <div class="modal-container w-1/3 h-max bg-white rounded-lg text-greyish_black">
            <header class="text-center text-lg font-bold py-3 mb-2">Add new Question</header>
            <div class="wrapper p-3 w-full mx-auto m-2">
                <input id="newQuestionInputName" type="text" class="w-full text-center text-lg border-b border-gray-300" placeholder="Untitled Question" />
                <!-- body -->
                <div class="p-3 text-gray-400 mb-2">
                    <select id="inputTypeModalNew" class="w-full p-2 outline-none center-shadow mb-2">
                        <option value="Radio">Radio Type</option>
                        <option value="Input">Input Type</option>
                        <option value="Checkbox">Chexbox Type</option>
                        <option value="DropDown">Dropdown Type</option>
                    </select>
                    <!-- options -->
                    <div class="optionContainer">
                        <div class="fieldWrapper flex items-center gap-2">
                            <iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>
                            <input type="text" class="py-2 choicesVal w-full" placeholder="Add choice">
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
    <div id="deploymentModal" class="modal fixed inset-0 z-50 flex items-center justify-center p-3 hidden">

        <div id="modalConfirmDeployment" class="modal-container w-2/5 h-max bg-white rounded-lg text-greyish_black p-3">
            <h3 class="text-xl font-semibold text-gray-900 border-b border-gray-300 py-3">Tracer Deploy Confirmation</h3>
            <p class="p-4 text-sm">You are about to deploy the Graduate Tracer, which will be accessible to all alumni users in the system.
                Please be aware that this deployment is valid for a limited period of 4 months. </p>

            <p class="p-4 text-sm">By confirming this action, you are distributing
                the Graduate Tracer to all alumni, enabling them to access valuable information and insights.
                It's essential to ensure that the data and content within the tracer are up-to-date and accurate for the
                benefit of our alumni community. </p>

            <p class="p-4 text-sm">Remember, this deployment is a significant step in keeping our alumni engaged and informed.
                Are you sure you want to proceed?</p>

            <div class="flex w-full justify-end items-center gap-2 my-3 border-t border-gray-300 py-2">
                <button id="cancelDeployBtn" class="text-gray-400 hover:text-gray-500">Cancel</button>
                <button id="confirmDeployTracerBtn" class="px-4 py-2 rounded-lg bg-blue-400 hover:bg-blue-500 text-white font-bold">Deploy</button>
            </div>
        </div>

    </div>





</section>



<script type="module" src="./forms/tracer.js"></script>
<script type="module" src="./forms/tracerchart.js"></script>
<script type="module" src="./forms/previewcontainer.js"></script>
<script type="module" src="./forms/edittracer.js"></script>