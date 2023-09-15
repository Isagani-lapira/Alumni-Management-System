$(document).ready(function () {

    $('#tracerbtn').on('click', function () {
        $('#formReport').addClass('hidden');
        $('#TracerWrapper').removeClass('hidden');
        $('#categoryWrapper').removeClass('hidden');
        retrieveCategory();
    })


    function retrieveCategory() {
        const action = "retrievedCategory";
        const formData = new FormData();
        formData.append('action', action);

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
                    $('#formTitle').val(formTitle) //add the form title
                    for (let i = 0; i < length; i++) {
                        const categoryID = response.categoryID[i];
                        const categoryName = response.categoryName[i];

                        addCategory(categoryID, categoryName, tracerID);
                    }
                }
            },
            error: error => { console.log(error) }
        })

    }


    function addCategory(categoryID, categoryName, tracerID) {
        const categoryBtn = $('<button>')
            .addClass('w-full border border-gray-300 rounded-lg py-3 categoryBtn inactive relative')
            .text(categoryName)
            .on('click', function () {
                // add edit button
                const editIcon = $('<iconify-icon icon="bx:edit" style="color: white;" width="24" height="24"></iconify-icon>')
                    .addClass('absolute top-2 right-2')
                    .on('click', function () {
                        // allows to be editable the content
                        const buttonElement = $(this).parent()
                            .attr('contentEditable', 'true')
                            .focus()
                            .html('<u>' + categoryName + '</u>') //add underline so that it can be seen that it is editable
                    })
                $(this).append(editIcon)
                $('.categoryBtn').removeClass('active') //remove the last active button
                $('#btnSaveChanges').addClass('hidden') //hide again the savechanges
                //retrieve questions
                $(this).addClass('active').removeClass('inactive')
                retrieveCategoryQuestion(categoryID, tracerID)
            })
            .on('blur', function () {
                // Save the edited text when focus is lost
                const editedCategoryText = $(this).text();
                updateCategoryName(categoryID, editedCategoryText) //update category name

                $(this).click() //refresh the display
                $(this).removeAttr('contentEditable'); //disable the editable text again
                $(this).find('u').remove() //remove the underline indicating the the editable is disabled
                $(this).text(categoryName)
            });

        $('#categoryWrapper').append(categoryBtn)
    }

    function retrieveCategoryQuestion(categoryID, tracerID) {
        const action = "readQuestions";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('categoryID', categoryID)

        //process retrieval of data for category questions
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    response.dataSet.forEach(element => {
                        const categoryID = element.categoryID;
                        const categoryName = element.categoryName
                        const questionSets = element.questionSet;

                        const container = '#questionSetContainer'
                        displayQuestion(categoryID, categoryName, questionSets, container, tracerID, false)
                    })
                }
            },
            error: error => { console.log(error) }
        })
    }


    function displayQuestion(categoryID, categoryName, questionSets, container, tracerID, isSection = true) {

        $('#questionSetContainer').find('.questionSet, .newQuestionBtn').remove()
        $('#categoryName').val(categoryName)
        const addNewQuestionBtn = $('<button>')
            .addClass('block ml-auto bg-blue-400 text-white hover:bg-blue-500 py-2 rounded-lg text-sm px-3 newQuestionBtn')
            .text('Add question')
            .on('click', function () {
                // insert new question
                displaySavedChanges()
                $('#newQuestionModal').removeClass('hidden')
                openCreateNewQuestion(categoryID, tracerID);
            })
        $('#questionSetContainer').append(addNewQuestionBtn)
        // show all the questions with its choices
        questionSets.forEach(questionData => {
            const questionID = questionData.questionID
            const questionTxt = questionData.questionTxt
            const questionType = questionData.inputType
            const choices = questionData.choices


            const choiceInput = $('<option>')
                .attr('value', "Input")
                .text('Input type')
            const choiceRadio = $('<option>')
                .attr('value', "Radio")
                .text('Radio type')
            const choiceDropDown = $('<option>')
                .attr('value', "DropDown")
                .text('DropDown type')
            const choiceCheckbox = $('<option>')
                .attr('value', "Checkbox")
                .text('Checkbox type')

            // Set the selected option based on questionType
            if (questionType === 'Input') {
                choiceInput.prop('selected', true);
            } else if (questionType === 'Radio') {
                choiceRadio.prop('selected', true);
            } else if (questionType === 'DropDown') {
                choiceDropDown.prop('selected', true);
            } else if (questionType === 'Checkbox') {
                choiceCheckbox.prop('selected', true);
            }

            const questionChoicesWrapper = $('<div>')
                .addClass('flex flex-col gap-2')

            //drop down selection of input type
            const questionTypeDropDown = $('<select>')
                .addClass('p-2 w-full text-gray-500 outline-none')
                .append(choiceInput, choiceRadio, choiceDropDown, choiceCheckbox)
                .on('change', function () {
                    displaySavedChanges()
                    const newInputType = $(this).val();
                    changeInputType(newInputType, questionID, questionChoicesWrapper) //change the type of choices
                })

            // get all the choices for a specific question
            choices.forEach(choice => {
                const choiceID = choice.choiceID
                const choice_text = choice.choice_text
                const choicequestionID = choice.questionID
                const sectionQuestion = choice.sectionQuestion
                const isSectionChoice = choice.isSectionChoice

                const choicesWrapper = $('<div>')
                    .addClass('flex gap-1 p-2 wrapperChoices')
                const choiceInput = $('<input>')
                    .addClass('border-b border-gray-300 p-2 flex-1')
                    .val(choice_text)
                    .on('change', function () {
                        // change the option text/value
                        const newChoiceTextVal = $(this).val()
                        displaySavedChanges()
                        changeOption(choiceID, newChoiceTextVal)
                    })
                const removeChoiceBtn = $('<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>')
                    .on('click', function () {
                        // remove a specific choice
                        displaySavedChanges()
                        removeChoice(choiceID, choicesWrapper)
                    })
                const sectionChoiceBtn = $('<iconify-icon class="sectionBtn" icon="uit:web-section-alt" class="p-2" style="color: #afafaf;" width="20" height="20"></iconify-icon>')
                // hover effect
                sectionChoiceBtn.hover(
                    function () {
                        // Change the icon to the solid version on hover-in
                        $(this).attr("icon", "uis:web-section-alt");
                    },
                    function () {
                        // Change the icon back to the original version on hover-out
                        $(this).attr("icon", "uit:web-section-alt");
                    }
                );

                // add indication for a choice with section
                if (isSectionChoice) {
                    // Replace the icon with a different one
                    sectionBtn
                        .attr("icon", "uis:web-section-alt")
                        .css("color", "#00a86b")
                }

                if (questionType !== 'Input') choicesWrapper.append(choiceInput, sectionChoiceBtn, removeChoiceBtn)
                questionChoicesWrapper.append(choicesWrapper)
            })

            const questionName = $('<input>')
                .addClass('text-center w-full font-bold border-b border-gray-300 py-3')
                .val(questionTxt)


            const addOption = $('<button>')
                .addClass('flex items-center gap-2 text-gray-400 m-2')
                .html('<iconify-icon icon="gala:add" style="color: #afafaf;" width="20" height="20"></iconify-icon>' + 'Add option')
                .hover(function () {
                    // over
                    $(this).css({ 'color': '#1769AA' }) //for text
                    $(this).find('iconify-icon').css({ 'color': '#1769AA' }) //for icon
                }, function () {
                    // out
                    $(this).find('iconify-icon').css({ 'color': '#afafaf' })
                    $(this).css({ 'color': '#afafaf' })
                }
                )
                .on('click', function () {
                    const choicesWrapper = $('<div>')
                        .addClass('flex gap-1 p-2')
                    const choiceInput = $('<input>')
                        .addClass('border-b border-gray-300 p-2 flex-1')
                        .attr('placeholder', 'Add option')
                        .on('change', function () {
                            // insert new option
                            displaySavedChanges()
                            const choiceTextVal = $(this).val()
                            let isSectionQuestion = 0;
                            if (!isSection) isSectionQuestion = 1

                            insertSectionChoices(questionID, choiceTextVal, isSectionQuestion)
                        })
                    const removeChoiceBtn = $('<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>')
                        .on('click', function () {
                            choicesWrapper.remove()
                        })
                    choicesWrapper.append(choiceInput, removeChoiceBtn)
                    questionChoicesWrapper.append(choicesWrapper)

                })

            const removeQuestionBtn = $('<iconify-icon icon="octicon:trash-24" class="absolute top-0 -right-14 p-3 rounded-md center-shadow remove-question h-max" style="color: #afafaf;" width="24" height="24"></iconify-icon>')
                .on('click', function () {
                    displaySavedChanges()
                    removeQuestions(questionID, questionWrapper)
                })
            const questionWrapper = $('<div>')
                .addClass('p-2 center-shadow rounded-lg w-4/5 mx-auto questionSet relative')
                .append(questionName, questionTypeDropDown, questionChoicesWrapper, addOption, removeQuestionBtn)

            $('#questionSetContainer').append(questionWrapper)
        })

    }

    function removeQuestions(questionID, container) {
        const action = "removeQuestion";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('questionID', questionID);

        // process archiving the question
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: response => {
                if (response == 'Success') {
                    //remove the display of the question
                    container.remove()
                }
            }
        })
    }
    function insertSectionChoices(questionID, choiceTextVal, isSectionQuestion) {
        const action = "addChoicesSection";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('questionID', questionID)
        formData.append('choiceText', choiceTextVal)
        formData.append('isSectionQuestion', isSectionQuestion)

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => { console.log(response) },
            error: error => { console.log(error) }
        })
    }

    function removeChoice(choiceID, wrapper) {
        const action = "removeChoice";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('choiceID', choiceID);

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                if (response == "Success") wrapper.remove() // remove the button

            },
            error: error => { console.log(error) }
        })
    }

    function displaySavedChanges() {
        // display saved indicator
        $('#btnSaveChanges').removeClass('hidden')
            .html('<iconify-icon icon="line-md:loading-twotone-loop" style="color: #afafaf;" width="20" height="20"></iconify-icon>'
                + 'Saving changes')

        setTimeout(() => {
            $('#btnSaveChanges').removeClass('hidden')
                .html('<iconify-icon icon="dashicons:saved" style="color: #afafaf;" width="20" height="20"></iconify-icon>'
                    + 'Saved')
        }, 3000)
    }

    function changeOption(choiceID, choiceText) {
        const action = "changeChoiceText";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('choiceText', choiceText)
        formData.append('choiceID', choiceID)

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
        })
    }

    function updateCategoryName(categoryID, categoryName) {
        const action = "updateCategoryName"
        //data to be sent
        const formData = new FormData();
        formData.append('action', action)
        formData.append('categoryID', categoryID);
        formData.append('categoryName', categoryName);

        //perform removal
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
        })
    }

    $('#addOptionmodal').on('click', function () {
        //add additional choice field
        const fieldContainer = $('<div>')
            .addClass('fieldWrapper flex items-center gap-2')

        const icon = $('<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>');
        const inputType = $('<input>')
            .addClass('flex-1 py-2')
            .attr('type', "text")
            .attr('placeholder', 'Add choice')

        const removeOption = $('<iconify-icon icon="ei:close" class="cursor-pointer" style="color: #afafaf;" width="20" height="20"></iconify-icon>')
            .on('click', function () {
                fieldContainer.remove()
            })

        fieldContainer.append(icon, inputType, removeOption)
        $('.optionContainer').append(fieldContainer)
    })
    function openCreateNewQuestion(categoryID, formID) {
        $('#newQuestionModal').removeClass('hidden')
        $('#saveNewQuestion').on('click', function () {
            const questionName = $('#newQuestionInputName').val()
            if (questionName !== "") {
                retrieveNewQuestionData(questionName, formID, categoryID)
            }
        })
    }
    function retrieveNewQuestionData(questionName, formID, categoryID, isSectionQuestion = false, choiceID = "") {
        const inputTypeVal = $('#inputTypeModalNew').val();
        // Get all the option choices
        const choices = [];
        $('.fieldWrapper').each(function () {
            let choiceVal = $(this).find('input[type="text"]').val();
            choices.push(choiceVal);
        })

        const QuestionSet = {
            "Question": questionName,
            "choice": choices,
            "InputType": inputTypeVal
        }

        if (!isSectionQuestion) {
            const data = {
                "FormID": formID,
                "CategoryID": categoryID,
                "Question": questionName,
                "InputType": inputTypeVal,
                "choices": choices
            }
            insertNewCategoryQuestion(data) //process insertion of data
        }
        else {
            const data = {
                'FormID': formID,
                'CategoryID': categoryID,
                'ChoiceID': choiceID,
                "QuestionSet": QuestionSet,
            }
            insertNewQuestionSection(data, choiceID)
        }

    }

    function insertNewQuestionSection(data, choiceID) {
        const action = 'addNewSectionQuestion';
        const formData = new FormData();
        formData.append('action', action)
        formData.append('newQuestion', JSON.stringify(data))

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: response => {
                if (response == "Success") {
                    console.log(response)
                    // $('#sectionBody').find('.questionCategoryWrapper').remove()
                    // retrievedSectionData(choiceID)
                    // restartAllVal()
                    // $('#newQuestionModal').addClass('hidden')
                    // //display prompt
                    // $('#promptMsgNewQuestion').removeClass('hidden')
                    // setTimeout(() => {
                    //     $('#promptMsgNewQuestion').addClass('hidden')
                    // }, 3000)
                }
            },
        })
    }

    function insertNewCategoryQuestion(data) {
        const action = "addNewQuestionForCategory";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('data', JSON.stringify(data));

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: response => {
                console.log(response)
                if (response == "Success") {
                    restartAllVal()
                    $('#newQuestionModal').addClass('hidden')
                    //display prompt
                    $('#promptMsgNewQuestion').removeClass('hidden')
                    setTimeout(() => {
                        $('#promptMsgNewQuestion').addClass('hidden')
                    }, 3000)
                }
            },
            error: error => { console.log(error) }
        })
    }

    function restartAllVal() {
        $('#newQuestionModal').addClass('hidden') //hide again the modal

        //restart all the value
        $('#newQuestionInputName').val("")
        $('#inputTypeModalNew').val("")
        $('.fieldWrapper:first input.choicesVal').val("");
        $('.fieldWrapper:not(:first)').remove(); // remove all the choices available and assign it with no value

    }

    function changeInputType(inputType, questionID, container) {
        const action = "changeTypeOfInput"
        const formData = new FormData();
        formData.append('action', action);
        formData.append('inputType', inputType);
        formData.append('questionID', questionID);

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: response => {
                if (response == 'Success') {
                    if (inputType == "Input") {
                        $(container).find('.wrapperChoices').addClass('hidden')
                    }
                    else $(container).find('.wrapperChoices').removeClass('hidden')
                }
            }
        })
    }
})