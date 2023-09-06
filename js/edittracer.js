
$(document).ready(function () {

    retrieveCategory() //get the category
    function retrieveCategory() {
        const formID = "78b1f3dc4af9cef8b6841e8cd0200"; //to be replaced
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
                    if (response == "Success") {
                        container.remove() //remove the field of the removed item
                    }
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

                        displayQuestion(categoryID, categoryName, questionSets)
                    })
                }
            },
            error: error => { console.log(error) }
        })
    }

    function displayQuestion(categoryID, categoryName, questionSets) {
        const categoryWrapper = $('<div>')
            .addClass('center-shadow rounded-lg w-full mb-6 p-1')

        const headerCategory = $('<header>')
            .addClass('w-full text-center py-5 font-bold text-lg text-white bg-darkAccent rounded-t-lg')
            .text(categoryName)

        const bodyCategory = $('<div>')
            .addClass('p-3 flex flex-col gap-2 center-shadow')

        //get all the question for every category they belong
        questionSets.forEach(questionData => {
            const questionID = questionData.questionID
            const questionTxt = questionData.questionTxt
            const questionType = questionData.inputType
            const choices = questionData.choices

            const questionName = $('<input>')
                .addClass('text-center font-bold center-shadow py-2 w-full bg-accent text-white rounded-t-md')
                .val(questionTxt)

            const questionBody = $('<div>')
                .addClass('flex flex-col gap-2 center-shadow p-3 rounded-b-lg')

            //get all the available choices for every questions
            choices.forEach(choice => {
                const choiceID = choice.choiceID
                const choice_text = choice.choice_text
                const choicequestionID = choice.questionID

                const inputField = $('<input>')
                    .addClass('border-b border-gray-400 py-2 px-2 outline-none w-full categoryField text-gray-500')
                    .val(choice_text)
                const removeBtn = $('<span>')
                    .html('<iconify-icon icon="ant-design:close-outlined" style="color: #626262;" width="20" height="20"></iconify-icon>')


                const wrapper = $('<div>')
                    .addClass('flex items-center justify-between mb-2')
                    .append(inputField, removeBtn)

                questionBody.append(wrapper)

            })

            const questionWrapper = $('<div>')
                .append(questionName, questionBody)

            bodyCategory.append(questionWrapper)
        })

        //display on root container
        categoryWrapper.append(headerCategory, bodyCategory)
        $('#questions').append(categoryWrapper)
    }
})

