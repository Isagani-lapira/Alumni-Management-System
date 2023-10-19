$(document).ready(function () {

    // get the total completion chart
    $('#formLi').on('click', function () {
        $('#formReport').removeClass('hidden')
        retrieveCompletionData()
        retrieveCollegeParticipation()
        addCategorySelection()
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
            responsive: false,
        }
    });


    const collegeChart = $('#respondentPerCol')[0].getContext('2d');
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
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, // Specify the step size for Y-axis values
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
        let value = $(this).val();

        $('#displayChart')
            .removeClass('off')
            .addClass('on')
            .on('click', function () {
                displayChartForQuestion(value)
            })
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
            console.log('pumasok')
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
})