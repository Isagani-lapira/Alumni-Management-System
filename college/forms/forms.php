<section class=" mx-auto   p-5 lg:mx-8">
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





</section>



<script type="module" src="./forms/tracer.js"></script>
<script type="module" src="./forms/tracerchart.js"></script>




<script>
    $(document).ready(function() {

        // Chart Canvas Elements
        const empStatusCanvas = document.querySelector('#empStatusPieChart');
        const annualSalCanvas = document.querySelector('#annualSalCanvas');
        // configure chart js functions here.

        // First Graph
        const data = {
            labels: [
                'Employed',
                'Unemployed',
                'Not yet answered'
            ],
            datasets: [{
                label: 'Employment Status',
                data: [300, 50, 100] // actual data,

            }]
        }
        new Chart(
            empStatusCanvas, { // config
                type: 'pie',
                data: data

            }
        )


        const salaryChart_labels = ["₱10k-20k", "₱21k-30k", "₱31k-40k", "₱51k-60k", "₱60k-70k", "₱71k-80k"];
        const salaryChart_data = [1000, 500, 247, 635, 323, 393];

        new Chart(
            annualSalCanvas, {
                type: 'bar',
                data: {
                    labels: salaryChart_labels,
                    datasets: [{
                        data: salaryChart_data,
                        borderWidth: 1,
                    }],

                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },

                }
            }
        )
    })
</script>