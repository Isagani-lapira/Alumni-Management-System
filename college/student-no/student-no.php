<section id="student-no-container">
    <!-- student number record -->
    <div id="studNo-tab" class="p-5  overflow-y-auto relative">


        <h1 class="text-xl font-extrabold">STUDENT NO. RECORD</h1>
        <input type="file" id="excelFileUploader" class="hidden" accept=".xls, .xlsx" />
        <!-- file upload -->
        <button class="w-4/5 text-end block mx-auto text-postButton hover:text-postHoverButton mt-5 see-format-btn">
            See sample format
        </button>
        <div id="uploadExcelFile" class="w-4/5 border mx-auto border-gray-400 rounded-md h-44 flex justify-center items-center cursor-pointer hover:font-bold">
            <h2 class="text-lg text-greyish_black uploader-text">
                Click ( or tap) here to select a file
            </h2>
        </div>
        <!-- note -->
        <div class="flex justify-between items-center w-4/5 mx-auto">
            <div class="flex items-center gap-2 text-greyish_black">
                <iconify-icon icon="fe:warning" width="24" height="24"></iconify-icon>
                <span>Maximum of 10mb</span>
            </div>
            <button class="px-3 py-2 bg-blue-400 hover:bg-blue-500 text-sm mt-2 rounded-md text-white upload-file-btn hidden">
                Upload
            </button>
        </div>

        <!-- list of student record -->
        <h2 class="text-greyish_black font-semibold mt-5">Recorded List</h2>
        <table id="studentRecordTB" class="w-full text-sm">
            <thead>
                <tr class="bg-accent text-white">
                    <td class="rounded-tl-md">Student No.</td>
                    <td>Fullname</td>
                    <td>BatchYear</td>
                    <td>Status</td>
                    <td class="rounded-tr-md">Date added</td>
                </tr>
            </thead>
        </table>
    </div>
    <!-- excel format notice -->
    <div class="post modal fixed inset-0 z-50 flex items-center justify-center sheet-format-modal p-3 hidden">
        <div class="bg-white rounded-2xl w-2/5 p-5 flex flex-col gap-3 text-gray-700 h-max">
            <h2 class="text-xl font-semibold border-b border-gray-300 py-2">File Format</h2>
            <p>Please see the preview of the sample sheet file below to help you prepare your data for upload:</p>
            <img class="w-full" src="/images/sample_excel.png" alt="sample excel format">
        </div>
    </div>


    <!-- duplicate data -->
    <div class="post modal fixed inset-0 z-50 flex items-center justify-center list-duplicate-modal p-3 hidden">
        <div class="bg-white rounded-2xl w-2/5 p-5 flex flex-col gap-3 text-gray-700 h-max relative duplicate-entry">
            <h2 class="text-xl font-semibold border-b border-gray-300 py-2 text-center">Duplicate Data</h2>
            <p class="text-center">Some of the information in the spreadsheet is already in the system. These details are omitted.</p>
            <span>List of duplicated data:</span>
            <!-- list of duplicated data -->
            <div class="list-wrapper flex flex-col overflow-y-auto text-gray-500"></div>
            <button class="close-list-duplicate-btn absolute top-2 right-3 text-gray-400">
                <iconify-icon class="hover:font-bold" icon="fe:close" width="24" height="24"></iconify-icon>
            </button>
        </div>
    </div>

</section>

<script type="module" src="./student-no/studNoUploader.js"></script>