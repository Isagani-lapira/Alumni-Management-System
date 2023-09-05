
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
                    container.remove()
                }
            },
            error: error => { console.log(error) }
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
})

