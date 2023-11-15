$(document).ready(function () {

    const tracerQuestionWrapper = $('.questions')
    const progressBar = $('.progressBar')
    progressBar.css({ 'width': 0 + '%' }) //start with 0 percentage
    // retrieving all the question for specific category
    function categoryQuestion(answerID, categoryID) {
        const action = "readQuestions";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('categoryID', categoryID)

        if (categoryID == '5fcdbb93760ce2f85bb62e5ca4f90') progressBar.css({ 'width': '100%' }) //last category will be 100%
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    $('#navigationWrapper').find('button').remove()
                    response.dataSet.forEach(data => {
                        const questionSet = data.questionSet

                        // get the details of a question
                        questionSet.forEach(data => {
                            const questionID = data.questionID
                            const questionTxt = data.questionTxt
                            const inputType = data.inputType
                            const choices = data.choices

                            //mark up for displaying question
                            displayQuestion(answerID, questionID, questionTxt, inputType, choices, tracerQuestionWrapper)
                        })

                    })

                }

                // add navigation button
                const navigationWrapper = $('<div>')
                    .addClass('flex justify-end w-full gap-2')

                const backBtn = $('<button>')
                    .addClass('px-3 py-2 rounded-md hover:bg-gray-300 text-gray-500')
                    .text('Back')
                    .on('click', function () {
                        //go back to previous category
                        count--
                        $('.questions').empty()
                        const prevCategoryName = categoryList[count].categoryName;
                        const prevCategoryID = categoryList[count].categoryID;

                        // update progress bar
                        progressBarMovement(count)
                        //open another set of question
                        $('#categoryNameQuestion').text(prevCategoryName)
                        categoryQuestion(answerID, prevCategoryID)
                    })
                const nextBtn = $('<button>')
                    .addClass('px-3 py-2 rounded-md bg-accent hover:bg-darkAccent text-white font-bold nextBtnQuestion')
                    .text('Next')
                    .on('click', function () {
                        let isCompleted = true
                        let haveCheckedInCheckBox = true
                        let haveSelectedDropDown = true
                        // check if all input type text are answered
                        $('.userinputData').each(function () {
                            let value = $(this).val().trim()
                            if (value === '') isCompleted = false;
                        })

                        // check the dropdown has value (if there's any)
                        const dropDown = $('.dropdownQuestion')
                        if (dropDown.length > 0) {
                            let dropDownNotNull = true;
                            dropDown.each(function () {
                                let value = $(this).val();
                                if (value === null) {
                                    dropDownNotNull = false
                                    return false;
                                }


                            })
                            // if there's nul then don't allow to proceed
                            if (!dropDownNotNull) {
                                haveSelectedDropDown = false
                            }
                        }


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
                        if (areQuestionAnswered() && isCompleted && haveCheckedInCheckBox && haveSelectedDropDown) {
                            count++
                            $('.questions').empty()
                            const nextCategoryName = categoryList[count].categoryName;
                            const nextCategoryID = categoryList[count].categoryID;

                            // update progress bar
                            progressBarMovement(count)
                            //open another set of question
                            $('#categoryNameQuestion').text(nextCategoryName)
                            categoryQuestion(answerID, nextCategoryID)
                        }
                    })

                const submitBtn = $('<button>')
                    .addClass('px-3 py-2 rounded-md bg-green-400 hover:bg-green-500 text-white font-bold hidden submitQuestionnaire')
                    .text('Submit')
                    .on('click', function () {
                        let isCompleted = true
                        let haveCheckedInCheckBox = true
                        let haveSelectedDropDown = true
                        // check if all input type text are answered
                        $('.userinputData').each(function () {
                            let value = $(this).val().trim()
                            if (value === '') isCompleted = false;
                        })

                        // check the dropdown has value (if there's any)
                        const dropDown = $('.dropdownQuestion')
                        if (dropDown.length > 0) {
                            let dropDownNotNull = true;
                            dropDown.each(function () {
                                let value = $(this).val();
                                if (value === null) {
                                    dropDownNotNull = false
                                    return false;
                                }


                            })
                            // if there's nul then don't allow to proceed
                            if (!dropDownNotNull) {
                                haveSelectedDropDown = false
                            }
                        }


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
                        if (areQuestionAnswered() && isCompleted && haveCheckedInCheckBox && haveSelectedDropDown) {
                            // update the progress of the user
                            updateUserProgress(answerID)
                        }
                    })


                if (count === categoryList.length - 1) {
                    nextBtn.addClass('hidden')
                    submitBtn.removeClass('hidden')
                }

                if (count > 0)
                    navigationWrapper.append(backBtn)

                navigationWrapper.append(submitBtn, nextBtn)
                $('#navigationWrapper').append(navigationWrapper)
            },
            error: error => { console.log(error) }
        })
    }

    function progressBarMovement(progress) {
        const progressGoal = categoryList.length;
        //calculating percentage
        const percentage = (progress / progressGoal) * 100;
        progressBar.css({ 'width': percentage + '%' }) //change the level of of progress
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

        try {
            await retrieveCategory(); // Wait for the data to be retrieved
            const firstCategoryName = categoryList[0].categoryName;
            const firstCategoryID = categoryList[0].categoryID;

            // Use await with createAlumniEntry and store the result in answerIDEntry
            const answerIDEntry = await createAlumniEntry();

            $('#frontpageTracer').addClass('hidden');
            // check first if the user is already done taking graduate tracer
            if (answerIDEntry !== 'finished') {
                $('#questionsContainer').removeClass('hidden');

                $('#categoryNameQuestion').text(firstCategoryName);
                categoryQuestion(answerIDEntry, firstCategoryID);
            }
            else {
                $('#finishedContainer').removeClass('hidden') //done finishing graduate tracer
                $('.sub-navbar-wrapper').addClass('hidden') //hide the navbar
            }
        } catch (error) {
            console.error(error);
        }
    });



    function createAlumniEntry() {
        const action = "addNewAnswer";
        const formData = new FormData();
        formData.append('action', action);

        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../PHP_process/answer.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: response => {
                    const answerIDEntry = response;
                    resolve(answerIDEntry);
                },
                error: error => {
                    reject(error);
                }
            });
        });
    }

    async function displayQuestion(answerID, questionID, questionTxt, inputType, choices, containerRoot) {
        // wrapper container
        const questionWrapper = $('<div>')
            .addClass('rounded-md userQuestionTracer');

        // question name
        const question = $('<h3>')
            .addClass('text-lg font-bold')
            .text(questionTxt);

        if (containerRoot == tracerQuestionWrapper) question.removeClass('text-center')
        questionWrapper.append(question);

        try {
            const response = await getAnswer(questionID, answerID);

            let answerTxt = [];
            let choiceIDData = [];

            if (response.response == "Success") {
                answerTxt = response.answerTxt;
                choiceIDData = response.choiceID;
            }

            const questionBody = $('<div>')
                .addClass('flex flex-col gap-2 py-2');

            // check what type of input type is set
            if (inputType === 'Input') {
                // input type
                let questionType = $('<input>')
                    .addClass('border-b border-gray-400 p-2 w-1/2 rounded-b-md outline-none userinputData')
                    .attr('type', 'text')
                    .attr('placeholder', 'Enter your answer')
                    .on('change', function () {
                        const answer = $(this).val();
                        addAnswer(answerID, questionID, answer);
                    });

                if (answerTxt[0] !== "") questionType.val(answerTxt[0]);
                questionBody.append(questionType);
            } else {
                const select = $('<select>')
                    .addClass('w-1/2 px-2 py-4 outline-none border border-gray-400 rounded-lg dropdownQuestion')
                const defaultOption = $('<option value="" selected disabled>--Please choose an option--</option>')
                select.append(defaultOption)
                // get all the choices
                choices.forEach(choice => {
                    const choiceID = choice.choiceID;
                    const choice_text = choice.choice_text;
                    const isSectionChoice = choice.isSectionChoice

                    // check again where it fall to three (radio,dropdown,checkbox)
                    if (inputType === "Radio") {
                        const inputFieldWrapper = $('<div>')
                            .addClass('flex justify-start items-center gap-2 w-1/2 mt-3')
                        let name = questionTxt.replace(' ', '')
                        const max = 1000
                        let id = choiceID + Math.floor(Math.random() * (max + 1))
                        const choiceVal = $('<input>')
                            .attr('type', 'radio')
                            .attr('name', name)
                            .attr('id', id)
                            .val(choiceID)
                            .on('click', function () {
                                // check first if the choice has section question
                                if (isSectionChoice) {
                                    $('#sectionQuestionContainer').empty()
                                    openSectionModal(choiceID, answerID, questionID)
                                }

                                // checking if the answer is have you ever been employed is "no" then show the submit now
                                if (choiceID == 'ed7f8ed8d45c72df2d9d5f5c8771c') {
                                    const progress = categoryList.length;
                                    progressBarMovement(progress)
                                    $('#navigationWrapper').find('.nextBtnQuestion').addClass('hidden')
                                    $('#navigationWrapper').find('.submitQuestionnaire').removeClass('hidden')
                                }
                                else if (choiceID == 'f26cd3188710d81ef34664426f940' || choiceIDData[0] == 'f26cd3188710d81ef34664426f940') {
                                    progressBarMovement(count)
                                    $('#navigationWrapper').find('.nextBtnQuestion').removeClass('hidden')
                                    $('#navigationWrapper').find('.submitQuestionnaire').addClass('hidden')
                                }

                                addAnswer(answerID, questionID, "", choiceID) //add the question

                            })

                        // if have already answer then make it as checked
                        if (choiceIDData[0] !== "" && choiceIDData[0] == choiceID) choiceVal.attr('checked', true)

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
                            .attr('data-is-section-choice', isSectionChoice)
                            .text(choice_text)

                        if (choiceIDData[0] !== "" && choiceID == choiceIDData[0]) {
                            option.attr('selected', true)
                        }
                        select.append(option)
                    }
                    else if (inputType == "Checkbox") {
                        const inputFieldWrapper = $('<div>')
                            .addClass('flex justify-start items-center gap-2 w-1/2 p-2')
                        let name = questionTxt.replace(' ', '')
                        const max = 1000
                        let id = choiceID + Math.floor(Math.random() * (max + 1))
                        const choiceVal = $('<input>')
                            .addClass('checkBoxVal')
                            .attr('type', 'checkbox')
                            .attr('name', name)
                            .attr('id', id)
                            .val(choiceID)
                            .on('click', function () {
                                let isSelected = $(this).prop('checked');
                                // check first if the check box is checked or not
                                if (!isSelected) removeCheckedAnswer(questionID, answerID, choiceID)  // remove the selected choice 
                                else addAnswerCheckBox(answerID, questionID, choiceID)  // insert new data

                            })

                        // Check the checkbox if choiceID is in choiceIDData
                        if (choiceIDData.includes(choiceID)) {
                            choiceVal.prop('checked', true);
                        }

                        const label = $('<label>')
                            .addClass('cursor-pointer')
                            .attr('for', id)
                            .text(choice_text)

                        inputFieldWrapper.append(choiceVal, label)
                        questionBody.append(inputFieldWrapper)
                    }
                });

                if (inputType === "DropDown") {
                    questionBody.append(select); // add select if the input was assigned as dropdown
                    select.on('change', function () {
                        const selectedOption = $(this).find('option:selected');
                        const isSectionChoice = selectedOption.data('is-section-choice');
                        const choiceID = selectedOption.val();

                        // check first if the answer has additional question
                        if (isSectionChoice) {
                            $('#sectionQuestionContainer').empty()
                            openSectionModal(choiceID, answerID)
                        }
                        addAnswer(answerID, questionID, "", choiceID) //adding normal answering

                    });
                }

            }

            // Add data-required attribute to required questions
            if (inputType !== 'Input') {
                questionWrapper.attr('data-required', 'true');
            }

            // skip 1 part of question
            if (choiceIDData[0] == 'e7df2b8246f80bceb558e7bc19bc1') count += 1
            if (choiceIDData[0] == 'ed7f8ed8d45c72df2d9d5f5c8771c') { // Have you ever been employed? No answer
                // update the progress bar to full
                const progress = categoryList.length;
                progressBarMovement(progress)
                $('#navigationWrapper').find('.nextBtnQuestion').addClass('hidden')
                $('#navigationWrapper').find('.submitQuestionnaire').removeClass('hidden')
            }
            else if (choiceIDData[0] == 'f26cd3188710d81ef34664426f940') {
                const progress = count;
                progressBarMovement(progress)
            }
            questionWrapper.append(questionBody);
            containerRoot.append(questionWrapper); // add to the main container
        } catch (error) {
            console.error(error);
        }
    }

    function openSectionModal(choiceID, answerID) {
        $('#sectionModal').removeClass('hidden')
        retrievedSectionData(choiceID, answerID)
    }

    function retrievedSectionData(choiceID, answerID) {
        const action = "getSectionData";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('choiceID', choiceID);

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                $('#sectionModalcontainer').removeClass('hidden')
                // Loop through the data and display questions
                const length = response.length
                for (let i = 0; i < length; i++) {
                    const questionID = response[i][0].questionID;
                    const questionTxt = response[i][0].questionTxt;
                    const inputType = response[i][0].inputType;
                    const choices = response[i][0].choices;

                    const containerRoot = $('#sectionQuestionContainer'); //root container of section modal
                    displayQuestion(answerID, questionID, questionTxt, inputType, choices, containerRoot)
                }

                $('#proceedBtnSection').on('click', function () {
                    let haveInputVal = true
                    let haveCheckedInCheckBox = true
                    let haveSelectedDropDown = true

                    const input = $('#sectionQuestionContainer').find('input')
                    if (input.length > 0) {
                        inputHaveValue = true
                        input.each(function () {
                            let value = $(this).val().trim();

                            // checking all the input if they have value
                            if (value === '') {
                                inputHaveValue = false
                                return false;
                            }
                        })

                        if (!inputHaveValue) haveInputVal = false
                    }

                    // dropdown checker
                    const dropdown = $('#sectionQuestionContainer').find('select')
                    if (dropdown.length > 0) {
                        dropdownHaveValue = true
                        dropdown.each(function () {
                            let value = $(this).val();

                            // checking all the input if they have value
                            if (value === null) {
                                dropdownHaveValue = false
                                return false;
                            }
                        })

                        if (!dropdownHaveValue) haveSelectedDropDown = false
                    }


                    // check box checker
                    const checkBoxes = $('#sectionQuestionContainer').find('.checkBoxVal')
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

                    // check if the user has already clicked the with employer to advance
                    if (choiceID == 'e7df2b8246f80bceb558e7bc19bc1') count += 1
                    // check for proceeding
                    if (haveInputVal && haveSelectedDropDown && haveCheckedInCheckBox)
                        $('#sectionModal').addClass('hidden')
                })

            },
            erro: error => { console.log(error) }
        })
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

    // insert the answer
    function addAnswer(answerID, questionID, answerTxt, choiceID = "") {
        const action = "addAnswer";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('answerID', answerID)
        formData.append('questionID', questionID)
        formData.append('choiceID', choiceID)
        formData.append('answerTxt', answerTxt)

        $.ajax({
            url: '../PHP_process/answer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => { console.log(response) },
            error: error => { console.log(error) },
        })
    }

    function getAnswer(questionID, answerID) {
        const action = "getAnswer";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('questionID', questionID);
        formData.append('answerID', answerID);

        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../PHP_process/answer.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: response => {
                    resolve(response);
                },
                error: error => {
                    reject(error);
                }
            });
        });
    }

    $('.closeSectionModal').on('click', function () {
        // close the section question
        $('#sectionModal').addClass('hidden')
        $('#sectionQuestionContainer').empty()
    })

    // insert the answer
    function addAnswerCheckBox(answerID, questionID, choiceID) {
        const action = "addCheckboxAnswer";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('answerID', answerID)
        formData.append('questionID', questionID)
        formData.append('choiceID', choiceID)

        $.ajax({
            url: '../PHP_process/answer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => { console.log(response) },
            error: error => { console.log(error) },
        })
    }

    function removeCheckedAnswer(questionID, answerID, choiceID) {
        const action = "removeCheckBoxAnswer";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('questionID', questionID)
        formData.append('answerID', answerID)
        formData.append('choiceID', choiceID)

        $.ajax({
            url: '../PHP_process/answer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => { console.log(response) },
            error: error => { console.log(error) },
        })
    }

    // updating from ongoing to done of answering graduate tracer 
    function updateUserProgress(answerID) {
        const action = "updateStatusToDone"
        const formData = new FormData();
        formData.append('action', action)
        formData.append('answerID', answerID)

        $.ajax({
            url: '../PHP_process/answer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                console.log(response)
                if (response == 'Success') {
                    $('#finishedContainer').addClass('hidden')
                    $('#successModal').removeClass('hidden')
                    $("#msgSuccess").text('Thank you for the having time completing this Graduate Tracer Form.')

                    // hide the modal again
                    setTimeout(() => {
                        $('#successModal').addClass('hidden')
                        $("#msgSuccess").text('Your feedback is important to us, and we take all reports seriously')
                        window.location.href = '../student-alumni/homepage.php'; //go back to the main page
                    }, 5000)
                }
            },
            error: error => { console.log(error) }
        })
    }
})