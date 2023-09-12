$(document).ready(function () {

    // open the repository of tracer
    $('#tracerbtn').on('click', function () {
        $('#tracerRepo').removeClass('hidden');
        $('#formReport').addClass('hidden');
        retrieveTracerData()
    })


    function retrieveTracerData() {
        const action = "retrieveAllTracerForm";
        const formdata = new FormData();
        formdata.append('action', action);

        $.ajax({
            url: '../PHP_process/graduatetracer.php',
            method: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    const length = response.formID.length
                    $('#repoContainer').find('.tracerWrapper').remove() //remove the display retrieve before
                    for (let i = 0; i < length; i++) {
                        let formID = response.formID[i];
                        let formName = response.formName[i];
                        let year_created = response.year_created[i];

                        displayForm(formID, formName, year_created); //display mark up for tracer repository
                    }
                }
            },
            error: error => { console.log(error) },
        })
    }

    function displayForm(formID, formName, year_created) {
        const formWrapper = $('<div>')
            .addClass('center-shadow rounded-lg flex flex-col tracerWrapper')

        // header part
        const headerPart = $('<div>')
            .addClass('bg-accent text-white rounded-t-lg p-3')
        const tracerName = $('<h3>')
            .addClass('text-lg font-bold text-center')
            .text(formName);
        const yearCreater = $('<span>')
            .addClass('text-sm italic text-gray-300 block mx-auto text-center')
            .text(year_created);

        // bottom part
        const footerPart = $('<div>')
            .addClass('bg-gray-300 text-gray-500 rounded-b-lg px-3 py-4 flex justify-between items-center')

        const removeBtn = $('<button>')
            .addClass('text-red-400 font-semibold text-xs hover:text-red-500')
            .text('Remove')

        const viewBtn = $('<iconify-icon icon="grommet-icons:next" class="text-blue-400 hover:text-blue-500" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                //open the edit tracer of the selected tracer
                let encodedID = encodeURIComponent(formID);
                window.open('../admin/editracer.php?id=' + encodedID, '_blank')
            })

        headerPart.append(tracerName, yearCreater)
        footerPart.append(removeBtn, viewBtn)
        formWrapper.append(headerPart, footerPart)
        $('#repoContainer').append(formWrapper) //root container
    }

    $('#backToGraphForm').on('click', function () {
        $('#tracerRepo').addClass('hidden');
        $('#formReport').removeClass('hidden');
    })
})