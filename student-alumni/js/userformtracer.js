$(document).ready(function () {

    // retrieving all the question for specific category
    function categoryQuestion(categoryID) {
        const action = "readQuestions";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('categoryID', categoryID)

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    response.dataSet.forEach(data => {
                        const categoryID = data.categoryID
                        const categoryName = data.categoryName
                        const questionSet = data.questionSet

                        // get the details of a question
                        questionSet.forEach(data => {
                            const questionID = data.questionID
                            const questionTxt = data.questionTxt
                            const inputType = data.inputType
                            const choices = data.choices

                            //mark up for displaying question
                            displayQuestion(categoryID, categoryName, questionID, questionTxt, inputType, choices)
                        })

                    })

                }

                // add navigation button
                const navigationWrapper = $('<div>')
                    .addClass('flex justify-end w-full gap-2')
                const nextBtn = $('<button>')
                    .addClass('px-3 py-2 rounded-md bg-accent hover:bg-darkAccent text-white font-bold')
                    .text('Next')
                    .on('click', function () {
                        let isCompleted = true
                        let haveCheckedInCheckBox = true
                        // check if all input type text are answered
                        $('.userinputData').each(function () {
                            let value = $(this).val().trim()
                            if (value === '') isCompleted = false;
                        })

                        // check if there are checkboxes with the class
                        const checkBoxes = $('.checkBoxVal');
                        if (checkBoxes.length > 0) {
                            // check if at least one checkbox is checked
                            let isAnyChecked = false;
                            $('.checkBoxVal').each(function () {
                                if ($(this).prop('checked')) {
                                    isAnyChecked = true;
                                    return false; // Exit the loop early once a checked checkbox is found
                                }
                            });

                            // If none of the checkboxes are checked, set haveCheckedInCheckBox to false
                            if (!isAnyChecked) {
                                haveCheckedInCheckBox = false;
                            }
                        }

                        // check if all input fields are completed
                        if (areQuestionAnswered() && isCompleted && haveCheckedInCheckBox) {
                            $('.questions').empty()
                            const nextCategoryName = categoryList[count].categoryName;
                            const nextCategoryID = categoryList[count].categoryID;
                            //open another set of question
                            $('#categoryNameQuestion').text(nextCategoryName)
                            categoryQuestion(nextCategoryID)
                            count++
                        }
                    })

                if (count === categoryList.length) nextBtn.addClass('hidden')


                navigationWrapper.append(nextBtn)
                $('.questions').append(navigationWrapper)
            },
            error: error => { console.log(error) }
        })
    }

    const categoryList = [];
    // Function to retrieve category data as a Promise
    function retrieveCategory() {
        const action = "retrievedCategory";
        const formData = new FormData();
        formData.append('action', action);

        // return the data collected
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../PHP_process/graduatetracer.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: response => {
                    if (response.result == "Success") {
                        const length = response.categoryID.length;
                        const formTitle = response.tracerTitle;
                        const tracerID = response.tracerID;
                        $('#formTitle').val(formTitle); // add the form title
                        for (let i = 0; i < length; i++) {
                            const categoryID = response.categoryID[i];
                            const categoryName = response.categoryName[i];

                            const categoryData = {
                                categoryID: categoryID,
                                categoryName: categoryName
                            };

                            categoryList.push(categoryData); //collect all the data for category
                        }
                        resolve(); // Resolve the Promise when data is ready
                    } else {
                        reject("Error: Unable to retrieve category data.");
                    }
                },
                error: error => {
                    reject("Error: AJAX request failed.");
                }
            });
        });
    }

    let count = 0
    // Event listener for clicking the "proceedTracer" button
    $('#proceedTracer').on('click', async function () {
        $('#frontpageTracer').addClass('hidden');
        $('#questionsContainer').removeClass('hidden');


        // get the first questions
        try {
            await retrieveCategory(); // Wait for the data to be retrieved
            const firstCategoryName = categoryList[0].categoryName;
            const firstCategoryID = categoryList[0].categoryID;

            createAlumniEntry()
            $('#categoryNameQuestion').text(firstCategoryName)
            categoryQuestion(firstCategoryID)
            count++
        } catch (error) {
            console.error(error);
        }
    });


    function createAlumniEntry() {
        const action = "addNewAnswer";
        const formData = new FormData();
        formData.append('action', action);

        $.ajax({
            url: '../PHP_process/answer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => { console.log(response) },
            error: error => { console.log(error) }
        })
    }
    function displayQuestion(categoryID, categoryName, questionID, questionTxt, inputType, choices) {
        // wrapper container
        const questionWrapper = $('<div>')
            .addClass('center-shadow border-t-4 border-accent rounded-lg py-3 px-5 mb-3 userQuestionTracer')

        // question name
        const question = $('<h3>')
            .addClass('text-center font-bold lg text-greyish_black')
            .text(questionTxt)
        questionWrapper.append(question)

        const questionBody = $('<div>')
            .addClass('flex flex-col gap-2 py-2')
        // check what type of input type is set
        if (inputType === 'Input') {
            // input type
            let questionType = $('<input>')
                .addClass('border-b border-gray-400 p-2 w-full outline-none userinputData')
                .attr('type', 'text')
            questionBody.append(questionType)

        }
        else {
            const select = $('<select>')
                .addClass('w-full px-2 py-4 outline-none center-shadow rounded-lg')
            // get all the choices
            choices.forEach(choice => {
                const choiceID = choice.choiceID;
                const choice_text = choice.choice_text;

                // check again where it fall to three (radio,dropdown,checkbox)
                if (inputType === "Radio") {
                    const inputFieldWrapper = $('<div>')
                        .addClass('flex justify-start items-center gap-2 w-full p-2')
                    let name = questionTxt.replace(' ', '')
                    const max = 1000
                    let id = choiceID + Math.floor(Math.random() * (max + 1))
                    const choiceVal = $('<input>')
                        .attr('type', 'radio')
                        .attr('name', name)
                        .attr('id', id)
                        .val(choiceID)

                    const label = $('<label>')
                        .addClass('cursor-pointer')
                        .attr('for', id)
                        .text(choice_text)

                    inputFieldWrapper.append(choiceVal, label)
                    questionBody.append(inputFieldWrapper)
                }
                else if (inputType === "DropDown") {
                    // dropdown type
                    const option = $('<option>')
                        .attr('value', choiceID)
                        .text(choice_text)

                    select.append(option)
                }
                else if (inputType == "Checkbox") {
                    const inputFieldWrapper = $('<div>')
                        .addClass('flex justify-start items-center gap-2 w-full p-2')
                    let name = questionTxt.replace(' ', '')
                    const max = 1000
                    let id = choiceID + Math.floor(Math.random() * (max + 1))
                    const choiceVal = $('<input>')
                        .addClass('checkBoxVal')
                        .attr('type', 'checkbox')
                        .attr('name', name)
                        .attr('id', id)
                        .val(choiceID)

                    const label = $('<label>')
                        .addClass('cursor-pointer')
                        .attr('for', id)
                        .text(choice_text)

                    inputFieldWrapper.append(choiceVal, label)
                    questionBody.append(inputFieldWrapper)
                }
            })

            if (inputType === "DropDown")
                questionBody.append(select) //add select if the input was assigned as dropwdown

        }

        // Add data-required attribute to required questions
        if (inputType !== 'Input') {
            questionWrapper.attr('data-required', 'true');
        }
        questionWrapper.append(questionBody)
        $('.questions').append(questionWrapper) //add to main container
    }

    // check if all readio input question are answered
    function areQuestionAnswered() {
        const unansweredRequiredQuestions = $('.questions [data-required="true"]')
            .toArray()
            .filter(questionWrapper => {
                if ($(questionWrapper).find('input[type="radio"]').length > 0) {
                    const questionID = $(questionWrapper).find('input[type="radio"]:checked').val();
                    return typeof questionID === 'undefined';
                }
                return false;
            });

        return unansweredRequiredQuestions.length === 0;
    }




})