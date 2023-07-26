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
            url: '../PHP_process/notificationData.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                //retrieve the previous date
                if (response == "none" && countNone <= 30) {
                    retrievalDate = getPreviousDate(noOfDaySubtract)
                    getNotification();
                    countNone++
                    noOfDaySubtract++
                }
                else {
                    //retrieve the data for a day
                }
            },
            error: (error) => { console.log(error) }
        })
    }
})