
$(document).ready(function () {

    const imgFormat = 'data:image/jpeg;base64,'
    let offset = 0;
    const activityContainer = $('#recentActWrapper')
    displayActivities(offset, true, activityContainer)


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
                    //display only 3 activities for dashboard
                    if (isDashDisplay) {
                        for (let i = 0; i < 3; i++) {
                            const action = response.action[i];
                            const timestamp = response.timestamp[i];
                            const details = response.details[i];
                            const colCode = response.colCode[i];
                            const colLogo = imgFormat + response.colLogo[i]; //formatted image

                            const formattedDate = convertTimestamp(timestamp) //format the date
                            createActivities(action, formattedDate, details, colCode, colLogo, container)
                        }

                    }
                }
            },
            error: error => { console.log(error) }
        })
    }

    function createActivities(action, timestamp, details, colCode, colLogo, container) {

        const actionWrapper = $('<div>')
            .addClass('flex justify-stretch')

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

        const time = $('<span>')
            .addClass('text-grayish text-xs')
            .text(timestamp)

        content.append(detailsWrapper, time)
        actionWrapper.append(imgCollegeLogo, content);
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
});