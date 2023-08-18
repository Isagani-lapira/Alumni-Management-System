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

                        displayNotification(profile, added_by, content, date_notification, is_read, postID)
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
    function displayNotification(profile, added_by, content, date_notification, is_read, postID) {
        const notifContainer = $('<div>').addClass('notifContainer flex items-center ' +
            'gap-3 border-b border-gray-300 p-2 bg-blue-200 rounded-md my-1 cursor-pointer')
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
            .on('click', function () {
                //show the modal
                $('#viewingPost').removeClass('hidden')
                //get primary details of user
                const name = $('#accFN').html();
                const accUN = $('#accUsername').html();

                //get the details of post
                getPostDetails(postID, name, accUN, src);

            })
        $('.notification-content').append(notifContainer)
    }


    function getPostDetails(postID, name, accUN, imgProfile) {
        let action = {
            action: 'readWithPostID'
        }

        //data to be send in database
        const data = new FormData();
        data.append('action', JSON.stringify(action))
        data.append('postID', postID);

        //process retrieval of post
        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                //data to be receive

                const description = response.caption[0];
                const images = response.images[0];
                const likes = response.likes[0];

                //zoom in the post or viewable in bigger size
                viewingOfPost(postID, name, accUN, description, images, likes, imgProfile)
            },
            error: error => { console.log(error) }
        })
    }
    //display the notification tab
    $('#notif-btn').on('click', function () {
        //restart first
        offset = 0;
        templength = 0;

        $('.notifContainer').remove();
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


    function viewingOfPost(postID, name, accUN, description, images, likes, imgProfile) {
        $('#profilePic').attr('src', imgProfile);
        $('#postFullName').text(name);
        $('#postUN').text(accUN);
        $('#noOfLikes').text(likes);
        $('#postDescript').text(description).addClass('text-sm my-2 text-gray-400');

        const carouselWrapper = $('#carousel-wrapper');
        const carouselIndicators = $('#carousel-indicators');

        let totalImgNo = images.length;

        //remove the navigation button when it is only 1
        if (totalImgNo === 1) {
            $('#btnPrev, #btnNext').addClass('hidden');
        } else {
            $('#btnPrev, #btnNext').removeClass('hidden');
        }

        //add image/s to the carousel
        images.forEach((image, index) => {
            let imageName = 'item-' + index;
            const item = $('<div>')
                .addClass('relative duration-700 ease-in-out h-full')
                .attr('data-carousel-item', '')
                .attr('id', imageName);

            const format = imgFormat + image;
            const img = $('<img>')
                .addClass('absolute inset-0 left-0 right-0 top-0 bottom-0 m-auto object-contain')
                .attr('src', format)
                .attr('alt', 'Carousel Image');

            if (index === 0) {
                item.removeClass('hidden'); // Show the first image
            } else {
                item.addClass('hidden'); // Hide the rest of the images
            }

            item.append(img);
            carouselWrapper.append(item);

            const indicator = $('<button>')
                .attr('type', 'button')
                .addClass('w-3 h-3 rounded-full')
                .attr('aria-current', index === 0 ? 'true' : 'false')
                .attr('aria-label', 'Slide ' + (index + 1))
                .attr('data-carousel-slide-to', index.toString());

            carouselIndicators.append(indicator);
        });

        //controller how to next image
        let currentImageDisplay = 0;
        $('#btnNext').on('click', function () {
            $('#item-' + currentImageDisplay).addClass('hidden'); // Hide the current image
            currentImageDisplay = (currentImageDisplay + 1) % totalImgNo; // Move to the next image
            $('#item-' + currentImageDisplay).removeClass('hidden'); // Show the next image
        });

        //controller how to previous image
        $('#btnPrev').on('click', function () {
            $('#item-' + currentImageDisplay).addClass('hidden'); // Hide the current image
            currentImageDisplay = (currentImageDisplay - 1 + totalImgNo) % totalImgNo; // Move to the previous image
            $('#item-' + currentImageDisplay).removeClass('hidden'); // Show the previous image
        });

        getComment(postID); //retrieve the comment if available

        //display all the person who likes a specific post
        $('#noOfLikes').hover(
            function () {
                //show the name of the one who likes the post
                getLikes(postID)
                $('#namesOfUser').show()
            },
            function () {
                $('#namesOfUser').hide().empty() //remove so that it the first one that added will not duplicate
            }
        )
    }


    //retrieving the comments
    function getComment(postID) {
        const action = {
            action: 'read',
            postID: postID
        }

        let formData = new FormData();
        formData.append('action', JSON.stringify(action));

        $.ajax({
            url: '../PHP_process/commentData.php',
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                const parsedResponse = JSON.parse(response);
                $('#commentContainer').empty(); //remove the comment of the firstly view
                if (parsedResponse.result == 'Success') {
                    const length = parsedResponse.commentID.length;
                    $('#noOfComment').text(length) //set the number of comments
                    $('#commentMsg').addClass('hidden')
                    //display every comments
                    for (let i = 0; i < length; i++) {
                        const commentID = parsedResponse.commentID[i];
                        const fullname = parsedResponse.fullname[i];
                        const comment = parsedResponse.comment[i];
                        const img = imgFormat + parsedResponse.profile[i];

                        let commentContainer = $('<div>').addClass("flex gap-2 my-2")
                        let imgProfile = $('<img>').addClass("h-8 w-8 rounded-full").attr('src', img);
                        let commentDescript = $('<div>').addClass("bg-gray-300 rounded-md p-3 flex-grow text-sm flex flex-col gap-1 text-greyish_black");
                        let commentor = $('<p>').text(fullname)
                        let postComment = $('<p>').text(comment).addClass('text-xs text-gray-500');

                        commentDescript.append(commentor, postComment);
                        commentContainer.append(imgProfile, commentDescript)

                        $('#commentContainer').append(commentContainer);
                    }
                } else {
                    let noCommentMsg = $('<p>').addClass('text-gray-500 text-center').text('No available comment')
                    $('#commentContainer').append(noCommentMsg) //show no comment
                }
            },
            error: (error) => { console.log(error) }
        })
    }

    function getLikes(postID) {
        let action = {
            action: 'readLikes',
            postID: postID,
        }

        let formData = new FormData();
        formData.append('action', JSON.stringify(action))

        //process the data
        $.ajax({
            url: '../PHP_process/likesData.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: (response) => {
                const parsedResponse = JSON.parse(response);
                if (parsedResponse.result == 'Success') {
                    let length = parsedResponse.fullname.length;
                    //retrieve all the names of the people who likes a post
                    for (let i = 0; i < length; i++) {
                        let fullname = parsedResponse.fullname
                        let p = $('<p>').text(fullname);
                        $('#namesOfUser').append(p)
                    }
                }
            },
            error: (error) => { console.log(error) }
        })
    }
})