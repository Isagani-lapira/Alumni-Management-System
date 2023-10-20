$(document).on('ready', function () {

    $('#aoyLi').on('click', function () {
        $('#aomSelection').find('option:not([value=""])').remove()
        getAlumniOfTheMonth()
    })
    function getAlumniOfTheMonth() {
        let action = "thisYearAOM";
        const formData = new FormData();
        formData.append('action', action)
        $.ajax({
            url: '../PHP_process/alumniofMonth.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: response => {
                if (response.response === "Success") {
                    // option to be shown in the alumni of the month selection
                    let length = response.names.length
                    for (let i = 0; i < length; i++) {
                        const name = response.names[i];
                        const personID = response.personID[i];
                        const aomID = response.aomID[i];
                        const colCode = response.colCode[i];
                        let option = $('<option>').val(aomID).text(name + ' - ' + colCode).attr('data-aompersonid', personID);
                        $('#aomSelection').append(option)
                    }

                }

            }
        })
    }

    // display the selected alumni of the month
    $('#aomSelection').on('change', function () {
        const aomID = $(this).val()
        const action = "searchAlumniOfTheMonth";

        const formData = new FormData();
        formData.append('action', action);
        formData.append('aomID', aomID)

        // retrieve selected alumni of the month
        $.ajax({
            url: '../PHP_process/alumniofMonth.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response === 'Success') {
                    const data = response
                    const cover_img = imgFormat + data.cover;
                    const quotation = data.quote;
                    const fullname = data.fullname;

                    displaySelectedAOM(aomID, cover_img, quotation, fullname)
                }
            }
        })
    })

    function displaySelectedAOM(aomID, cover_img, quotation, fullname) {
        $('#aomCover').attr('src', cover_img);
        $('.aomFullname').text(fullname);
        $('#aomQuotation').text(quotation);
        getTestimonials(aomID)
            .then(response => {
                if (response.response === 'Success') {
                    let length = response.message.length
                    $('.subtitle').removeClass('hidden')
                    // display all the testimonies
                    for (let i = 0; i < length; i++) {
                        const message = response.message[i]
                        const personName = response.personName[i]
                        const position = response.position[i]
                        const profile = imgFormat + response.profile[i]

                        // mark up for testimony
                        let wrapper = $('<div>').addClass('p-5 rounded-md center-shadow w-1/3 flex flex-col gap-3')
                        let messageElement = $('<p>').addClass('italic text-sm testimony flex-1 w-full').text(`" ${message} "`)
                        let personDetails = $('<div>').addClass('flex items-center gap-3')
                        let profileElement = $('<img>').attr('src', profile).addClass('w-8 h-8 rounded-full');
                        let information = $('<div>')
                        let nameElement = $('<p>').addClass('font-semibold text-sm').text(personName);
                        let positionElement = $('<p>').addClass('text-xs').text(position);

                        information.append(nameElement, positionElement)
                        personDetails.append(profileElement, information)
                        wrapper.append(messageElement, personDetails)
                        $('.testimonyContainer').append(wrapper) //root container
                    }
                }
            })
    }

    function getTestimonials(aomID) {
        const action = "getTestimonials";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('aomID', aomID);

        return new Promise((resolve) => {
            $.ajax({
                url: '../PHP_process/alumniofMonth.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: response => {
                    resolve(response)
                }
            })
        })

    }


})