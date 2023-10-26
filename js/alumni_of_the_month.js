$(document).ready(function () {
    const imgFormat = "data:image/jpeg;base64,"
    // show the alumni of the month
    $('#aomLi').on('click', function () {
        table.clear().draw(); //remove the previous display
        $('#aomMonth option:not(:first-child)').remove() //avoid duplication of option
        $('#aomYr option:not(:first-child)').remove()
        addMonths()
        addYearOption()
        retrieveAlumniOfMonth()
    })

    function retrieveAlumniOfMonth() {
        const action = "thisMonthAOM";
        const data = new FormData();
        data.append('action', action);

        $.ajax({
            url: '../PHP_process/alumniofMonth.php',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    let length = response.profile.length; //lengt of the data
                    for (let i = 0; i < length; i++) {
                        let profile = (response.profile[i] != "") ? imgFormat + response.profile[i] : '../assets/icons/person.png';
                        let personalEmail = response.personalEmail[i];
                        let colCode = response.colCode[i];
                        let studentNo = response.studentNo[i];
                        let fullname = response.fullname[i];
                        displayOnTable(profile, personalEmail, studentNo, colCode, fullname)
                    }

                    // retrieve more
                    if (length === 10) retrieveAlumniOfMonth()
                }
            },
            error: error => { console.log(error) }
        })
    }

    // Initialize the DataTable with options
    let table = $('#aomTable').DataTable({
        "paging": false,
        "ordering": false,
        "info": false,
        "lengthChange": false,
        "searching": false,
        "pageLength": 12
    });
    $('#aomTable').removeClass('dataTable').addClass('rounded-lg')
    function displayOnTable(profile, personalEmail, studentNo, colCode, fullname) {
        //add the data to the table
        let row = [
            `<img src="${profile}" alt="Profile Image" class="w-12 h-12 mx-auto rounded-full object-cover" />`,
            fullname,
            personalEmail,
            studentNo,
            colCode,
        ]

        // Add the new row to the DataTable
        table.row.add(row).draw(); // draw to refresh the table
    }

    // create option for select batch from today's year down to 1945
    function addMonths() {
        let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
            'August', 'September', 'October', 'November', 'December'];

        for (let i = 0; i < months.length; i++) {
            let option = $('<option>').val(i + 1).text(months[i])
            $('#aomMonth').append(option)
        }
    }

    // default value
    let monthVal = ''
    let colCodeVal = ''
    let yearVal = ''

    // data to be sent on the server
    let actionFilter = 'filterAOM'
    let filterForm = new FormData();
    filterForm.append('action', actionFilter)
    filterForm.append('month', monthVal)
    filterForm.append('colCode', colCodeVal)
    filterForm.append('year', yearVal)

    // filter based on the month selected
    $('#aomMonth').on('change', function () {
        monthVal = $(this).val();
        filterForm.set('month', monthVal)
        filterForm.set('colCode', colCodeVal)
        filterForm.set('year', yearVal)
        table.clear().draw(); //remove the previous display
        filterAlumni(filterForm)

    })

    $('#aomCollege').on('change', function () {
        colCodeVal = $(this).val()
        filterForm.set('month', monthVal)
        filterForm.set('colCode', colCodeVal)
        filterForm.set('year', yearVal)
        table.clear().draw(); //remove the previous display
        filterAlumni(filterForm)
    })

    $('#aomYr').on('change', function () {
        yearVal = $(this).val();
        filterForm.set('month', monthVal)
        filterForm.set('colCode', colCodeVal)
        filterForm.set('year', yearVal)
        table.clear().draw(); //remove the previous display
        filterAlumni(filterForm)
    })


    function filterAlumni(data) {

        $.ajax({
            url: '../PHP_process/alumniofMonth.php',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    let length = response.profile.length; //lengt of the data
                    for (let i = 0; i < length; i++) {
                        let profile = (response.profile[i] != "") ? imgFormat + response.profile[i] : '../assets/icons/person.png';
                        let personalEmail = response.personalEmail[i];
                        let colCode = response.colCode[i];
                        let studentNo = response.studentNo[i];
                        let fullname = response.fullname[i];
                        displayOnTable(profile, personalEmail, studentNo, colCode, fullname)
                    }
                    // retrieve more
                    if (length === 10) filterAlumni()

                }
                else table.clear().draw(); //remove the previous display
            },
            error: error => { console.log(error.responseText) }
        })
    }

    const currentDate = new Date()
    const currentYr = currentDate.getFullYear();
    const lastYr = 1904
    function addYearOption() {
        for (let i = currentYr; i >= lastYr; i--) {
            const option = $('<option>').val(i).text(i)
            $('#aomYr').append(option)
        }

    }
})