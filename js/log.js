
$(document).ready(function () {

    const PWD = window.location.href;
    const splitPath = PWD.split('admin');
    const rootPath = splitPath[0];
    const COL_LOGO = rootPath + "media/search.php?media=college&colCode=";

    const imgFormat = 'data:image/jpeg;base64,'
    let offset = 0;
    const activityContainer = $('#recentActWrapper')
    const logListContainer = $('#logList')
    displayActivities(offset, true, activityContainer)

    let dataArray = []
    function displayActivities(offset, isDashDisplay, container) {
        const action = "RetrieveData";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('offset', offset)


        $.ajax({
            url: '../PHP_process/log.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    let length = response.action.length
                    dataArray = []
                    $('.lds-roller').addClass('hidden')
                    if (isDashDisplay) {
                        //display only maximum of 3 activities for dashboard
                        if (length > 3) length = 3
                    }

                    for (let i = 0; i < length; i++) {
                        const timestamp = response.timestamp[i];
                        const details = response.details[i];
                        const colCode = response.colCode[i];
                        const colAdminName = response.colAdminName[i];
                        const formattedDate = convertTimestamp(timestamp) //format the date

                        const colLogo = COL_LOGO + colCode;
                        // in case the user will print the list
                        const logList = {
                            timestamp: formattedDate,
                            details: details,
                            colCode: colCode,
                            colAdmin: colAdminName,
                        }
                        if (isDashDisplay)
                            createActivities(formattedDate, details, colCode, colLogo, colAdminName, container)
                        else {
                            dataArray.push(logList)
                            createActivities(formattedDate, details, colCode, colLogo, colAdminName, logListContainer, false)
                        }
                    }
                }
            },
            error: error => { console.log(error) }
        })
    }

    function createActivities(timestamp, details, colCode, colLogo, colAdminName, container, isDashDisplay = true) {

        const actionWrapper = $('<div>')
            .addClass('flex justify-stretch actionWrapper items-center')

        const imgCollegeLogo = $('<img>')
            .addClass('circle rounded-full bg-gray-400  h-10 w-10')
            .attr('src', colLogo)

        const content = $('<div>')
            .addClass('text-sm ms-2 font-extralight')

        const detailsWrapper = $('<div>')
            .addClass('flex gap-2 items-center')
        const college = $('<span>')
            .addClass('text-gray-700 font-bold text-lg')
            .text(colCode)
        const detailsMsg = $('<p>')
            .addClass('text-gray-500')
            .text(details)
        detailsWrapper.append(college, detailsMsg)

        const adminWrapper = $('<span>')
            .addClass('text-accent font-bold')
            .text('Administrator');
        const adminName = $('<span>')
            .addClass('text-gray-500 text-xs')
            .append(colAdminName, '  ', adminWrapper);


        const time = $('<span>')
            .addClass('text-gray-500 text-xs')
            .text(timestamp)
        content.append(detailsWrapper, time)

        actionWrapper.append(imgCollegeLogo, content);
        if (!isDashDisplay) {
            content.append(detailsWrapper, adminName)
            actionWrapper.append(time)
            content.addClass('flex-1')
        }

        container.append(actionWrapper)

    }

    function convertTimestamp(timestampStr) {
        const timestamp = new Date(timestampStr);

        // Define the date format options
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };

        // Format the date as a string
        const formattedDate = timestamp.toLocaleString(undefined, options);

        return formattedDate;

    }

    const defaultStart = thismonth + "/" + thisday + "/" + thisyear;
    const defaultEnd = thismonth + 1 + "/" + thisday + "/" + thisyear;

    $('#btnViewMoreLog').on('click', function () {
        $('#logHistoryModal').removeClass('hidden')
        $('#logList').find('.actionWrapper').remove() //remove the previously retrieve logs (not duplicate)
        restartData()
        // display the history log today
        displayActivities(offset, false, logListContainer)

    })
    let college = "";
    let startDate = "";
    let endDate = "";
    let weekVal = "";

    function restartData() {
        // restart data again
        college = "";
        startDate = "";
        endDate = "";
        weekVal = "";

        // make the filter to default
        $('#logCollege').val(null)
        $('#weekFilter').val(1)
        // date picker to default today's date
        let daterangepickerElement = $('input[name="logdaterange"]');
        daterangepickerElement.data('daterangepicker').setStartDate(defaultStart);
        daterangepickerElement.data('daterangepicker').setEndDate(defaultEnd);
    }
    // filter logs by date range
    $(function () {
        $('input[name="logdaterange"]').daterangepicker(
            {
                opens: "left",
                startDate: defaultStart,
                endDate: defaultEnd,
            },
            function (start, end, label) {
                weekVal = ""
                startDate = start.format('YYYY-MM-DD')
                endDate = end.format('YYYY-MM-DD')
                $('.lds-roller').removeClass('hidden') // hide the loading
                getFilteredDateAction(startDate, endDate, college, weekVal)
            }
        );
    });

    $('#logCollege').on('change', function () {
        college = $(this).val();
        if (college != "")
            getFilteredDateAction(startDate, endDate, college, weekVal)
        else {
            offset = 0;
            $('#logHistoryModal').removeClass('hidden')
            $('#logList').find('.actionWrapper').remove() //remove the previously retrieve logs (not duplicate)
            // display the history log today
            displayActivities(offset, false, logListContainer)

        }
    })

    // filter base on week
    $('#weekFilter').on('change', function () {
        weekVal = $(this).val();

        // make the date filter in default state
        startDate = "";
        endDate = "";
        let daterangepickerElement = $('input[name="logdaterange"]');
        daterangepickerElement.data('daterangepicker').setStartDate(defaultStart);
        daterangepickerElement.data('daterangepicker').setEndDate(defaultEnd);

        getFilteredDateAction(startDate, endDate, college, weekVal)

    })

    // get the filtered log data
    function getFilteredDateAction(startDate, endDate, college, weekVal) {
        const action = "retrieveByDate"
        const formdata = new FormData();
        formdata.append('action', action)
        formdata.append('startDate', startDate)
        formdata.append('endDate', endDate)
        formdata.append('colCode', college)
        formdata.append('week', weekVal)

        $.ajax({
            url: '../PHP_process/log.php',
            method: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                $('#logList').find('.actionWrapper').remove()
                if (response.response == "Success") {
                    const length = response.action.length;
                    dataArray = []
                    for (let i = 0; i < length; i++) {
                        const timestamp = response.timestamp[i];
                        const details = response.details[i];
                        const colCode = response.colCode[i];
                        const colAdminName = response.colAdminName[i];
                        const colLogo = COL_LOGO + colCode;
                        const formattedDate = convertTimestamp(timestamp) //format the date

                        // in case the user will print the list
                        const logList = {
                            timestamp: formattedDate,
                            details: details,
                            colCode: colCode,
                            colAdmin: colAdminName,
                        }
                        dataArray.push(logList)
                        createActivities(formattedDate, details, colCode, colLogo, colAdminName, logListContainer, false)
                    }
                }
                $('.lds-roller').addClass('hidden') // hide the loading
            }
        })
    }


    // close the modal when clicked outside the modal
    $('#logHistoryModal').on('click', function (e) {
        const target = e.target
        const modal = $("#modalLogContainer")

        if (!modal.has(target).length && !modal.is(target))
            $('#logHistoryModal').addClass('hidden')
    })


    $('#printLogsBtn').on('click', function () {
        const jsonString = JSON.stringify(dataArray)
        window.open('logtemplate.html?data=' + encodeURIComponent(jsonString), '_blank')
    })
});