$(document).ready(function () {

    // get the total completion chart
    $('#formLi').on('click', function () {
        // restart everything
        $('#forms-tab').removeClass('hidden')
        $('#ddTracerform').find('option:not(:disabled)').remove()
        retrieveCompletionData()
        retrieveCollegeParticipation()
        addCategorySelection()
        addTracerDeploymentOption()

    })



    const completionChart = $('#completionChart')[0].getContext('2d');
    const completionChartObj = new Chart(completionChart, {
        type: 'pie',
        data: {
            labels: ['Completed', 'Waiting'],
            datasets: [{
                label: '%',
                data: [35, 500],
                borderWidth: 1,
                backgroundColor: [
                    '#CA472F', '#8DDDD0'
                ]
            }]
        },
        options: {
            responsive: false,
        }
    });


    const collegeChart = $('#respondentPerCol')[0].getContext('2d');
    const chartData = {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: '% of College Alumni already finished answering',
                data: [],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Make the chart responsive
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100, // Set the maximum value on the y-axis to 100
                    ticks: {
                        stepSize: 1, // Specify the step size for Y-axis values
                        callback: function (value) {
                            return value + '%'; // Add '%' sign to the tick values
                        },
                    }
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

                console.log(response)
                updateChart(labels, countData) //update chart using the data retrieved
            },error: error=>{console.log(error)}
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


    // add category to the selection
    function addCategorySelection() {
        const action = "retrievedCategory"
        const formData = new FormData();
        formData.append('action', action)
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.result == 'Success') {
                    // add the category to selection of category
                    const length = response.categoryID.length;

                    for (let i = 0; i < length; i++) {
                        const categoryID = response.categoryID[i];
                        const categoryName = response.categoryName[i];

                        const option = $('<option>').text(categoryName).val(categoryID);
                        $('#categorySelection').append(option)
                    }
                }
            },
            error: error => { console.log(error) }
        })
    }


    // add question
    $('#categorySelection').on('change', function () {
        const categoryVal = $(this).val()
        const action = 'readQuestions';
        const formData = new FormData();
        formData.append('action', action);
        formData.append('categoryID', categoryVal);

        // retrieve all question that is in category
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: response => {
                $('#questionSelection').find(':not(:first-child)').remove()
                if (response.response = 'Success') {
                    const dataSet = response.dataSet[0].questionSet
                    const choicesData = []
                    dataSet.forEach(data => {
                        let questionID = data.questionID
                        let questionTxt = data.questionTxt
                        let inputType = data.inputType

                        // only question with choices
                        if (inputType !== 'Input') {
                            let option = $('<option>').text(questionTxt).val(questionID)
                            $('#questionSelection').append(option)

                        }

                    })
                }
            },
            error: error => { console.log(error) },
        })
    })

    $('#questionSelection').on('change', function () {
        // enable display button
        $('#displayChart')
            .removeClass('off')
            .addClass('on')
    })
    $('#displayChart').on('click', function () {
        const value = $('#questionSelection').val()
        questionChartObj.data.datasets[0].data = []
        displayChartForQuestion(value)
    })


    function displayChartForQuestion(questionID) {
        const action = "getQuestionChoices"
        const formData = new FormData();
        formData.append('action', action);
        formData.append('questionID', questionID);

        let labels = []
        let counts = []
        // get all the choices in  a particular question
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: response => {
                const length = response.length
                for (let i = 0; i < length; i++) {
                    // choice and its corresponding answers chosen
                    const choiceText = response[i].choiceText
                    const count = response[i].count

                    labels.push(choiceText)
                    counts.push(count)
                }

                typeChartSelection = $('#typeChartSelection').val()

                if (typeChartSelection != "")
                    updateQuestionChartData(labels, counts, typeChartSelection)
                else updateQuestionChartData(labels, counts)
                labels = []
                counts = []
            },
        })
    }

    const colors = ['#E6B0AA', '#D7BDE2', '#A9CCE3', '#A3E4D7',
        '#A9DFBF', '#F9E79F', '#F5CBA7', '#D5DBDB', '#D5DBDB', '#AEB6BF', '#8D6E63',
        '#00BCD4', '#78909C', '#C0CA33', '#117864', '#212F3C'];

    function updateQuestionChartData(labels, data, typeChartSelection = "pie") {
        // changing the chart based on reference of the user
        questionChartObj.data.labels = labels; //choices as labels
        questionChartObj.data.datasets[0].data = data; //count per choices
        questionChartObj.config.type = typeChartSelection; //type of chart
        const questionText = $('#questionSelection option:selected').text() //label for chart the selected option text
        questionChartObj.data.datasets[0].label = "# of Votes in " + questionText
        const backgroundColors = [];
        // background colors
        for (let i = 0; i < data.length; i++) {
            backgroundColors.push(colors[i]);
        }

        if (typeChartSelection !== "pie") {
            questionChartObj.options.scales = {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, // Specify the step size for Y-axis values
                    }
                }
            }
        }
        questionChartObj.data.datasets[0].backgroundColor = backgroundColors
        questionChartObj.update();
    }

    const questionChart = $('#chartPerQuestion')[0].getContext('2d');
    const questionChartObj = new Chart(questionChart, {
        type: 'bar',
        data: {
            datasets: [{
                label: '# of Votes',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
        }
    });


    function addTracerDeploymentOption() {
        const action = 'retrieveList';
        const formData = new FormData();
        formData.append('action', action);

        $.ajax({
            url: '../PHP_process/deploymentTracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response === 'Success') {
                    const length = response.tracerDeploymentID.length;
                    // add option to the dropdown
                    for (let i = 0; i < length; i++) {
                        const tracerDeploymentID = response.tracerDeploymentID[i];
                        const year = response.year[i];

                        // option mark up
                        let option = $('<option>').text('Tracer form ' + year).val(tracerDeploymentID)
                        $('#ddTracerform').append(option);
                    }
                }
            },
            error: error => { console.log(error) }
        })
    }

    // download the graduate tracer form in spreadsheet
    $('#ddTracerform').on('change', function () {
        const tracerDeploymentID = $(this).val();
        const spreadsheetUrl = '../PHP_process/spreadsheet.php?tracerDeployID=' + tracerDeploymentID; //URL for spreadsheet with the selected form
        window.location.href = spreadsheetUrl; // Redirect the user to the generated spreadsheet URL
    });


    $('.download-chart-btn').on('click', function () {
        let chartobj = questionChartObj;

        // Create an HTML2Canvas configuration object with a white background
        let html2canvasConfig = {
            backgroundColor: 'white',
        };

        // Use HTML2Canvas to capture the chart as an image
        html2canvas(chartobj.canvas, html2canvasConfig).then(function (canvas) {
            // Convert the captured chart into an image data URL
            let base64Img = canvas.toDataURL('image/png');

            // Create a download link
            let downloadLink = document.createElement('a');
            downloadLink.href = base64Img;
            downloadLink.download = 'questionChart.png'; // Specify the file name here

            // Simulate a click event to initiate the download
            downloadLink.click();
        });
    });



    $('.download-two-btn').on('click', function () {
        var divToCapture = document.getElementById('chart-completion-alumni');

        // Use HTML2Canvas to capture the <div> as an image
        html2canvas(divToCapture).then(function (canvas) {
            // Convert the captured <div> into an image data URL
            var imageDataURL = canvas.toDataURL('image/png');

            // Create a download link
            var downloadLink = document.createElement('a');
            downloadLink.href = imageDataURL;
            downloadLink.download = 'capturedDiv.png'; // Specify the file name here
            downloadLink.click();
        });
    });

})