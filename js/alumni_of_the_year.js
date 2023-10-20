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

                    displaySelectedAOM(cover_img, quotation, fullname)
                }
            }
        })
    })

    function displaySelectedAOM(cover_img, quotation, fullname) {
        $('#aomCover').attr('src', cover_img);
        $('.aomFullname').text(fullname);
        $('#aomQuotation').text(quotation);
    }

})