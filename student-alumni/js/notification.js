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
                $('#loadingDataNotif').addClass('hidden')
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
                        const details = (response.details[i] == "") ? "" : response.details[i];

                        let content = "";
                        let isDeleted = false
                        if (typeOfNotif == "comment") content = "Commented on your post"
                        else if (typeOfNotif == "like") content = "Liked on your post"
                        else if (typeOfNotif == "added post") content = "added a post"
                        else if (typeOfNotif == "delete") {
                            content = "Admin deleted your post"
                            isDeleted = true
                        }
                        else if (typeOfNotif == 'aoy') content = "You have been assigned as alumni of the year"

                        displayNotification(profile, added_by, content, date_notification, is_read, postID, notifID, details, isDeleted)
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
            }

        })
    }

    const imgFormat = 'data:image/jpeg;base64,';
    function displayNotification(profile, added_by, content, date_notification, is_read, postID, notifID, details, isDeleted) {
        const notifContainer = $('<div>').addClass('notifContainer flex items-center ' +
            'gap-3 border-b border-gray-300 p-2 bg-blue-200 rounded-md my-1 cursor-pointer')
        if (is_read == '1') notifContainer.removeClass("bg-blue-200")//check if the notification already read

        //image of the user
        const defaultProfile = "../assets/icons/person.png"
        const dbProfile = imgFormat + profile
        const src = (profile !== '') ? dbProfile : defaultProfile
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
            .addClass('text-xs mt-auto flex-1 text-gray-400')
            .text(date_notification)

        //put in place the content
        descriptContainer.append(accName, contentElement, dateElement);
        notifContainer.append(imgProfile, descriptContainer)

        // showing normal view if the notification is not deleted
        if (!isDeleted) {
            notifContainer.on('click', function () {
                $('.loadingProfile').parent().removeClass('hidden')
                //get the details of post
                getPostDetails(postID, src, date_notification);

                //update the total number of unread notification
                updateStatusNotification(notifID);
            })
        }
        else {
            // post deleted by admin
            notifContainer.on('click', function () {
                $('#reportedPostModal').removeClass('hidden') // display the reported post modal
                $('#notif-btn').click() //hide the display again
                const timestamp = formattedTimestamp(date_notification) //formatted date
                // update the data of reported post
                $('#reportedTime').text(timestamp)
                getReportedPostDetails(postID) //post details
                $("#reasonForDel").text(details)

                //update the total number of unread notification
                updateStatusNotification(notifID);
            })
        }

        $('.notification-content').append(notifContainer)
    }

    function updateStatusNotification(notifID) {
        let action = {
            action: 'updateNotifStat'
        };
        const formdata = new FormData();
        formdata.append('action', JSON.stringify(action));
        formdata.append('notifID', notifID);

        //ajax update
        $.ajax({
            url: '../PHP_process/notificationData.php',
            method: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            success: response => {
                if (response == 'Success') badgeNotification()
            },
            error: error => { console.log(error) },
        })
    }

    function getPostDetails(postID, imgProfile, date_notification) {
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
                $('.loadingProfile').parent().addClass('hidden')
                //data to be receive
                const description = response.caption[0];
                const fullname = response.fullname[0];
                const username = response.username[0];
                const images = response.images[0];
                const likes = response.likes[0];
                $('#notification-tab').hide()
                //zoom in the post or viewable in bigger size
                if (images.length > 0)
                    viewingOfPost(postID, fullname, username, description, images, likes, imgProfile)
                else
                    viewStatusPost(postID, fullname, date_notification, description, likes, username)

            },
        })
    }

    function getReportedPostDetails(postID) {
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
                const timestamp = formattedTimestamp(response.timestamp);
                const caption = response.caption;

                $('#postDate').text(timestamp)
                $('#reportedPostCap').text(caption)
            }
        })
    }

    //display the notification tab
    $('#notif-btn').on('click', function () {
        //restart first
        offset = 0;
        templength = 0;

        $('.notifContainer').remove();
        const notificationTab = $('#notification-tab')
        $('#loadingDataNotif').removeClass('hidden')
        getNotification()
        notificationTab.toggle()
    })

    //get new sets of notification
    $('.notification-content').on('scroll', function () {
        if (templength != 0) {
            $('#loadingDataNotif').removeClass('hidden').appendTo('.notification-content')
            getNotification()
        }
    })

    function getUnreadNotification() {

        let action = {
            action: 'unreadNotif',
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
                    $('#loadingDataNotif').addClass('hidden')
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
                        const details = (response.details[i] == "") ? "" : response.details[i];
                        let content = "";
                        let isDeleted = false

                        if (typeOfNotif == "comment") content = "Commented on your post"
                        else if (typeOfNotif == "like") content = "Liked on your post"
                        else if (typeOfNotif == "added post") content = "added a post"
                        else if (typeOfNotif == "delete") {
                            content = "Admin deleted your post"
                            isDeleted = true
                        }


                        displayNotification(profile, added_by, content, date_notification, is_read, postID, notifID, details, isDeleted)

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
        $('#loadingDataNotif').removeClass('hidden')
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


    function badgeNotification() {
        let action = {
            action: 'readUnreadNotif'
        }
        let formatData = new FormData()
        formatData.append('action', JSON.stringify(action));

        $.ajax({
            url: '../PHP_process/notificationData.php',
            method: 'POST',
            data: formatData,
            processData: false,
            contentType: false,
            success: (success) => {
                //display a notification badge
                if (success != 'none') $('#notifBadge').removeClass('hidden').html(success);
                else $('#notifBadge').addClass('hidden')
            },
            error: (error) => { console.log(error) }
        })
    }
    function viewingOfPost(postID, name, accUN, description, images, likes, imgProfile) {
        buttonColor()
        $('#viewingPost').removeClass('hidden')  //show the modal
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
                        const fullname = parsedResponse.fullname[i];
                        const comment = parsedResponse.comment[i];
                        const commentID = parsedResponse.commentID[i];
                        const img = imgFormat + parsedResponse.profile[i];

                        let commentContainer = $('<div>').addClass("flex gap-2 my-2")
                        let imgProfile = $('<img>').addClass("h-8 w-8 rounded-full").attr('src', img);
                        let commentDescript = $('<div>').addClass("bg-gray-300 rounded-md p-3 flex-grow text-sm flex flex-col gap-1 text-greyish_black");
                        let commentor = $('<p>').text(fullname)
                        let delComment = $('<button>').addClass('text-xs hover:text-red-400').text('Delete')
                            .on('click', function () {
                                $('#delete-modal').removeClass('hidden')
                                // delete the comment
                                $('#deletePostbtn').on('click', function () {
                                    deleteComment(commentID, commentContainer);
                                    // update the count
                                    let newCountComment = parseInt($('#noOfComment').text()) - 1
                                    $('#noOfComment').text(newCountComment)
                                })
                            })

                        let container = $('<div>').addClass('flex justify-between').append(commentor, delComment)
                        let postComment = $('<p>').text(comment).addClass('text-xs text-gray-500');

                        commentDescript.append(container, postComment);
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

    function formattedTimestamp(dateTimeString) {
        const date = new Date(dateTimeString);
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };

        const formattedDate = date.toLocaleString('en-US', options);

        return formattedDate
    }

    // hide the report modal again
    $('#closeReportedPost').on('click', function () {
        $('#reportedPostModal').addClass('hidden')
    })

    $('#learnMoreBtn').on('click', function () {
        $('.communityGuideline').removeClass('hidden')
    })
    $('.closeGuidelines').on('click', function () {
        $('.communityGuideline').addClass('hidden')
    })

    function viewStatusPost(postID, name, postDate, postcaption, likes, accountUN) {
        buttonColor()
        $('#postStatusModal').removeClass('hidden')
        $('#statusFullnameUser').text(name)
        $('#statusDate').text(postDate)
        $('.accountUN').text(accountUN)
        $('#statusDescript').html(postcaption)
        $('#statusLikes').text(likes)

        const action = { action: 'readWithPostID' }
        const formData = new FormData();
        formData.append('action', JSON.stringify(action))
        formData.append('postID', postID)

        $.ajax({
            url: '../PHP_process/postDB.php',
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                const commentCount = response.comments
                const imgProfile = imgFormat + response.profilePic

                $('#statusComment').text(commentCount)
                $('#profileStatusImg').attr('src', imgProfile)

                if (commentCount > 0) displayComments(postID)

            }
        })
    }

    function displayComments(postID) {
        const action = { action: 'read', postID: postID };
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));

        $.ajax({
            url: '../PHP_process/commentData.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                $('#commentStatus').empty()
                let length = response.fullname.length;
                for (let i = 0; i < length; i++) {
                    let fullname = response.fullname[i];
                    let comment = response.comment[i];
                    let commentID = response.commentID[i];
                    let profile = (response.profile[i] === '') ? '../assets/icons/person.png' : imgFormat + response.profile[i]

                    // display the comments
                    let wrapper = $('<div>').addClass('flex gap-2 text-sm text-greyish_black')
                    let profilePic = $('<img>')
                        .attr('src', profile)
                        .addClass('rounded-full w-10 h-10')

                    let nameElement = $('<p>').addClass('font-bold').text(fullname)
                    let delComment = $('<button>').addClass('text-xs hover:text-red-400').text('Delete')
                        .on('click', function () {
                            $('#delete-modal').removeClass('hidden')
                            // delete the comment
                            $('#deletePostbtn').on('click', function () {
                                deleteComment(commentID, wrapper);
                                // update the count
                                let newCountComment = parseInt($('#statusComment').text()) - 1
                                $('#statusComment').text(newCountComment)
                            })
                        })

                    let container = $('<div>').addClass('flex items-center justify-between').append(nameElement, delComment)

                    let commentElement = $('<pre>').addClass('text-gray-500').text(comment)
                    let commentDetail = $('<div>').addClass('flex-1 flex-col w-4/5 bg-gray-300 rounded-md p-2 ')
                        .append(container, commentElement)

                    wrapper.append(profilePic, commentDetail)
                    $('#commentStatus').append(wrapper)
                }
            },
            error: error => { console.log(error) }
        })
    }

    function deleteComment(commentID, wrapper) {
        const action = { action: "deleteComment" };
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('commentID', commentID);

        $.ajax({
            url: '../PHP_process/commentData.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                if (response === 'Success') {
                    $('#delete-modal').addClass('hidden')
                    wrapper.remove()
                }
            }
        })
    }
})


function buttonColor() {
    var targetDiv = document.getElementById("target-div");
    targetDiv.classList.toggle("red-color");

    var icon = document.querySelector("#notif-btn .fa");
    var text = document.querySelector("#notif-btn .text-greyish_black");

    if (targetDiv.classList.contains("red-color")) {
        icon.style.color = "white";
        text.style.color = "white";
        targetDiv.classList.add("hover:bg-red-900");
    } else {
        icon.style.color = "";
        text.style.color = "";
        targetDiv.classList.remove("hover:bg-red-900");
    }
}