$(document).ready(function () {
    function getCurrentDate() {
        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var day = String(today.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    //for retrieving recent date
    function getPreviousDate(daysToSubtract) {
        var today = new Date();
        today.setDate(today.getDate() - daysToSubtract);
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var day = String(today.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }
    var retrievalDate = getCurrentDate(); //to be change getCurrentDate()
    var noOfDaySubtract = 1;
    var countNone = 1;
    var maxRetrieve = 10;
    let dataRetrieved = 0;

    //retrieving notification
    function getNotification() {
        let action = {
            action: 'readNotif',
            retrievalDate: retrievalDate,
            maxRetrieve: maxRetrieve
        }

        let formData = new FormData();
        formData.append('action', JSON.stringify(action));

        //process the notification
        $.ajax({
            method: 'POST',
            url: '../PHP_process/notificationData.php',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: (response) => {
                //retrieve the previous date
                if (response.result == "Nothing" && countNone <= 10 && maxRetrieve != 0) {
                    console.log(retrievalDate)
                    retrievalDate = getPreviousDate(noOfDaySubtract);
                    getNotification()
                    noOfDaySubtract++ //if no more the day will be increasing to get the previous date
                    countNone++
                }
                else if (response != "Nothing") {

                    if (response.result == 'Success') {
                        let length = response.notifID.length; //total length of the data retrieved

                        //store data that has been process
                        for (let i = 0; i < length; i++) {
                            const notifID = response.notifID[i];
                            const added_by = response.added_by[i];
                            const typeOfNotif = response.typeOfNotif[i];
                            const content = response.content[i];
                            const date_notification = response.date_notification[i];
                            const timestamp = response.timestamp[i];
                            const is_read = response.is_read[i];
                            const profile = response.profile[i];

                            displayNotification(profile, added_by, content, date_notification, is_read)
                        }

                        dataRetrieved = length; // get how many data has been retrieve for that day
                        maxRetrieve = maxRetrieve - dataRetrieved;
                        if (maxRetrieve != 0) {
                            retrievalDate = getPreviousDate(noOfDaySubtract);
                            noOfDaySubtract++
                            countNone = 0;
                            getNotification()
                        } else maxRetrieve = 10;
                    }
                }
                else {
                    let noNotification = $('<p>').addClass('text-center').text('No available notification')
                    $('.notification-content').append(noNotification)
                }
            },
            error: (error) => { console.log(error) }
        })
    }

    const imgFormat = 'data:image/jpeg;base64,';
    function displayNotification(profile, added_by, content, date_notification, is_read) {
        const notifContainer = $('<div>').addClass('flex items-center gap-3 border-b border-gray-300 p-2 bg-blue-200 rounded-md my-1')

        if (is_read == '1') notifContainer.removeClass("bg-blue-200")//check if the notification already read

        //image of the user
        const src = imgFormat + profile;
        const imgProfile = $('<img>')
            .addClass('h-12 w-12 rounded-full')
            .attr('src', src);

        //description content
        const descriptContainer = $('<div>')
        const accName = $('<p>')
            .addClass('font-semibold text-sm text-greyish_black')
            .text(added_by)
        const contentElement = $('<p>')
            .addClass('text-sm')
            .text(content)
        const dateElement = $('<p>')
            .addClass('text-xs items-end mt-auto flex-1 text-end text-gray-400')
            .text(date_notification)

        //put in place the content
        descriptContainer.append(accName, contentElement);
        notifContainer.append(imgProfile, descriptContainer, dateElement)
        $('.notification-content').append(notifContainer)
    }

    $('#notif-btn').on('click', function () {
        const notificationTab = $('#notification-tab')
        getNotification()
        notificationTab.toggle()
    })

    $('.notification-content').on('scroll', function () {
        getNotification()
    })
})