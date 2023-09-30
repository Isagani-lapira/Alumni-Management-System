$(document).ready(function () {

    // get the total completion chart
    $('#formLi').on('click', function () {
        retrieveCompletionData()
        retrieveCollegeParticipation()
    })

    const completionChart = $('#completionChart')[0].getContext('2d');
    const completionChartObj = new Chart(completionChart, {
        type: 'pie',
        data: {
            labels: ['Completed', 'Waiting'],
            datasets: [{
                label: '# of Votes',
                data: [35, 500],
                borderWidth: 1,
                backgroundColor: [
                    '#CA472F', '#8DDDD0'
                ]
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    const collegeChart = $('#salaryChart')[0].getContext('2d');
    const chartData = {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: '# of College Alumni already finished answering',
                data: [],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Make the chart responsive
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const myChart = new Chart(collegeChart, chartData);
    // Function to update the chart with data
    function updateChart(labels, data) {
        myChart.data.labels = labels;
        myChart.data.datasets[0].data = data;
        myChart.update();
    }

    function retrieveCollegeParticipation() {
        const action = "countDonePerCollege";
        const formData = new FormData();
        formData.append('action', action)

        $.ajax({
            url: '../PHP_process/answer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                // collect data retrieve
                const labels = [];
                const countData = [];
                response.forEach(data => {
                    const collegeName = data.colCode;
                    const dataCount = data.alumniCountFinished;

                    labels.push(collegeName);
                    countData.push(dataCount);
                });

                updateChart(labels, countData) //update chart using the data retrieved
            },
            error: error => { console.log(error) }
        })
    }

    function retrieveCompletionData() {
        const action = "completionStatus"
        const formData = new FormData()
        formData.append('action', action)

        $.ajax({
            url: '../PHP_process/answer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                const completedCount = response.completed;
                const waitingCount = response.waiting;

                // update the chart based on the retrieve data
                completionChartObj.data.datasets[0].data = [completedCount, waitingCount];
                completionChartObj.update();
            }
        })
    }
})