<section class=" mx-auto   p-5 lg:mx-8">
    <h1 class="text-2xl font-bold ">Alumni Tracer Form</h1>
    <p class="text-gray-400 font-semibold">See the relevant information that are gathered</p>

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


</section>

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