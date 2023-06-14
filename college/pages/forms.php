<section class=" mx-auto container border px-5">
    <h1 class="text-2xl font-bold ">Alumni Tracer Form</h1>
    <p class="text-gray-400 font-semibold">Here you can see the list of alumni that answers the form</p>

    <div class="flex w-full justify-end"><button class="btn-primary font-bold space-y-2"><span>Deploy Tracer</span>
            <i class="fa-solid fa-circle-chevron-right"></i>
        </button></div>

    <hr>

    <!-- Pie Chart -->

    <h3>Employee Status</h3>


    <!-- TODO add chart here -->
    <div class="chart-box w-72 relative">
        <canvas id="empStatusPieChart"></canvas>
    </div>

    <!-- Chart.js  -->
    <h3>Annual Salary</h3>
    <div class="chart-box w-1/2 h-64 relative">
        <canvas id="annualSalCanvas"></canvas>
    </div>
    <h3>Relevant Skills Developed</h3>
    <!-- TODO add chart here -->
    <div class="grid grid-cols-3 grid-flow-row gap-4">
        <div class="chart-box w-64">
            <canvas id="commSkillsCanvas"></canvas>
        </div>
        <div class="chart-box w-64">
            <canvas id="hrSkillsCanvas"></canvas>
        </div>
        <div class="chart-box w-64">
            <canvas id="entrepCanvas"></canvas>
        </div>
        <div class="chart-box w-64">
            <canvas id="itCanvas"></canvas>
        </div>
        <div class="chart-box w-64">
            <canvas id="problemSolvingCanvas"></canvas>
        </div>
        <div class="chart-box w-64">
            <canvas id="criticalThinkingCanvas"></canvas>
        </div>
    </div>

</section>

<script>
    $(document).ready(function() {

        // Chart Canvas Elements
        const empStatusCanvas = document.querySelector('#empStatusPieChart');
        const annualSalCanvas = document.querySelector('#annualSalCanvas');
        // relevant skills developed
        const commSkillsCanvas = document.querySelector('#commSkillsCanvas');
        const hrSkillsCanvas = document.querySelector('#hrSkillsCanvas');
        const entrepCanvas = document.querySelector('#entrepCanvas');
        const itCanvas = document.querySelector('#itCanvas');
        const problemSolvingCanvas = document.querySelector('#problemSolvingCanvas');
        const criticalThinkingCanvas = document.querySelector('#criticalThinkingCanvas');
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



        createRelevantSkillsChart(commSkillsCanvas, 'Communication Skills', {
            labels: ['skilled', 'not skilled'],
            data: [100, 400]
        })
        createRelevantSkillsChart(hrSkillsCanvas, 'Human Relations Skills', {
            labels: ['skilled', 'not skilled'],
            data: [100, 400]
        })
        createRelevantSkillsChart(entrepCanvas, 'Entreprenuerial Skills', {
            labels: ['skilled', 'not skilled'],
            data: [100, 400]
        })
        createRelevantSkillsChart(itCanvas, 'Information Technology Skills', {
            labels: ['skilled', 'not skilled'],
            data: [100, 400]
        })
        createRelevantSkillsChart(problemSolvingCanvas, 'Problem-Solving Skills', {
            labels: ['skilled', 'not skilled'],
            data: [100, 400]
        })
        createRelevantSkillsChart(criticalThinkingCanvas, 'Critical-Thinking Skills', {
            labels: ['skilled', 'not skilled'],
            data: [100, 400]
        })

        function createRelevantSkillsChart(id, chartTitle, chartData = {
            labels: [],
            data: []
        }) {
            new Chart(
                id, {
                    type: 'pie',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            data: chartData.data
                        }],

                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: chartTitle,
                                position: 'bottom',
                                font: {
                                    weight: 'bold',
                                    size: 16,
                                    family: 'sans-serif'
                                }

                            }
                        },

                    }
                }
            )
        }

    })
</script>