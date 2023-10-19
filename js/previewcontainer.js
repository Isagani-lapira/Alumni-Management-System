$(document).ready(function () {

    let categories = []
    let trackerCategory = 0

    $('#previewFormBtn').on('click', function () {
        $('#previewForm').removeClass('hidden')
        $('#tracerRepo').addClass('hidden')
        $('#formReport').addClass('hidden')

        // store the data that retrieved
        retrieveCategory()
            .then(categoryID => {
                categories = categoryID
                const firstCategoryID = categories[trackerCategory];
                retrieveCategoryQuestion(firstCategoryID)
            })
    })

    $('#backFromPreviewBtn').on('click', function () {
        $('#previewForm').addClass('hidden')
        $('#tracerRepo').removeClass('hidden')
        $('#formReport').addClass('hidden')

        // restart everything
        categories = []
        trackerCategory = 0
        $('#previewContainer').children(":not(#categoryNamePrev)").remove()
        $('#previousPreviewBtn').addClass('hidden')
    })

    function retrieveCategory() {
        const action = "retrievedCategory";
        const formData = new FormData();
        formData.append('action', action);

        // retrieve categories
        return new Promise((resolve) => {
            $.ajax({
                url: '../PHP_process/graduatetracer.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: response => {
                    if (response.result == "Success") {
                        const categoryID = response.categoryID.map(category => category); // Copy the category IDs
                        resolve(categoryID)
                    }
                },
            })
        })

    }


    // get the first category questions
    function retrieveCategoryQuestion(categoryID) {
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
                        const categoryName = element.categoryName
                        const questionSets = element.questionSet;
                        displayQuestion(categoryName, questionSets)
                    })
                }
            }
        })
    }

    function displayQuestion(categoryName, questionSets) {
        $('#categoryNamePrev').text(categoryName)

        let countQuestion = 1; //for counting question
        // retrieve all the questions
        questionSets.forEach(value => {
            const inputType = value.inputType
            const questionID = value.questionID
            const questionWrapper = $('<div>')
                .addClass('w-full')

            // questions
            const question = $('<p>')
                .addClass('flex gap-2 text-lg')
                .text(countQuestion + '.) ' + value.questionTxt)


            // retrieve all the choices
            const choiceContainer = $('<div>').addClass('flex flex-col gap-2 mt-3')
            const selectWrapper = $('<select>').addClass('w-1/2 border border-gray-300 p-3 rounded-md')

            if (inputType !== "Input") {
                value.choices.forEach(choice => {
                    const choiceID = choice.choiceID
                    const choice_text = choice.choice_text
                    // radio type question
                    if (inputType === 'Radio') {
                        const choiceWrapper = $('<div>').addClass('flex gap-2 items-center')
                        const radio = $('<input>').attr('type', 'radio').attr('id', choiceID).attr('name', questionID)
                        const choiceTxt = $('<label>').attr('for', choiceID).text(choice_text)

                        choiceWrapper.append(radio, choiceTxt)
                        choiceContainer.append(choiceWrapper)
                    }
                    // dropdown question
                    else if (inputType === 'DropDown') {
                        const option = $('<option>').val(choiceID).text(choice_text)
                        selectWrapper.append(option)
                    }
                })
            }
            else {
                const input = $('<input>')
                    .attr('placeholder', 'Enter your answer')
                    .addClass('border-b border-gray-400 prevInput p-3 w-1/2');


                choiceContainer.append(input)
            }


            if (inputType === 'DropDown') choiceContainer.append(selectWrapper)

            questionWrapper.append(question, choiceContainer)
            $('#previewContainer').append(questionWrapper)
            countQuestion++
        })
    }

    // retrieve new set of question
    $('#nextPreviewBtn').on('click', function () {
        // display new questions
        trackerCategory++;
        $('#previewContainer').children(":not(#categoryNamePrev)").remove()
        const categoryID = categories[trackerCategory]
        retrieveCategoryQuestion(categoryID)
        $('#previousPreviewBtn').removeClass('hidden')

        // check  if the end of category
        if (trackerCategory === categories.length - 1) $(this).addClass('hidden')
        else $(this).removeClass('hidden')

    })

    $('#previousPreviewBtn').on('click', function () {
        trackerCategory--;
        $('#previewContainer').children(":not(#categoryNamePrev)").remove()
        const categoryID = categories[trackerCategory]
        retrieveCategoryQuestion(categoryID)
        $('#nextPreviewBtn').removeClass('hidden')

        // check  if the end of category
        if (trackerCategory === 0) $(this).addClass('hidden')
        else $(this).removeClass('hidden')
    })

})