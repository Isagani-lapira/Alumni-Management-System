$(document).ready(function () {
    const imgFormat = 'data:image/jpeg;base64,';
    const colCode = $('#colCode').html();
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
    var maxRetrieve = 10;
    let dataRetrieved = 0;
    var stoppingPostRetrieval = 0;
    let postStatus = "normal";

    getPost()
    //retrieve post data
    function getPost() {
        let action = {
            action: 'readColPost',
            retrievalDate: retrievalDate, // to be change
            maxRetrieve: maxRetrieve
        }
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));

        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                //no data available for the day
                if (response == "none" && maxRetrieve != 0 && stoppingPostRetrieval != 150) {
                    $('#loadingDataFeed').removeClass('hidden').appendTo('#feedContainer')
                    retrievalDate = getPreviousDate(noOfDaySubtract);
                    getPost()
                    noOfDaySubtract++ //if no more the day will be increasing to get the previous date
                    stoppingPostRetrieval++
                }
                else if (response != 'none') {
                    $('#loadingDataFeed').addClass('hidden')
                    $('.lds-facebook').parent().addClass('hidden')
                    const parsedResponse = JSON.parse(response); //parsed the json data
                    //check for response
                    if (parsedResponse.response == "Success") {

                        const length = parsedResponse.username.length;
                        for (let i = 0; i < length; i++) {
                            //store data that retrieve
                            const postID = parsedResponse.postID[i];
                            const fullname = parsedResponse.fullname[i];
                            const username = parsedResponse.username[i];
                            const isLiked = parsedResponse.isLiked[i];
                            const images = parsedResponse.images[i];
                            const imgProfile = (parsedResponse.profilePic[i] == '') ? '../assets/icons/person.png' : imgFormat + parsedResponse.profilePic[i]
                            const caption = parsedResponse.caption[i];
                            let date = parsedResponse.date[i];
                            const likes = parsedResponse.likes[i];
                            const comments = parsedResponse.comments[i];
                            date = getFormattedDate(date) //formatted date for easy viewing of date
                            displayPost(imgProfile, username, fullname, caption, images, date, likes, comments, postID, isLiked); //display the post on the container
                        }

                        dataRetrieved = length; // get how many data has been retrieve for that day
                        maxRetrieve = maxRetrieve - dataRetrieved;
                        if (maxRetrieve != 0) {
                            $('#loadingDataFeed').removeClass('hidden').appendTo('#feedContainer')
                            retrievalDate = getPreviousDate(noOfDaySubtract);
                            stoppingPostRetrieval = 0;
                            getPost()
                        } else maxRetrieve = 10;
                    }
                    else {
                        $('#loadingDataFeed').removeClass('hidden').appendTo('#feedContainer')
                        retrievalDate = getPreviousDate(noOfDaySubtract);
                        getPost()
                    }
                }
                else {
                    $('#loadingDataFeed').addClass('hidden')
                    $('.lds-facebook').parent().addClass('hidden')
                    $('#noPostMsgFeed').removeClass('hidden')
                        .appendTo('#feedContainer');
                }

            }
        })


    }


    function getFormattedDate(date) {
        //parts out the date
        let year = date.substring(0, 4);
        let dateMonth = parseInt(date.substring(5, 7));
        let day = date.substring(8, 10);

        const listOfMonths = ['', 'January', 'February', 'March', 'April', 'May',
            'June', 'July', 'August', 'September', 'October', 'November', 'December']
        let month = listOfMonths[dateMonth];

        return month + ' ' + day + ', ' + year
    }


    function retrieveNewPost(username) {
        const action = { action: "getNewlyCreatedPost" }
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('username', username);

        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                console.log(response)
                $('.loadingProfile').parent().addClass('hidden');
                const parsedResponse = JSON.parse(response); //parsed the json data
                const length = parsedResponse.username.length;
                for (let i = 0; i < length; i++) {
                    //store data that retrieve
                    const postID = parsedResponse.postID[i];
                    const fullname = parsedResponse.fullname[i];
                    const username = parsedResponse.username[i];
                    const isLiked = parsedResponse.isLiked[i];
                    const images = parsedResponse.images[i];
                    const imgProfile = (parsedResponse.profilePic[i] == '') ? '../assets/icons/person.png' : imgFormat + parsedResponse.profilePic[i]
                    const caption = parsedResponse.caption[i];
                    let date = parsedResponse.date[i];
                    const likes = parsedResponse.likes[i];
                    const comments = parsedResponse.comments[i];
                    date = getFormattedDate(date) //formatted date for easy viewing of date

                    const position = 1 //display on the top
                    displayPost(imgProfile, username, fullname, caption, images, date, likes, comments, postID, isLiked, position); //display the post on the container
                }
                // displayPostPrompt('Post successfully added') //display successfully added
            },
            error: error => { console.log(error) }
        })
    }
    function displayPost(imgProfile, username, fullname, caption, images, date, likes, comments, postID, isLikedByUser, position = 0) {
        let postWrapper = $('<div>').addClass("postWrapper center-shadow w-full p-4 mb-2 rounded-md");

        let header = $('<div>');
        let headerWrapper = $('<div>').addClass("flex gap-2 items-center");
        let userProfile = $('<img>').addClass("h-10 w-10 rounded-full").attr('src', imgProfile);
        let authorDetails = $('<div>').addClass("flex-1");
        let fullnameElement = $('<p>').addClass("font-bold text-greyish_black").text(fullname);
        let usernameElement = $('<p>').addClass("text-gray-400 text-xs").text(username);

        // Header content
        authorDetails.append(fullnameElement, usernameElement);
        headerWrapper.append(userProfile, authorDetails);
        header.append(headerWrapper);

        // Markup for body
        let description = $('<p>').addClass('text-sm text-gray-500 my-2').text(caption);
        let swiperContainer = null;

        // Check if there are images to display
        if (images.length > 0) {
            swiperContainer = $('<div>').addClass("swiper h-80 bg-black rounded-md cursor-pointer");
            let swiperWrapper = $('<div>').addClass("swiper-wrapper");

            // Add images
            images.forEach(image => {
                let postImg = imgFormat + image;

                // Create slides for the image
                let slide = $('<div>').addClass("swiper-slide relative flex justify-center items-center");
                let imageContainer = $('<img>').addClass('object-contain h-full').attr('src', postImg);

                slide.append(imageContainer);
                swiperWrapper.append(slide);
            });

            swiperContainer.append(swiperWrapper);

            // Navigation buttons
            if (images.length > 1) {
                let pagination = $('<div>').addClass("swiper-pagination");
                let prevBtn = $('<div>').addClass("swiper-button-prev");
                let nextBtn = $('<div>').addClass("swiper-button-next");
                swiperContainer.append(pagination, prevBtn, nextBtn);
            }

            new Swiper('.swiper', {
                // If we need pagination
                pagination: {
                    el: '.swiper-pagination',
                },

                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            swiperContainer.on('click', function (event) {
                // Check if the click event is coming from the navigation buttons
                if (!$(event.target).hasClass('swiper-button-prev') && !$(event.target).hasClass('swiper-button-next')) {
                    $('#viewingPost').removeClass("hidden");
                    viewingOfPost(postID, fullname, username, caption, images, likes, imgProfile, commentElement);
                }
            });

        } else {
            postWrapper.css('min-height', '100px')
                .on('click', function (e) {
                    const target = e.target
                    const button = reportElement

                    if (!button.is(target) && button.has(target).length === 0)
                        viewStatusPost(postID, fullname, date, caption, likes, username, commentElement)

                })
        }

        date_posted = $('<p>').addClass('text-xs text-gray-500 my-2').text(date);

        let newlyAddedLike = parseInt(likes);
        //interaction buttons
        let isLiked = isLikedByUser;
        let heartIcon = $('<span>')
        if (isLiked)
            heartIcon = heartIcon.html('<iconify-icon icon="mdi:heart" style="color: #ed1d24;" width="20" height="20"></iconify-icon>');
        else
            heartIcon = heartIcon.html('<iconify-icon icon="mdi:heart-outline" style="color: #626262;" width="20" height="20"></iconify-icon>')

        let interactionContainer = $('<div>').addClass('border-t border-gray-400 p-2 flex items-center justify-between')
        heartIcon.addClass('cursor-pointer flex items-center')
            .on('click', function (e) {
                e.stopPropagation()
                //toggle like button
                if (isLiked) {
                    //decrease the current total number of likes by 1
                    newlyAddedLike -= 1
                    likesElement.text(newlyAddedLike)
                    heartIcon.html('<iconify-icon icon="mdi:heart-outline" style="color: #626262;" width="20" height="20"></iconify-icon>');
                    removeLike(postID)
                }
                else {
                    //increase the current total number of likes by 1
                    newlyAddedLike += 1
                    likesElement.text(newlyAddedLike)
                    heartIcon.html('<iconify-icon icon="mdi:heart" style="color: #ed1d24;" width="20" height="20"></iconify-icon>');
                    addLikes(postID)
                }

                isLiked = !isLiked;
            });

        let commentIcon = $('<span>').html('<iconify-icon icon="uil:comment" style="color: #626262;" width="20" height="20"></iconify-icon>')
            .addClass('cursor-pointer flex items-center comment')
        let likesElement = $('<p>').addClass('text-xs text-gray-500').text(likes)
        let commentElement = $('<p>').addClass('text-xs text-gray-500 comment').text(comments)
        let leftContainer = $('<div>').addClass('flex gap-2 items-center').append(heartIcon, likesElement, commentIcon, commentElement)

        //make comment
        commentIcon.on('click', function (e) {
            e.stopPropagation()
            $('#commentPost').removeClass('hidden') //open the comment modal

            //set up the details for comment to be display
            $('#postProfile').attr('src', imgProfile)
            $('#postFullname').text(fullname)
            $('#postUsername').text(username)
            $('#replyToUsername').text(username)

            //insert a comment to database
            $('#commentBtn').on('click', function () {
                let commentVal = $('#commentArea').val()
                insertComment(postID, commentVal, commentElement)
            })

        })
        let reportElement = $('<p>').addClass('text-xs text-red-400 cursor-pointer ')
            .text('report')
            .on('click', function () {
                //open report modal
                $('#reportModal').removeClass('hidden')

                reportProcess(postID)
            })
        interactionContainer.append(leftContainer, reportElement)

        //set up the details of the post
        postWrapper.append(header, description, swiperContainer, date_posted, interactionContainer)

        if (position === 1) {
            $('#feedContainer').children().eq(1).before(postWrapper);
        } else
            $('#feedContainer').append(postWrapper);

    }

    $("#closeComment").on('click', function () {
        $('#commentPost').addClass('hidden')
    })

    $('#commentArea').on('input', function () {
        let commentVal = $(this).val();
        //enable the comment button
        if (commentVal != "") {
            $('#commentBtn').removeAttr('disabled')
                .addClass('bg-accent')
                .removeClass('bg-red-950')
        }
        else {
            $('#commentBtn').attr('disabled', true) //disabled the button again
                .addClass('bg-red-950')
                .removeClass('bg-accent')
        }
    })


    function reportProcess(postID) {
        let haveReported = false;
        $('.reportCateg').each(function () {
            let checkBox = $(this)

            checkBox.on('click', function () {
                haveReported = $('.reportCateg:checked').length > 0;
                //add the behavior of report button
                let reportBtn = $('#reportBtn');

                //enable the rerport butotn
                if (haveReported) {
                    reportBtn.removeAttr('disabled')
                        .addClass('text-white bg-accent')
                        .removeClass('bg-red-300 text-gray-300')

                }
                else {
                    reportBtn.attr('disabled', true)
                        .removeClass('text-white bg-accent')
                        .addClass('bg-red-300 text-gray-300');
                }
            })
        })

        let reportBtn = $('#reportBtn')
        reportBtn.on('click', function () {
            $('.reportCateg:checked').each(function () {
                let reportCateg = $(this).val();
                reportPost(postID, reportCateg)
            })
            setTimeout(function () {
                $('#reportModal').addClass('hidden')
                $('#successModal').removeClass('hidden')

                setTimeout(function () {
                    $('#successModal').addClass('hidden');
                }, 5000);
            }, 2000);

        })
    }


    //report a specific post
    function reportPost(postID, reportCateg) {
        let action = {
            action: 'reportPost'
        }
        const formData = new FormData();
        formData.append('action', JSON.stringify(action))
        formData.append('postID', postID);
        formData.append('category', reportCateg);

        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        })
    }


    function insertComment(postID, comment, commentElement) {
        const action = {
            action: 'insertComment'
        }

        const formData = new FormData();
        formData.append('action', JSON.stringify(action))
        formData.append('postID', postID);
        formData.append('comment', comment);

        $.ajax({
            url: '../PHP_process/commentData.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: (response) => {
                if (response === 'Success') {
                    $('#commentPost').addClasdisplayPostPrompts('hidden') //hide modal
                    displayPostPrompt('Comment successfully added')
                    $('#commentArea').val('') //restart the value of comment
                    let commentCount = parseInt(commentElement.text()) + 1
                    commentElement.text(commentCount) //update the count of comment of ta certain post
                }
            },
            error: error => {
                console.log(error)
            }
        })
    }

    function displayPostPrompt(message) {
        $('#promptMsg').removeClass('hidden')//display the prompt
            .text(message)

        //hide again after 4 seconds
        setTimeout(() => {
            $('#promptMsg').addClass('hidden')
        }, 4000)
    }
    //add the likes to a post
    function addLikes(postID) {
        let action = {
            action: 'addLike',
        }

        const formData = new FormData();
        formData.append('action', JSON.stringify(action))
        formData.append('postID', postID);

        //process the adding of like
        $.ajax({
            url: '../PHP_process/likesData.php',
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

        })
    }

    //add the likes to a post
    function removeLike(postID) {
        let action = {
            action: 'removeLike',
        }

        const formData = new FormData();
        formData.append('action', JSON.stringify(action))
        formData.append('postID', postID);

        //process the removal of like
        $.ajax({
            url: '../PHP_process/likesData.php',
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

        })
    }

    //add retrieve new data
    $('#feedContainer').on('scroll', function () {
        const containerHeight = $(this).height();
        const contentHeight = $(this)[0].scrollHeight;
        const scrollOffset = $(this).scrollTop();
        const threshold = 50;

        //once the bottom ends, it will reach another sets of data (post)
        if (scrollOffset + containerHeight + threshold >= contentHeight && dataRetrieved === 10) {
            // reload the loading 
            $('#loadingDataFeed').removeClass('hidden').appendTo('#feedContainer');
            if (postStatus === 'normal') getPost();
            else refreshPost()
        }
        else $('#loadingDataFeed').addClass('hidden')

    })


    //close the post modal view
    $('#closePostModal').on('click', function () {
        $('#viewingPost').addClass('hidden')
        $('#carousel-wrapper').empty()
        $("#carousel-indicators").empty();
    })

    function viewingOfPost(postID, name, accUN, description, images, likes, imgProfile, commentElement) {
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

        getComment(postID, commentElement); //retrieve the comment if available

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
    function getComment(postID, commentCount) {
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
                        const commentID = parsedResponse.commentID[i];
                        const username = parsedResponse.username[i];
                        const comment = parsedResponse.comment[i];
                        let img = (parsedResponse.profile[i] == '') ? '../assets/icons/person.png' : imgFormat + parsedResponse.profile[i]

                        let commentContainer = $('<div>').addClass("flex gap-2 my-2")
                        let imgProfile = $('<img>').addClass("h-8 w-8 rounded-full").attr('src', img);
                        let commentDescript = $('<div>').addClass("bg-gray-300 rounded-md p-3 flex-grow text-sm flex flex-col gap-1 text-greyish_black");
                        let commentor = $('<p>').text(fullname)
                        let container = $('<div>').addClass('flex justify-between').append(commentor)
                        let delComment = $('<button>').addClass('text-xs hover:text-red-400').text('Delete')
                            .on('click', function () {
                                $('#delete-modal').removeClass('hidden')
                                // delete the comment
                                $('#deletePostbtn').on('click', function () {
                                    deleteComment(commentID, commentContainer);
                                    // update the count
                                    let newCountComment = parseInt(commentCount.text()) - 1
                                    commentCount.text(newCountComment)
                                    $('#noOfComment').text(newCountComment)
                                })
                            })
                        let currentUser = $('#accUsername').text();
                        if (username === currentUser)
                            container.append(delComment)

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

        })
    }


    //open the post modal
    $('#postButton').on('click', function () {
        $('#modal').removeClass('hidden')
    })
    let validExtension = ['jpeg', 'jpg', 'png'] //only allowed extension
    let fileExtension
    //close modal
    $('.cancel').click(function () {
        prompt("#modal", false)
        restartPostModal()
    })

    function restartPostModal() {
        //remove the images
        while (imgContPost.firstChild) {
            imgContPost.removeChild(imgContPost.firstChild)
            selectedFiles = [];
        }
        $('#TxtAreaAnnouncement').val('')
    }

    //show or close the prompt modal
    function prompt(id, openIt) {
        openIt == true ? $(id).removeClass('hidden') : $(id).addClass('hidden')
    }

    let imageSequence = 1;
    let selectedFiles = [];
    //add image to the modal
    $('#fileGallery').change(() => {

        $('#errorMsg').addClass('hidden') //always set the error message as hidden when changing the file
        $('#TxtAreaAnnouncement').addClass('h-5/6').removeClass('h-3/6')

        //file input
        var fileInput = $('#fileGallery')
        var file = fileInput[0].files[0] //get the first file that being select

        fileExtension = file.name.split('.').pop().toLowerCase() //getting the extension of the selected file
        //checking if the file is based on the extension we looking for
        if (validExtension.includes(fileExtension)) {
            var reader = new FileReader()
            selectedFiles.push(file); // Store the selected file in the array
            //new image element to be place on the  image container div
            const imageElement = document.createElement('img')

            const imgPlaceHolder = document.createElement('div')
            imgPlaceHolder.className = "relative"

            //for button x
            const xBtn = document.createElement('button')
            xBtn.innerHTML = 'X'
            xBtn.className = 'xBtn absolute h-5 w-5 top-0 text-center right-0 cursor-pointer rounded-full hover:bg-accent hover:text-white hover:font-bold'
            //remove the image
            xBtn.addEventListener('click', function (e) {
                var parent = e.target.parentNode
                var index = Array.from(parent.parentNode.children).indexOf(parent); //get a specific index which picture has been remove
                selectedFiles.splice(index, 1); // Remove the file from the selectedFiles array
                parent.parentNode.removeChild(parent)
            })

            // img element
            imageElement.className = 'flex-shrink-0 h-20 w-20 rounded-md m-2'
            imageElement.setAttribute('id', 'reservedPicture' + imageSequence) //to make sure every id is unique

            //add to its corresponding container
            imgPlaceHolder.appendChild(imageElement)
            imgPlaceHolder.appendChild(xBtn)
            imgContPost.appendChild(imgPlaceHolder)

            //assign the image path to the img element
            reader.onload = function (e) {
                $('#reservedPicture' + imageSequence).attr('src', e.target.result)
                $('#imgContPost').removeClass('hidden')
                $('#TxtAreaAnnouncement').addClass('h-3/6').removeClass('h-5/6') //make the text area smaller in height
                imageSequence++
            }

            reader.readAsDataURL(file)
        }
        else {
            $('#errorMsg').removeClass('hidden') //if the file is not based on the img extension we looking for
        }

    })

    //make a post
    $('#postBtn').on('click', function () {
        let formData = new FormData();
        let caption = $('#TxtAreaAnnouncement').val();
        let college = $('#colCode').html();

        let action = {
            action: 'insert',
        }
        formData.append('caption', caption);
        formData.append('college', college);
        formData.append('action', JSON.stringify(action));

        // Append each file individually to the FormData object
        for (let i = 0; i < selectedFiles.length; i++) {
            formData.append('files[]', selectedFiles[i]);
        }

        $('.loadingProfile').parent().removeClass('hidden');
        $.ajax({
            url: '../PHP_process/postDB.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response == 'successful') {
                    $('#modal').addClass('hidden');
                    restartPostModal()
                    const username = $('#accUsername').text()
                    retrieveNewPost(username)
                }

            },

        })
    })


    $('.closeStatusPost').on('click', function () {
        $('#postStatusModal').addClass('hidden')
    })

    function viewStatusPost(postID, name, postDate, postcaption, likes, username, commentElement) {
        $('#postStatusModal').removeClass('hidden')
        $('#statusFullnameUser').text(name)
        $('#statusDate').text(postDate)
        $('.accountUN').text(username)
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

                if (commentCount > 0) displayComments(postID, commentElement)

            },
            error: error => { console.log(error) }
        })
    }

    function displayComments(postID, commentCount) {
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
                let length = response.fullname.length;
                $('#commentStatus').empty()
                for (let i = 0; i < length; i++) {
                    let fullname = response.fullname[i];
                    let commentID = response.commentID[i];
                    let username = response.username[i];
                    let comment = response.comment[i];
                    let profile = (response.profile[i] === '') ? '../assets/icons/person.png' : imgFormat + response.profile[i];

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
                                let newCountComment = parseInt(commentCount.text()) - 1
                                commentCount.text(newCountComment)
                                $('#statusComment').text(newCountComment)
                            })
                        })
                    let container = $('<div>').addClass('flex items-center justify-between').append(nameElement)

                    let currentUser = $('#accUsername').text();
                    if (username === currentUser)
                        container.append(delComment)

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

    $('.closeDeleteBtn').on('click', function () {
        $('#delete-modal').addClass('hidden')
    })

    $('.refresher').on('click', function () {
        postStatus = "refresher"
        refresherOffset = 0;
        refreshPost(); //retrieve all post in college by post no restriction
    })

    let refresherOffset = 0;
    function refreshPost() {
        const action = { action: 'getAllCollegePost' };
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('offset', refresherOffset);

        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response === 'Success') {
                    $('#noPostMsgFeed').addClass('hidden')
                    const length = response.username.length;
                    for (let i = 0; i < length; i++) {
                        //store data that retrieve
                        const postID = response.postID[i];
                        const fullname = response.fullname[i];
                        const username = response.username[i];
                        const isLiked = response.isLiked[i];
                        const images = response.images[i];
                        const imgProfile = (response.profilePic[i] == '') ? '../assets/icons/person.png' : imgFormat + response.profilePic[i]
                        const caption = response.caption[i];
                        let date = response.date[i];
                        const likes = response.likes[i];
                        const comments = response.comments[i];
                        date = getFormattedDate(date) //formatted date for easy viewing of date
                        displayPost(imgProfile, username, fullname, caption, images, date, likes, comments, postID, isLiked); //display the post on the container
                    }

                    refresherOffset += length
                }
                else {
                    $('#loadingDataFeed').addClass('hidden')
                    $('.lds-facebook').parent().addClass('hidden')
                    $('#noPostMsgFeed').removeClass('hidden')
                        .appendTo('#feedContainer');
                }
            }

        })
    }
})