$(document).ready(function () {
    const fileUploader = $('#excelFileUploader')
    const UPLOADER_TEXT = "Click ( or tap) here to select a file"

    $('#studnoLi').on('click', function () {
        restartValue()
        retrieveStudentRecord()
    })

    function restartValue() {
        // restart value
        $('#loadingScreen').addClass('hidden')
        table.clear().draw();
        offset = 0;
        fileUploader.val();
        $('.uploader-text').text(UPLOADER_TEXT)
    }
    $('#uploadExcelFile').on('click', function () {
        fileUploader.click(); // open the file input
    });

    fileUploader.on('change', function () {
        const fileName = $(this).val().split('\\').pop(); // Extract file name
        $('.uploader-text').text(fileName)
        $('.upload-file-btn').removeClass('hidden')
    })


    $('.upload-file-btn').on('click', function () {
        const fileUploader = document.getElementById('excelFileUploader');
        const file = fileUploader.files[0];

        $('#loadingScreen').removeClass('hidden') //open loading screen
        if (file) {
            const formData = new FormData();
            formData.append('file', file);
            // send file to php
            fetch('../PHP_process/studentNoValidator.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    restartValue()
                    // open successful uploading
                    if (data.result === 'Success') {
                        $('.success-msg-upload').removeClass('hidden')
                        setTimeout(() => {
                            $('.success-msg-upload').addClass('hidden')
                        }, 5000)
                    }
                })
                .catch(error => {
                    restartValue()
                    console.error(error);
                })
        }
    })


    // set up table as Datatable
    const table = $('#studentRecordTB').DataTable({
        "paging": true,
        "ordering": false,
        "info": false,
        "lengthChange": false,
        "searching": true,
        "pageLength": 10
    });

    $('#studentRecordTB').removeClass('dataTable').css('width', '').addClass('rounded-lg center-shadow')

    let offset = 0;
    function retrieveStudentRecord() {
        const formData = new FormData();
        formData.append('offset', offset);

        // get all the date from the database record
        fetch('../PHP_process/studentNoValidator.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.response === 'Success') {
                    const length = data.studentNo.length;

                    // iterate the content
                    for (let i = 0; i < length; i++) {
                        const studentNo = data.studentNo[i];
                        const fullname = data.fullname[i];
                        const batchYear = data.batchYear[i];
                        const status = data.status[i];
                        let addedTime = data.addedTime[i];
                        addedTime = formatDate(addedTime) //format date to easy to read date

                        const row = [studentNo, fullname, batchYear, status, addedTime];
                        table.row.add(row); //add to the table
                    }

                    table.draw(); //refresh datatable to see the newly added row
                    offset += length;

                    if (length === 10) retrieveStudentRecord() //retrieve more once it is not yet finish
                }
            })
            .catch(error => {
                console.log(error)
            })
    }

    function formatDate(date) {
        const parsedDate = new Date(date);

        const option = { year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = parsedDate.toLocaleDateString('en-US', option);
        return formattedDate;
    }
})