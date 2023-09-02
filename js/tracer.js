$(document).ready(function () {

    let pageNo = 1
    let categories = []
    let categoryIndex = 0
    $('#btnAddCat').on('click', function () {

        const wrapper = $('<div>')
            .addClass('flex items-center justify-between mb-2')
        const inputField = $('<input>')
            .addClass('border-b border-gray-400 py-2 px-2 outline-none w-full categoryField')
            .attr('placeholder', 'Add Category')
        const removeBtn = $('<span>')
            .html('<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>')
            .on('click', function () {
                wrapper.remove()
            })

        wrapper.append(inputField, removeBtn)
        $('#categoryWrapper').append(wrapper)
    })

    $('#nextpage').on('click', function () {
        let isCompleted = true
        //check if all the inputs are completed
        $('.categoryField').each(function () {
            let value = $(this).val()
            if (value == "") {
                isCompleted = false
            }
            else categories.push(value)

        })

        //change the view
        if (isCompleted) {
            $('#categoryContainer').addClass('hidden')
            $('#paginationWrapper').addClass('hidden')
            changeView(categories[categoryIndex])
        }
    })

    let idKey = 0
    function changeView(category) {
        $('#subBar').text(category) //change text based on the currently working category
        pageNo += 1
        //create new page
        const page = $('<div>')
            .addClass('h-max')
            .attr('id', "pageNo" + pageNo)
        $('#categorySection').append(page)
        idKey += 1
        addQuestionnaire(pageNo)
        //hide the last page
        // console.log(pageNo)
        if (pageNo > 2) {
            $("#pageNo" + (pageNo - 1)).addClass('hidden');
        }
    }

    const questionnaireData = [];
    function addQuestionnaire(pageNoElement) {
        //mark up for q uestions
        const container = $('<div>')
            .addClass('flex gap-2 justify-center mb-2 relative w-full')
        const containerQuestion = $('<div>')
            .addClass('rounded-lg border-t-8 border-accent p-5 center-shadow w-full')
        const questionWrapper = $('<div>')
            .addClass("flex flex-col gap-2")
        const choices = $('<div>')
            .addClass('flex flex-col')
        const fieldWrapper = $('<div>')
            .addClass('flex items-center mb-2')

        const question = $('<input>')
            .addClass('border-b border-gray-400 py-2 px-2 outline-none w-full categoryField text-center text-lg font-bold')
            .attr('type', 'text')
            .attr('placeholder', 'Untitled Question')
            .attr('id', 'questionID' + idKey)

        //question type
        inputClass = 'inputType' + idKey
        const optInput = $('<option>').val('Input').text('Input type')
        const optRadio = $('<option>').val('Radio').text('Multiple Choice').attr('selected', true)
        const optDropDown = $('<option>').val('DropDown').text('DropDown type')
        const optCheckBox = $('<option>').val('Checkbox').text('Checkbox Type')
        const questionType = $('<select>')
            .append(optRadio, optInput, optDropDown, optCheckBox)
            .addClass('text-gray-400 py-2 outline-none ' + inputClass)


        const navigationWrapper = $('<div>')
            .addClass('flex items-center justify-end gap-2')

        // first choice
        fieldClass = 'choices' + idKey
        const iconRadio = $('<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>')
        const inputField = $('<input>')
            .addClass('py-2 px-2 outline-none w-4/5 ' + fieldClass)
            .attr('placeholder', 'choice')

        const addQuestion = $('<iconify-icon class="p-3 rounded-md center-shadow h-max my-2 absolute -right-16" icon="gala:add" style="color: #347cb5;" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                const value = question.val()
                //add new question
                if (value != "") {
                    navigationWrapper.remove()
                    addQuestionnaire(pageNoElement)
                    question.removeClass('border-accent').addClass('border-gray-400 ')
                }
                else question.addClass('border-accent').removeClass('border-gray-400 ')
            })


        const addChoices = $('<button>')
            .addClass('text-gray-400 w-max py-3 rounded-md flex items-center gap-2')
            .html(
                '<iconify-icon icon="gala:add" style="color: #afafaf;" width="24" height="24"></iconify-icon>' +
                'Add choices')
            .on('click', function () {
                // Check if all existing choices have values
                let isCompleted = true;
                const containerElement = $(this).closest('.relative'); // Find the closest container
                containerElement.find('.choicesField').each(function () {
                    const element = $(this);
                    if (element.val() === "") {
                        isCompleted = false;
                        element.addClass('border-b border-accent')
                    } else element.removeClass('border-b border-accent')
                });

                // add new field if the choices available is completed
                if (isCompleted) {
                    // Add another choice
                    const newFieldWrapper = $('<div>')
                        .addClass('flex items-center mb-2');

                    fieldClass = 'choices' + idKey
                    const newIconRadio = $('<iconify-icon icon="bx:circle" style="color: #afafaf;" width="24" height="24"></iconify-icon>');
                    const newInputField = $('<input>')
                        .addClass('py-2 px-2 outline-none flex-1 ' + fieldClass)
                        .attr('placeholder', 'choice')

                    const removeBtn = $('<span>')
                        .html('<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>')
                        .on('click', function () {
                            newFieldWrapper.remove() //remove the field
                        })
                    newFieldWrapper.append(newIconRadio, newInputField, removeBtn);
                    choices.append(newFieldWrapper);
                }

            })


        const prevBtn = $('<button>')
            .addClass('text-gray-500 hover:bg-gray-300 py-2 px-4 rounded-md')
            .text('Previous')
        const nextBtn = $('<button>')
            .addClass('text-white bg-accent px-4 py-2 rounded-md')
            .text('Next')
            .on('click', function () {
                addQuestionData(idKey)
                categoryIndex++
                changeView(categories[categoryIndex])
            });
        const submitBtn = $('<button>')
            .addClass('text-white bg-blue-400 hover:bg-blue-500 px-4 py-2 rounded-md')
            .text('Create')
            .on('click', function () {
                addQuestionData(idKey) // collect the last data

                const title = $('#formTitle').val();
                const collectionData = {
                    title: title,
                    data: questionnaireData
                }
                console.log(collectionData)
            })

        //hide the next when there's no more category
        if (categoryIndex + 1 == categories.length) {
            nextBtn.addClass('hidden')
        }

        questionType.on('change', function () {
            const inputType = $(this).val()
            if (inputType == "Input") {
                //hide the fields
                fieldWrapper.addClass('hidden')
                addChoices.addClass('hidden')
            } else {
                fieldWrapper.removeClass('hidden')
                addChoices.removeClass('hidden')
            }
        })
        //append all element to their respective container
        fieldWrapper.append(iconRadio, inputField)
        choices.append(questionType, fieldWrapper)
        questionWrapper.append(question, choices, addChoices)
        containerQuestion.append(questionWrapper)
        container.append(containerQuestion, addQuestion, nextBtn)
        navigationWrapper.append(prevBtn, nextBtn, submitBtn)
        $('#pageNo' + pageNoElement).append(container, navigationWrapper) //to root
    }

    function addQuestionData(idKey) {
        console.log(idKey)

        const data = []
        let value = $('#' + 'questionID' + idKey).val()
        choicesArr = []
        let inputType = ""
        $('.' + 'choices' + idKey).each(function () {
            const choiceVal = $(this).val()
            choicesArr.push(choiceVal)
        })

        $('.' + 'inputType' + idKey).each(function () {
            const inputVal = $(this).val();
            inputType = inputVal
        })
        //data in question
        const questionData = {
            Question: value,
            inputType: inputType,
            choices: choicesArr
        }

        data.push(questionData) //store the data in question

        //object to compile both category and data (question,choices)
        categQuestion = {
            Category: categories[categoryIndex],
            Data: data
        }
        questionnaireData.push(categQuestion)
    }

})