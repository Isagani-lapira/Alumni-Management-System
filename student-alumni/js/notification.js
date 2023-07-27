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

    getNotification()
    //retrieving notification
    function getNotification() {
        let action = {
            action: 'readNotif',
            retrievalDate: retrievalDate
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
                console.log(response)
                //retrieve the previous date
                if (response == "None" && countNone <= 30) {
                    retrievalDate = getPreviousDate(noOfDaySubtract)
                    getNotification();
                    countNone++
                    noOfDaySubtract++
                }
                else if (response != "None") {
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
        const notifContainer = $('<div>').addClass('flex items-center gap-3 border-b border-gray-300 py-2')

        //image of the user
        const src = imgFormat + profile;
        const imgProfile = $('<img>')
            .addClass('h-12 w-12 rounded-full')
            .attr('src', src);

        //description content
        const descriptContainer = $('<div>')
        const accName = $('<p>')
            .addClass('font-semibold text-sm')
            .text(added_by)
        const contentElement = $('<p>')
            .addClass('text-sm')
            .text(content)

        //put in place the content
        descriptContainer.append(accName, contentElement);
        notifContainer.append(imgProfile, descriptContainer)
        $('.notification-content').append(notifContainer)
    }
})