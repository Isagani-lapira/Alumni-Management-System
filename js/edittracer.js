

$(document).ready(function () {

    const formIDValue = $('#formID').val()
    retrieveCategory(formIDValue) //get the category
    function retrieveCategory(formIDValue) {
        const formID = formIDValue;
        const action = "retrievedCategory";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('formID', formID)

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
                    $('#formTitle').val(formTitle) //add the form title
                    for (let i = 0; i < length; i++) {
                        const categoryID = response.categoryID[i];
                        const categoryName = response.categoryName[i];

                        addCategory(categoryID, categoryName);
                    }
                }
            },
            error: error => { console.log(error) }
        })

        //update the title
        $('#formTitle').on('change', function () {
            updateFormTitle(formID)
            displaySavedChanges()
        })


        $('#btnAddCat').on('click', function () {
            addNewField(formID)
        })

        $('#nextpage').on('click', function () {
            $('#questions').removeClass('hidden')
            $('#categoryContainer, #paginationWrapper, #noteCategory').addClass('hidden');
            $('#subBar').text('Questions')
            retrieveQuestions(formID)
        })
    }
    function addCategory(categoryID, categoryName) {
        const container = $('<div>').addClass('mb-2 flex justify-between items-center')
        const inputField = $('<input>')
            .attr('type', 'text')
            .attr('value', categoryName)
            .addClass('border-b border-gray-400 py-2 px-2 outline-none w-full categoryField')
            .on('change', function () {
                displaySavedChanges()
                const newCategoryName = $(this).val()
                updateCategoryName(categoryID, newCategoryName);
            })
        const removeBtn = $('<iconify-icon icon="healthicons:x" style="color: #afafaf;" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                displaySavedChanges()
                removeCategory(categoryID, container);
            })

        container.append(inputField, removeBtn);
        $('#categoryWrapper').append(container)
    }

    function removeCategory(categoryID, container) {

        //check if the category is newly add with no category saved
        if (categoryID != "") {
            const status = "archived";
            const action = "updateCategory"
            //data to be sent
            const formData = new FormData();
            formData.append('action', action)
            formData.append('categoryID', categoryID);
            formData.append('categoryStatus', status);

            //perform removal
            $.ajax({
                url: '../PHP_process/graduatetracer.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: response => {
                    if (response == "Success") container.remove() //remove the field of the removed item

                },
                error: error => { console.log(error) }
            })
        }
        else container.remove() //remove field with no category saved

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


    function updateFormTitle(formID) {
        const action = "updateTitleForm"
        const formName = $('#formTitle').val()
        //data to be sent
        const formData = new FormData();
        formData.append('action', action)
        formData.append('formID', formID);
        formData.append('formName', formName);

        //perform removal
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
        })
    }

    function displaySavedChanges() {
        //show message to notify user that their action is always changing
        $('#saveMsg')
            .html(
                '<iconify-icon id="changesIcon" icon="ic:sharp-published-with-changes" style="color: #afafaf;" width="20" height="20"></iconify-icon>' +
                'Saving changes...'
            )

        //set back to default after 2seconds
        setTimeout(() => {
            $('#saveMsg').text('Saved')
            $('#changesIcon').addClass('hidden')
        }, 2000)
    }

    function addNewField(formID) {
        const wrapper = $('<div>')
            .addClass('flex items-center justify-between mb-2')
        const inputField = $('<input>')
            .addClass('border-b border-gray-400 py-2 px-2 outline-none w-full categoryField')
            .attr('placeholder', 'Add Category')
        const removeBtn = $('<span>')
            .html('<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>')
            .on('click', function () {
                categoryID = ""
                removeCategory(categoryID, wrapper)
            })

        inputField.on('change', function () {
            displaySavedChanges()
            const newCategoryVal = $(this).val()
            addNewCategory(formID, newCategoryVal, removeBtn, wrapper)
        })

        wrapper.append(inputField, removeBtn)
        $('#categoryWrapper').append(wrapper)
    }

    function addNewCategory(formID, categoryValue, removeBtn, container) {
        const categoryName = categoryValue
        const action = "insertNewCategory";
        const formData = new FormData();

        //data to be sent
        formData.append('action', action)
        formData.append('categoryName', categoryName)
        formData.append('formID', formID)

        //proccess insertion
        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: response => {
                if (response.result == "Success") {
                    const categoryID = response.categoryID //use to remove a newly added category
                    removeBtn.on('click', function () {
                        removeCategory(categoryID, container)
                    })
                }
            }
        })
    }


    function retrieveQuestions(formID) {
        const action = "readQuestions";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('formID', formID)

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

                        const container = '#questions'
                        displayQuestion(categoryID, categoryName, questionSets, formID, container, false)
                    })
                }
            },
            error: error => { console.log(error) }
        })
    }

    function displayQuestion(categoryID, categoryName, questionSets, formID, container, isQuestion, choiceIDVal = "") {
        const categoryWrapper = $('<div>')
            .addClass('rounded-lg mx-auto mb-6 p-1 relative questionCategoryWrapper')

        const headerCategory = $('<header>')
            .addClass('w-full text-center py-5 font-bold text-lg text-white bg-darkAccent rounded-t-lg')
            .text(categoryName)

        const addNewQuestion = $('<iconify-icon class="p-3 rounded-md hidden center-shadow h-max absolute top-1 right-1" icon="gala:add" style="color: #AFAFAF;" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                openCreateNewQuestion(categoryID, formID);
            })
        addNewQuestion.hover(
            function () {
                // Mouse enters the element (hover)
                $(this).css({
                    "background": '#FFFFFF',
                    "color": 'red'
                })
            },
            function () {
                // Mouse leaves the element (hover out)
                $(this).css({
                    "background": 'transparent',
                    "color": '#AFAFAF'
                })
            }
        );
        const bodyCategory = $('<div>')
            .addClass('p-3 flex flex-col gap-2')

        //get all the question for every category they belong
        questionSets.forEach(questionData => {
            const questionID = questionData.questionID
            const questionTxt = questionData.questionTxt
            const questionType = questionData.inputType
            const choices = questionData.choices
            const questionWrapper = $('<div>')
                .addClass('relative center-shadow w-4/5 mx-auto')

            const questionName = $('<input>')
                .addClass('text-center font-bold center-shadow py-3 w-full rounded-t-md')
                .val(questionTxt)

            if (!isQuestion) {
                questionName.addClass('bg-accent text-white')
            } else {
                questionName.removeClass('bg-accent text-white').addClass('text-gray-500 border-b border-gray-400 border-t-4')
            }
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

            //drop down selection of input type
            const questionTypeDropDown = $('<select>')
                .addClass('p-2 w-full text-gray-500 outline-none')
                .append(choiceInput, choiceRadio, choiceDropDown, choiceCheckbox)
                .on('change', function () {
                    const newInputType = $(this).val();
                    changeInputType(newInputType, questionID, questionBody)
                })

            const questionBody = $('<div>')
                .addClass('flex flex-col gap-2 p-3 rounded-b-lg')
                .append(questionTypeDropDown)

            //get all the available choices for every questions
            choices.forEach(choice => {
                const choiceID = choice.choiceID
                const choice_text = choice.choice_text
                const choicequestionID = choice.questionID
                const sectionQuestion = choice.sectionQuestion
                const isSectionChoice = choice.isSectionChoice

                const wrapper = $('<div>')
                    .addClass('flex items-center justify-between mb-2 wrapperChoices')

                //mark up for input field with remove button
                const inputField = $('<input>')
                    .addClass('border-b border-gray-400 py-2 px-2 outline-none w-full categoryField text-gray-500')
                    .val(choice_text)
                    .on('change', function () {
                        displaySavedChanges()
                        const newTextVal = $(this).val()
                        changeOption(choiceID, newTextVal)
                    })

                const sectionBtn = $('<iconify-icon icon="uit:web-section-alt" class="p-2" style="color: #afafaf;" width="20" height="20"></iconify-icon>')
                    .attr('title', 'Add section for this choice')
                    .on('click', function () {
                        if (!isSectionChoice) createSection(categoryID, choiceID, formID)//add section per category
                        else {
                            retrievedSectionData(choiceID)
                        }

                    })

                sectionBtn.hover(
                    function () {
                        // Change the icon to the solid version on hover-in
                        $(this).attr("icon", "uis:web-section-alt");
                    },
                    function () {
                        // Change the icon back to the original version on hover-out
                        $(this).attr("icon", "uit:web-section-alt");
                    }
                );

                if (isSectionChoice) {
                    // Replace the icon with a different one
                    sectionBtn
                        .attr("icon", "uis:web-section-alt")
                        .css("color", "#00a86b")
                }

                const removeBtn = $('<span>')
                    .html('<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>')
                    .on('click', function () {
                        displaySavedChanges()
                        removeChoice(choiceID, wrapper)
                    })



                wrapper.append(inputField, sectionBtn, removeBtn)

                if (questionType != "Input")
                    questionBody.append(wrapper)

                if (isQuestion) sectionBtn.addClass('hidden')

            })


            const removeQuestion = $('<iconify-icon icon="octicon:trash-24" class="p-2 rounded-md center-shadow h-max remove-question absolute top-0 -right-12" style="color: #afafaf;" width="24" height="24"></iconify-icon>')
                .on("click", function () {
                    //change the status of the question to archived
                    displaySavedChanges()
                    removeQuestions(questionID, questionWrapper)
                })
            // Add a animation
            removeQuestion.hover(
                function () {
                    // Mouse over
                    $(this).css('color', 'red');
                },
                function () {
                    // Mouse out
                    $(this).css('color', '#afafaf');
                }
            );

            //add choice button
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
                    //add new option
                    const newinputWrapper = $('<div>')
                        .addClass('flex items-center mb-2')
                    const newiconRadio = $('<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>')
                    const newinputField = $('<input>')
                        .addClass('py-2 px-2 outline-none w-4/5 flex-1 choices')
                        .attr('placeholder', 'option')
                        .on('change', function () {
                            displaySavedChanges()
                            const choiceTextVal = $(this).val()
                            let isSectionQuestion = 0;
                            if (isQuestion) isSectionQuestion = 1

                            insertSectionChoices(questionID, choiceTextVal, isSectionQuestion)
                        })
                    const removeOption = $('<iconify-icon icon="ei:close" style="color: #afafaf;" width="20" height="20"></iconify-icon>')
                        .on('click', function () {
                            newinputWrapper.remove()
                        })
                    newinputWrapper.append(newiconRadio, newinputField, removeOption)
                    questionBody.append(newinputWrapper)
                })

            questionWrapper.append(questionName, questionBody, addOption, removeQuestion)

            bodyCategory.append(questionWrapper)
        })

        if (!isQuestion) {
            categoryWrapper.append(headerCategory)
            categoryWrapper.addClass('center-shadow w-full')
            bodyCategory.addClass('center-shadow')
            addNewQuestion.removeClass('hidden')
        } else {
            categoryWrapper.addClass('w-4/5')
            $('#catIDHolder').val(categoryID)
            $('#formIDHolder').val(formID)
            $('#choiceIDHolder').val(choiceIDVal)
        }

        //display on root container

        categoryWrapper.append(addNewQuestion, bodyCategory)
        $(container).append(categoryWrapper)

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

    function removeQuestions(questionID, container) {
        const action = "removeQuestion";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('questionID', questionID);

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

    function createSection(categoryID, choiceID, formID) {
        const container = $('<div>')
            .addClass('post modal fixed inset-0 z-50 flex items-center justify-center p-3')

        const modalContainer = $('<div>')
            .addClass('modal-container w-1/2 h-4/5 bg-white rounded-lg p-3 text-greyish_black flex flex-col gap-2 border-t-8 border-blue-400')

        const header = $('<header>')
            .addClass('font-bold text-4xl text-center text-blue-400 py-2 border-b border-gray-300')
            .text('Section')

        // body of modal
        const body = $('<div>')
            .addClass('h-full overflow-y-auto')
        createSecQuestion(body)

        // footer part
        const cancelBtn = $('<button>')
            .addClass('text-gray-400 hover:text-gray-500 text-sm')
            .text('Cancel')
            .on('click', function () {
                //close the modal
                container.remove()
            })

        const saveBtn = $('<button>')
            .addClass('text-white px-4 py-2 rounded-md bg-green-400 hover:bg-green-500')
            .text('Save Section')
            .on('click', function () {
                insertSectionData(categoryID, choiceID, formID, container);
            })
        const footer = $('<div>')
            .addClass('flex justify-end gap-2')
            .append(cancelBtn, saveBtn)

        // append to their corresponding container
        modalContainer.append(header, body, footer)
        container.append(modalContainer)
        $('body').append(container)

    }

    function createSecQuestion(body, isFirst = false) {
        const container = $('<div>')
            .addClass('center-shadow w-4/5 rounded-md border-t-4 border-gray-400 p-3 mx-auto flex flex-col relative mb-2 questionnaireSection ')

        const question = $('<input>')
            .addClass('border-b border-gray-400 py-2 px-2 outline-none w-full categoryField text-center text-lg font-bold sectionQuestion')
            .attr('type', 'text')
            .attr('placeholder', 'Untitled Question')

        // body part
        const bodyPart = $('<div>')

        //input type option
        const optInput = $('<option>').val('Input').text('Input type')
        const optRadio = $('<option>').val('Radio').text('Radio Choice').attr('selected', true)
        const optDropDown = $('<option>').val('DropDown').text('DropDown type')
        const optCheckBox = $('<option>').val('Checkbox').text('Checkbox Type')
        const questionType = $('<select>')
            .append(optRadio, optInput, optDropDown, optCheckBox)
            .addClass('text-gray-400 py-3 outline-none w-full')

        // input field
        const optionContainer = $('<div>')
        const inputWrapper = $('<div>')
            .addClass('flex items-center mb-2')
        const iconRadio = $('<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>')
        const inputField = $('<input>')
            .addClass('py-2 px-2 outline-none w-4/5 choices')
            .attr('placeholder', 'option')
        inputWrapper.append(iconRadio, inputField)
        optionContainer.append(inputWrapper)

        //add choice button
        const addOption = $('<button>')
            .addClass('flex items-center gap-2 text-gray-400 my-2')
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
                //add new option
                const newinputWrapper = $('<div>')
                    .addClass('flex items-center mb-2')
                const newiconRadio = $('<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>')
                const newinputField = $('<input>')
                    .addClass('py-2 px-2 outline-none w-4/5 flex-1 choices')
                    .attr('placeholder', 'option')
                const removeOption = $('<iconify-icon icon="ei:close" style="color: #afafaf;" width="20" height="20"></iconify-icon>')
                    .on('click', function () {
                        newinputWrapper.remove()
                    })
                newinputWrapper.append(newiconRadio, newinputField, removeOption)
                optionContainer.append(newinputWrapper)
            })

        //remove question
        const removeQuestion = $('<iconify-icon icon="octicon:trash-24" class="p-3 rounded-md center-shadow h-max" style="color: #afafaf;" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                container.remove()
            })
            .hover(function () {
                // over
                $(this).css('color', 'red');
            }, function () {
                // out
                $(this).css('color', '#afafaf');
            })

        //check if the question is first
        if (!isFirst) removeQuestion.addClass('hidden')

        const addQuestion = $('<iconify-icon class="p-3 rounded-md center-shadow h-max" icon="gala:add" style="color: #347cb5;" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                //add new question to the section

                //check first if the question before create new is have value
                if (question.val() !== "") {
                    question.addClass('border-gray-400').removeClass('border-red-400') //back to default
                    createSecQuestion(body, true)
                }
                else question.removeClass('border-gray-400').addClass('border-red-400') //add warning color

            })

        const additionalChoices = $('<div>')
            .addClass('flex flex-col absolute top-0 -right-14 gap-2')
            .append(addQuestion, removeQuestion)

        //add to their corresponding container
        bodyPart.append(questionType, optionContainer, addOption)
        container.append(question, additionalChoices, bodyPart)
        body.append(container)
    }

    function retrievedSectionData(choiceID) {
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
            success: response => {
                const data = JSON.parse(response); // Assuming the response is in JSON format
                $('#sectionModalcontainer').removeClass('hidden')
                // Loop through the data and display questions
                data.forEach(questionSet => {
                    const categoryID = questionSet[0].categoryID;
                    const formID = questionSet[0].formID
                    const container = '#sectionBody';
                    displayQuestion(categoryID, "", questionSet, formID, container, true, choiceID)
                });
            },
            erro: error => { console.log(error) }
        })
    }


    sectionQuestion = []
    function insertSectionData(categoryID, choiceID, formID, modal) {
        const questionSet = []
        $('.questionnaireSection').each(function () {
            const question = $(this).find('.sectionQuestion').val();
            const inputType = $(this).find('select').val();

            const choicesArray = [];
            $(this).find('.choices').each(function () {
                const choiceVal = $(this).val();
                choicesArray.push(choiceVal)
            });

            const questionObj = {
                'Question': question,
                'InputType': inputType,
                'choice': choicesArray,
            }
            questionSet.push(questionObj)
        })
        const data = {
            'FormID': formID,
            'CategoryID': categoryID,
            'ChoiceID': choiceID,
            'QuestionSet': questionSet
        }

        sectionQuestion.push(data)
        processInsertionOfSection(sectionQuestion, modal);
    }

    function processInsertionOfSection(sectionData, modal = "") {
        const action = 'addSectionData';
        const formData = new FormData();
        formData.append('action', action)
        formData.append('sectionData', JSON.stringify(sectionData))

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: response => {
                console.log(response)
                if (response == 'Success') {
                    modal.remove()
                    $('#promptMsgSection').removeClass('hidden') //display the message

                    setTimeout(() => {
                        $('#promptMsgSection').addClass('hidden') //hide the message
                    }, 3000);
                }
            },
            error: error => { console.log(error) }
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

    function openCreateNewQuestion(categoryID, formID) {
        $('#newQuestionModal').removeClass('hidden')
        $('#saveNewQuestion').on('click', function () {
            const questionName = $('#newQuestionInputName').val()
            if (questionName !== "") {
                retrieveNewQuestionData(questionName, formID, categoryID)
            }
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

    $('#inputTypeModalNew').on('change', function () {
        $selectedType = $(this).val();
        if ($selectedType == 'Input') $('.optionContainer').addClass('hidden')
        else $('.optionContainer').removeClass('hidden')
    })

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


    $('#closeQuestionModal').on('click', restartAllVal) //close and restart everything

    function restartAllVal() {
        $('#newQuestionModal').addClass('hidden') //hide again the modal

        //restart all the value
        $('#newQuestionInputName').val("")
        $('#inputTypeModalNew').val("")
        $('.fieldWrapper:first input.choicesVal').val("");
        $('.fieldWrapper:not(:first)').remove(); // remove all the choices available and assign it with no value

    }

    // for section adding question
    $('.iconAddModal').on('click', function () {
        const formID = $('#formIDHolder').val()
        const categoryID = $('#catIDHolder').val()
        const choiceID = $('#choiceIDHolder').val()

        $('#newQuestionModal').removeClass('hidden')
        $('#saveNewQuestion').on('click', function () {
            const questionName = $('#newQuestionInputName').val()
            if (questionName !== "") {
                retrieveNewQuestionData(questionName, formID, categoryID, true, choiceID)
            }
        })
    })


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
                    $('#sectionBody').find('.questionCategoryWrapper').remove()
                    retrievedSectionData(choiceID)
                    restartAllVal()
                    $('#newQuestionModal').addClass('hidden')
                    //display prompt
                    $('#promptMsgNewQuestion').removeClass('hidden')
                    setTimeout(() => {
                        $('#promptMsgNewQuestion').addClass('hidden')
                    }, 3000)
                }
            },
        })
    }

    // close the section modal
    $('#sectionModalcontainer').on('click', function (e) {
        const target = e.target
        const modal = $('#sectionModal')

        if (!modal.is(target) && !modal.has(target).length) {
            $('#sectionModalcontainer').addClass('hidden')
        }
    })

})

