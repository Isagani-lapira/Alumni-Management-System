$(document).ready(function () {
    let offset = 0;

    let batchFilter = "";
    let collegeFilter = "";
    let empStatusFilter = ""
    $('#alumniLi').on('click', function () {
        offset = 0
        getAlumniRecord(alumniDataDefault)
        $('#batchAlumRecord option:not(:first-child)').remove()
        addBatchOption()
    })

    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const startYear = 1904;
    // create option for select batch from today's year down to 1945
    function addBatchOption() {
        const option = $('<option>').val('').text('All');
        $('#batchAlumRecord').append(option)
        for (let i = currentYear; i > startYear; i--) {
            const option = $('<option>').val(i).text(i);
            $('#batchAlumRecord').append(option)
        }
    }
    let actionAlumni = {
        action: "readAll",
    };

    let alumniDataDefault = new FormData();
    alumniDataDefault.append("action", JSON.stringify(actionAlumni));
    alumniDataDefault.append('offset', offset)

    // retrieve alumni record
    function getAlumniRecord(alumniDataDefault) {
        $.ajax({
            url: "../PHP_process/alumniData.php",
            method: "POST",
            data: alumniDataDefault,
            processData: false,
            contentType: false,
            success: (response) => {
                const parsedResponse = JSON.parse(response);
                //display the data
                let dataLength = parsedResponse.studentNo.length;
                for (let i = 0; i < dataLength; i++) {
                    //retrieve data from json
                    let studNo = parsedResponse.studentNo[i];
                    let fullname = parsedResponse.fullname[i];
                    let colCode = parsedResponse.colCode[i];
                    let batchYr = parsedResponse.batchYr[i];
                    let employmentStatus = parsedResponse.employmentStat[i];

                    let row = [studNo, fullname, colCode, batchYr, employmentStatus];
                    table.row.add(row);
                }

                table.draw();
                offset += dataLength
                // get another batch of record of alumni
                if (dataLength == 10) {
                    alumniDataDefault.set('offset', offset)
                    getAlumniRecord(alumniDataDefault);
                }

            }
        });
    }

    // initialize table to be DataTable
    let table = $("#alumRecord").DataTable({
        "ordering": false,
        "info": false,
        "lengthChange": false,
        "pageLength": 10
    })

    $("#alumRecord").removeClass('dataTable').addClass('rounded-lg')

    // filtering data
    $('#employmentStat').on('change', function () {
        empStatusFilter = $(this).val();
        filteringRecord()
    })

    $('#batchAlumRecord').on('change', function () {
        batchFilter = $(this).val();
        filteringRecord()
    })
    $('#alumniCollege').on('change', function () {
        collegeFilter = $(this).val();
        filteringRecord()
    })

    function filteringRecord() {
        // default view
        table.column(2).search('').column(3).search('').column(4).search('').draw();
        if (collegeFilter === "" && batchFilter === "" && empStatusFilter !== "") {
            // filtering employment status only
            empStatusFilter = "^" + empStatusFilter + "$"
            table.column(4).search(empStatusFilter, true, false).draw();
        } else if (collegeFilter !== "" && batchFilter === "" && empStatusFilter === "") {
            // filtering college only
            table.column(2).search(collegeFilter).draw()
        } else if (collegeFilter === "" && batchFilter !== "" && empStatusFilter === "") {
            // filtering batch only
            table.column(3).search(batchFilter).draw()
        } else if (collegeFilter !== "" && batchFilter !== "" && empStatusFilter === "") {
            // filtering batch and college
            table.column(3).search(batchFilter) //batch
            table.column(2).search(collegeFilter) //college
            table.draw();
        } else if (collegeFilter === "" && batchFilter !== "" && empStatusFilter !== "") {
            // filtering batch and employment status
            empStatusFilter = "^" + empStatusFilter + "$"
            table.column(3).search(batchFilter) //batch
            table.column(4).search(empStatusFilter, true, false)// employment status
            table.draw();
        } else if (collegeFilter !== "" && batchFilter === "" && empStatusFilter !== "") {
            // filtering college and employment status
            empStatusFilter = "^" + empStatusFilter + "$"
            table.column(2).search(collegeFilter) //college
            table.column(4).search(empStatusFilter, true, false)// employment status
            table.draw();
        }
    }


})