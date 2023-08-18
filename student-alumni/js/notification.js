$(document).ready(function () {

    let offset = 0;
    let templength = 0;
    //retrieving notification
    function getNotification() {
        let action = {
            action: 'readNotif',
        }

        let formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('offset', offset);
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
                if (response.result == 'Success') {
                    let length = response.notifID.length; //total length of the data retrieved

                    //store data that has been process
                    for (let i = 0; i < length; i++) {
                        const notifID = response.notifID[i];
                        const postID = response.postID[i];
                        const added_by = response.added_by[i];
                        const typeOfNotif = response.typeOfNotif[i];
                        const date_notification = response.date_notification[i];
                        const is_read = response.is_read[i];
                        const profile = response.profile[i];
                        let content = "";

                        if (typeOfNotif == "comment") content = "Commented on your post"
                        else if (typeOfNotif == "like") content = "Liked on your post"
                        else if (typeOfNotif == "added post") content = "added a post"

                        displayNotification(profile, added_by, content, date_notification, is_read)
                    }

                    //increase the offset based on length so it can produce new sets of notification
                    offset += length
                    templength = length
                }
                else {
                    templength = 0;
                    $('#noNotifMsg').removeClass('hidden')
                    $('#noNotifMsg').appendTo('.notification-content')
                }
            },
            error: (error) => { console.log(error) }
        })
    }

    const imgFormat = 'data:image/jpeg;base64,';
    function displayNotification(profile, added_by, content, date_notification, is_read) {
        const notifContainer = $('<div>').addClass('notifContainer flex items-center gap-3 border-b border-gray-300 p-2 bg-blue-200 rounded-md my-1')
        if (is_read == '1') notifContainer.removeClass("bg-blue-200")//check if the notification already read

        //image of the user
        const defaultProfile = "../assets/icons/person.png"
        const dbProfile = imgFormat + profile
        const src = (profile != '') ? dbProfile : defaultProfile
        const imgProfile = $('<img>')
            .addClass('h-12 w-12 rounded-full border border-accent')
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

    //display the notification tab
    $('#notif-btn').on('click', function () {
        const notificationTab = $('#notification-tab')
        getNotification()
        notificationTab.toggle()
    })

    //get new sets of notification
    $('.notification-content').on('scroll', function () {
        if (templength != 0) {
            getNotification()
        }
    })

    function getUnreadNotification() {

        let action = {
            action: 'readNotif',
        }

        let formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('offset', offset);
        //process the notification
        $.ajax({
            method: 'POST',
            url: '../PHP_process/notificationData.php',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: (response) => {
                if (response.result == 'Success') {
                    let length = response.notifID.length; //total length of the data retrieved

                    //store data that has been process
                    for (let i = 0; i < length; i++) {
                        const notifID = response.notifID[i];
                        const added_by = response.added_by[i];
                        const typeOfNotif = response.typeOfNotif[i];
                        const date_notification = response.date_notification[i];
                        const is_read = response.is_read[i];
                        const profile = response.profile[i];
                        let content = "";

                        if (typeOfNotif == "comment") content = "Commented on your post"
                        else if (typeOfNotif == "like") content = "Liked on your post"
                        else if (typeOfNotif == "added post") content = "added a post"

                        if (is_read == 0)
                            displayNotification(profile, added_by, content, date_notification, is_read)
                    }

                    //increase the offset based on length so it can produce new sets of notification
                    offset += length
                    templength = length
                }
                else {
                    templength = 0;
                    $('#noNotifMsg').removeClass('hidden')
                    $('#noNotifMsg').appendTo('.notification-content') // to be placed at the very bottom
                }
            },
            error: (error) => { console.log(error) }
        })

    }

    //show only unread notification
    $('#btnNotifUnread').on('click', function () {
        $(this).addClass('bg-accent text-white') //highlight the button unread
        $('#btnNotifAll').removeClass('bg-accent text-white') //remove highlighted button all 

        //remove the previously displayed list
        $('.notifContainer').each(function () {
            $(this).remove()
        })
        $('#noNotifMsg').addClass('hidden')

        //restart to 0 to make a fresh notification for switching tabs
        offset = 0
        templength = 0
        getUnreadNotification() //display all the notification
    })

    //show all notification
    $('#btnNotifAll').on('click', function () {
        $(this).addClass('bg-accent text-white') //highlight the button unread
        $('#btnNotifUnread').removeClass('bg-accent text-white') //remove highlighted button all 

        //remove the current display so that the unread data will be remove
        $('.notifContainer').each(function () {
            $(this).remove()
        })
        $('#noNotifMsg').addClass('hidden')

        //restart to 0 to make a fresh notification for switching tabs
        offset = 0
        templength = 0
        getNotification() //display all the notification
    })
})