$(document).ready(function () {

    // cancel the cancelling migration
    $('#cancelMigrationBtn').on('click', function () {
        $('.cancelMigrationModal').addClass('hidden')
    })

    // close the modal migration
    $('#closeMigrationModal').on('click', function () {
        $('.cancelMigrationModal').addClass('hidden')
        $('.migrationModal').addClass('hidden')
    })

    // open cancelling button
    $('.cancelMigration').on('click', function () {
        $('.cancelMigrationModal').removeClass('hidden')
    })

    $('.migrateConfirmBtn').on('click', function () {
        $('.additionalInfo').removeClass('hidden')
        $('.migrationModal').addClass('hidden')
        $('#batchYrData').empty()
        addBatchYear()
    })
    addBatchYear()
    //current year
    function addBatchYear() {
        const date = new Date();
        const currentYr = date.getFullYear();
        const startYear = 1904;

        for (let i = currentYr; i > startYear; i--) {
            //add option to the batch year
            let option = $('<option>').val(i).text(i);
            $('#batchYrData').append(option)
        }
    }

    $('.cancelAdditionalInfo').on('click', function () {
        $('.additionalInfo').addClass('hidden')
        $('.migrationModal').removeClass('hidden')
    })

    $('#migrationForm').on('submit', function (e) {
        e.preventDefault()//not submitting
        const data = $(this)[0];
        const action = { action: 'migrateStudent' };
        const formData = new FormData(data);
        formData.append('action', JSON.stringify(action));

        // process migration
        $.ajax({
            url: '../PHP_process/studentData.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                if (response === 'Success') {
                    $('.successMigrationModal').removeClass('hidden')
                    $('.additionalInfo').addClass('hidden')

                    // make automatic sign out after 6seconds
                    setTimeout(function () {
                        $('.successMigrationModal').addClass('hidden')
                        $('#logout').click()
                    }, 6000);
                }
            }
        })
    })

})