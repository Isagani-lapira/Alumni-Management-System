const imgContPost = document.getElementById('imgContPost')
$(document).ready(function () {

    let validExtension = ['jpeg', 'jpg', 'png'] //only allowed extension
    let fileExtension
    const imgFormat = "data:image/jpeg;base64,";
    // profile of the current user
    let imgProfileVal = $('.profilePicVal').html();
    let profilePic = imgFormat + imgProfileVal;

    //show or close the prompt modal
    function prompt(id, openIt) {
        openIt == true ? $(id).removeClass('hidden') : $(id).addClass('hidden')
    }
    //close modal
    $('.cancel').click(function () {
        prompt("#modal", false)

        //remove the images
        while (imgContPost.firstChild) {
            imgContPost.removeChild(imgContPost.firstChild)
            selectedFiles = [];
        }
        $('#TxtAreaAnnouncement').val('')
    })

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

    // //open modal post
    $("#btnAnnouncement").click(function () {
        $('#modal').removeClass('hidden')
    });

    //make a post
    $('#postBtn').on('click', function () {
        let caption = $('#TxtAreaAnnouncement').val();
        let college = $('#collegePost').val();

        // check if the post has atleast have description or 1 image
        if (caption != "" || selectedFiles.length != 0) {
            if (college == "all") {
                $('#collegePost option').slice(1).each(function () { // to skip the index 0 (all)
                    const collegeVal = $(this).val();
                    postInsertion(caption, collegeVal);
                })
            }
            else postInsertion(caption, college);

            //refresh a table list
            restartTableContent()
            getPostAdmin(postData, true)

            // restart the form
            //remove the images
            while (imgContPost.firstChild) {
                imgContPost.removeChild(imgContPost.firstChild)
                selectedFiles = [];
            }
            $('#TxtAreaAnnouncement').val('')
        }

    })

    function postInsertion(caption, college) {
        let formData = new FormData();
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

        $.ajax({
            url: '../PHP_process/postDB.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                $('#modal').addClass('hidden');
                $('#promptMsg').removeClass('hidden')
                $('#message').text('Announcement successfully posted!')
                setTimeout(() => {
                    $('#promptMsg').addClass('hidden')
                }, 4000)
                selectedFiles = [];
            },
        })
    }

    let postAction = {
        action: 'read',
    }
    let offsetPost = 0;
    let postData = new FormData();
    postData.append('action', JSON.stringify(postAction));
    postData.append('startDate', "")
    postData.append('endDate', "")
    postData.append('offset', offsetPost)

    $('#announcementLI').on('click', function () {
        restartTableContent()
        getPostAdmin(postData, true)
        $('#announcement-tab').removeClass('hidden')
    })

    // //show post of admin
    function getPostAdmin(data, isTable) {
        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: (response) => {
                let data = response;
                if (data.response == 'Success') {
                    $('#noPostMsg').hide()
                    let length = data.colCode.length;
                    let username = data.username;
                    let fullname = $('#userFullname').html(); //change base on the full name of the user
                    for (let i = 0; i < length; i++) {
                        let postID = data.postID[i]
                        let collegeCode = data.colCode[i]
                        let caption = data.caption[i]
                        let date = data.date[i]
                        let comment = data.comments[i];
                        let likes = data.likes[i];
                        date = textDateFormat(date) //change to text format

                        if (isTable) announcementTbDisplay(postID, collegeCode, fullname, username, caption, date, i, comment, likes, length) //add post in table;
                        else displayToProfile();
                    }
                    toAddProfile = false //won't be affected by date range 
                    offsetPost += length

                    // Check if the table row count is equal to 10
                    if (isTable && length === 10) {
                        postData.set('offset', offsetPost);
                        getPostAdmin(postData, true);
                    }
                    $('.totalPost').text(table.rows().count())
                }

            },
            error: (error) => { console.log(error) }
        })
    }


    function textDateFormat(date) {
        //extract parts of date
        year = date.substr(0, 4);
        month = parseInt(date.substr(5, 2));
        day = date.substr(8, 2)

        //convert number months to text month
        months = ["", "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];

        textDate = months[month] + ' ' + day + ' ' + year;
        return textDate;
    }


    $('#postTable').DataTable({
        "paging": true,
        "ordering": false,
        "info": false,
        "lengthChange": false,
        "pageLength": 10
    });

    let table = $('#postTable').DataTable(); // Get the DataTable instance
    $('#postTable').removeClass('dataTable').addClass('rounded-lg')
    function announcementTbDisplay(postID, colCode, name, accUN, postcaption, postdate, position, comments, likes) {

        // Create a new row
        let row = [
            colCode,
            likes,
            comments,
            postdate,
            `<button class="text-sm text-red-400 rounded-lg p-1 hover:bg-red-400 hover:text-white delCommunityPost"
            data-postid="${postID}">Archive</button>

            <button class="bg-blue-400 text-sm text-white rounded-lg py-1 px-2 hover:bg-blue-500 view-button"
            data-postid="${postID}"
            data-name="${name}"
            data-username="${accUN}"
            data-postcaption="${postcaption}"
            data-position="${position}"
            data-postdate="${postdate}"
            data-likes="${likes}"
            >View</button>`

        ];

        // Add the new row to the DataTable
        table.row.add(row).draw();

    }

    let collgeFilter = ""
    let startDateFilter = ""
    let endDateFilter = ""

    $('#announcementCol').on('change', function () {
        collgeFilter = $(this).val()
        filterTable();
    })

    // filtering date
    $(function () {
        $('input[name="daterange"]').daterangepicker(
            {
                opens: "left",
                startDate: defaultStart,
                endDate: defaultEnd,
            },
            function (start, end, label) {
                startDateFilter = start.format('MMMM DD YYYY');
                endDateFilter = end.format('MMMM DD YYYY')

                filterTable()
            }
        );
    });

    // back to default button
    const defaultButton = $('<button class="hover:bg-accent rounded-md hover:text-white px-2">Default</button>')
        .on('click', function () {
            startDateFilter = ''
            endDateFilter = ''
            $('#daterange').val('Select date')
            filterTable()
        })

    $('#daterange').parent().append(defaultButton)

    function filterTable() {
        table.column(0).search('').column(3).search('')// reset as default
        if (collgeFilter !== '' && startDateFilter === '' && endDateFilter === '') //college filtering
            table.column(0).search(collgeFilter);
        else if (collgeFilter === '' && startDateFilter !== '' && endDateFilter !== '') //date filtering
            table.column(3).search(startDateFilter + '|' + endDateFilter, true, false, true).draw();
        else if (collgeFilter !== '' && startDateFilter !== '' && endDateFilter !== '') {
            // search both college and date
            table.column(0).search(collgeFilter);
            table.column(3).search(startDateFilter + '|' + endDateFilter, true, false, true);
        }
        table.draw()
    }


    $('#postTable').on('click', '.view-button', function () {
        const postID = $(this).data('postid');
        const name = $(this).data('name');
        const accUN = $(this).data('username');
        const postcaption = $(this).data('postcaption');
        const postDate = $(this).data('postdate');
        const likes = $(this).data('likes');

        // get images
        const action = { action: "getPostImages" };
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('postID', postID);


        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                const images = response;
                if (images.length !== 0)
                    viewingOfPost(postID, name, accUN, postcaption, images, likes)
                else
                    viewStatusPost(postID, name, postDate, postcaption, likes, accUN)
            },

        });

    });

    $('.closeDeleteBtn').on('click', function () {
        $('#delete-modal').addClass('hidden')
    })

    $('#postTable').on('click', '.delCommunityPost', function () {
        let postID = $(this).data('postid')

        //open the delete prompt
        $('#delete-modal').removeClass('hidden')
        //update the post status into deleted
        $('#deletePostbtn').on('click', function () {
            const status = "deleted"
            updatePostStatus(status, postID, false)
        })
    })


    function viewingOfPost(postID, name, accUN, description, images, likes) {
        $('#modalPost').removeClass('hidden');
        $('#profilePic').attr('src', profilePic);
        $('#postFullName').text(name);
        $('#postUN').text(accUN);
        $('#noOfLikes').text(likes);
        $('#postDescript').text(description).addClass('text-sm my-2 text-gray-400');

        const carouselWrapper = $('#carousel-wrapper');
        const carouselIndicators = $('#carousel-indicators');

        let totalImgNo = images.length;
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

        let currentImageDisplay = 0;
        $('#btnNext').on('click', function () {
            $('#item-' + currentImageDisplay).addClass('hidden'); // Hide the current image
            currentImageDisplay = (currentImageDisplay + 1) % totalImgNo; // Move to the next image
            $('#item-' + currentImageDisplay).removeClass('hidden'); // Show the next image
        });

        $('#btnPrev').on('click', function () {
            $('#item-' + currentImageDisplay).addClass('hidden'); // Hide the current image
            currentImageDisplay = (currentImageDisplay - 1 + totalImgNo) % totalImgNo; // Move to the previous image
            $('#item-' + currentImageDisplay).removeClass('hidden'); // Show the previous image
        });

        getComment(postID);

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

    function viewStatusPost(postID, name, postDate, postcaption, likes, username) {
        $('#postStatusModal').removeClass('hidden')
        $('#statusFullnameUser').text(name)
        $('#statusDate').text(postDate)
        $('.accountUN').text(username)
        $('#statusDescript').html(postcaption)
        $('#statusLikes').text(likes)
        $('#commentStatus').empty() //remove previously displayed comment

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

            },

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
                let length = response.fullname.length;
                for (let i = 0; i < length; i++) {
                    let fullname = response.fullname[i];
                    let comment = response.comment[i];
                    let profile = (response.profile[i] == '') ? '../assets/icons/person.png' : imgFormat + response.profile[i];

                    // display the comments
                    let wrapper = $('<div>').addClass('flex gap-2 text-sm text-greyish_black')
                    let profilePic = $('<img>')
                        .attr('src', profile)
                        .addClass('rounded-full w-10 h-10')

                    let nameElement = $('<p>').addClass('font-bold').text(fullname)
                    let commentElement = $('<pre>').addClass('text-gray-500').text(comment)
                    let commentDetail = $('<div>').addClass('flex-1 flex-col w-4/5 bg-gray-300 rounded-md p-2 ')
                        .append(nameElement, commentElement)

                    wrapper.append(profilePic, commentDetail)
                    $('#commentStatus').append(wrapper)
                }
            },

        })
    }
    //close the post modal view
    $('#closePostModal').on('click', function () {
        $('#modalPost').addClass('hidden')
        $('#carousel-wrapper').empty()
        $("#carousel-indicators").empty();
    })


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
    let actionTracker = "";
    let isAvailable = true

    //retrieve the post after clicking the profile tab
    $('#profileTabAdmin').on('click', function () {
        actionTracker = formDataAvailable
        displayToProfile(formDataAvailable, isAvailable)
    })

    let offsetProfile = 0;
    const profileAction = {
        action: 'readUserProfile',
    }
    const formDataAvailable = new FormData()
    formDataAvailable.append('action', JSON.stringify(profileAction));
    formDataAvailable.append('status', 'available');

    function displayToProfile(data, isAvailable) {
        data.append('offset', offsetProfile)
        //process retrieval
        $.ajax({
            method: 'POST',
            url: "../PHP_process/postDB.php",
            data: data,
            contentType: false,
            processData: false,
            success: response => {
                $('.loadingProfile').addClass('hidden')
                if (response != 'none') {
                    const parsedData = JSON.parse(response)
                    const length = parsedData.username.length
                    //get all the data that gather
                    for (let i = 0; i < length; i++) {
                        const imgProfile = parsedData.profilePic[i];
                        const postID = parsedData.postID[i];
                        const isLiked = parsedData.isLiked[i];
                        const fullname = parsedData.fullname[i];
                        const username = parsedData.username[i];
                        const images = parsedData.images[i];
                        const caption = parsedData.caption[i];
                        let date = parsedData.date[i];
                        const likes = parsedData.likes[i];
                        const comments = parsedData.comments[i];

                        displayPostToProfile(postID, imgProfile, fullname, username, images, caption, date, likes, comments, isAvailable, isLiked)
                    }

                    offsetProfile += length
                }

            },

        })
    }

    //get the date in a text format
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

    //display for in the profile tab
    function displayPostToProfile(postID, imgProfile, fullname, username, images, caption, date, likes, comments, isAvailable, isLikedByUser) {
        const feedContainer = $('#feedContainer')
        const container = $('<div>').addClass('containerPostProfile flex gap-1 justify-center p-4')
        const postWrapper = $('<div>').addClass('postWrapper rounded-md center-shadow p-4');

        //adding details for header
        let header = $('<div>');
        let headerWrapper = $('<div>').addClass("flex gap-2 items-center");
        let img = imgFormat + imgProfile;
        let userProfile = $('<img>').addClass("h-10 w-10 rounded-full").attr('src', img);
        let authorDetails = $('<div>').addClass("flex-1");
        let fullnameElement = $('<p>').addClass("font-bold text-greyish_black").text(fullname);
        let usernameElement = $('<p>').addClass("text-gray-400 text-xs").text(username);

        //set up the header to be displayed
        authorDetails.append(fullnameElement, usernameElement)
        headerWrapper.append(userProfile, authorDetails)
        header.append(headerWrapper)

        //for body
        let description = $('<pre>')
            .addClass('text-sm text-gray-500 my-2 w-full outline-none')
            .attr('contenteditable', false)
            .text(caption);

        let swiperContainer = null;
        //check if post is status only or with image
        if (images.length > 0) {
            //set up the swiper
            swiperContainer = $("<div>").addClass("swiper relative profilePostSwiper w-full h-80 bg-black rounded-md cursor-pointer overflow-x-hidden")
            const swiperWrapper = $('<div>').addClass('swiper-wrapper h-full')

            //carousel the image
            images.forEach(value => {
                let postImg = imgFormat + value;
                //create slider
                const swiperSlider = $('<div>').addClass('swiper-slide h-full flex justify-center')
                const swiperImg = $('<img>')
                    .addClass('object-contain h-full w-full bg-black')
                    .attr('src', postImg)

                //slider to wrapper
                swiperSlider.append(swiperImg)
                swiperWrapper.append(swiperSlider);
            })
            // Navigation buttons
            let pagination = $('<div>').addClass("swiper-pagination");
            let prevBtn = $('<div>').addClass("swiper-button-prev");
            let nextBtn = $('<div>').addClass("swiper-button-next ");
            swiperContainer.append(swiperWrapper, pagination, prevBtn, nextBtn);

            swiperContainer.on('click', function (event) {
                // Check if the click event is coming from the navigation buttons
                if (!$(event.target).hasClass('swiper-button-prev') && !$(event.target).hasClass('swiper-button-next')) {
                    $('#modalPost').removeClass("hidden");
                    viewingOfPost1(postID, fullname, username, caption, images, likes, img);
                }
            });

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

        } else {
            // status only
            postWrapper.css('min-height', '155px')
                .on('click', function (e) {
                    const target = e.target

                    // check if the clicked is not on the different icon element (comment, delete, and heart)
                    if (!deleteElement.is(target) && deleteElement.has(target).length === 0 &&
                        !commentIcon.is(target) && commentIcon.has(target).length === 0 &&
                        !heartIcon.is(target) && heartIcon.has(target).length === 0) {

                        // check if editting to avoid clicking on description
                        if (!isEditting) viewStatusPost(postID, fullname, date, caption, likes, username)
                    }

                })
        }

        let isEditting = false
        date = getFormattedDate(date)
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
            .addClass('cursor-pointer flex items-center')
            .on('click', function () {
                //toggle like button
                if (isLiked) {
                    //decrease the current total number of likes by 1
                    newlyAddedLike -= 1
                    console.log(newlyAddedLike)
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
        commentIcon.on('click', function () {
            $('#commentPost').removeClass('hidden') //open the comment modal

            //set up the details for comment to be display
            $('#postProfile').attr('src', img)
            $('#postFullname').text(fullname)
            $('#postUsername').text(username)
            $('#replyToUsername').text(username)

            //insert a comment to database
            $('#commentBtn').on('click', function () {
                let commentVal = $('#commentArea').val()
                insertComment(postID, commentVal, commentElement)
            })

        })
        const editIcon = $('<iconify-icon class="editIcon cursor-pointer" icon="akar-icons:edit" style="color: #626262;" width="24" height="24"></iconify-icon>');
        const exitEdit = $('<iconify-icon class="hidden cursor-pointer" icon="mdi:cancel-bold" style="color: #d0342c;" width="24" height="24"></iconify-icon>')
        let status = ""
        let deleteElement = $('<p>').addClass('text-xs cursor-pointer font-bold')
        if (isAvailable) {
            deleteElement.addClass('text-red-400')
                .text('Archive')
                .on('click', function () {
                    //open the delete prompt
                    $('#delete-modal').removeClass('hidden')
                    //update the post status into deleted
                    $('#deletePostbtn').on('click', function () {
                        status = "deleted"
                        updatePostStatus(status, postID, true)
                    })
                })
        }
        else {
            $('.editIcon').addClass('hidden')
            leftContainer.addClass('hidden')
            interactionContainer.removeClass('justify-between').addClass('justify-end')
            deleteElement.addClass('text-green-400')
                .text('Restore')
                .on('click', function () {
                    $('#restoreModal').removeClass('hidden')
                    $('#restorePost').on('click', function () {
                        status = 'available'
                        updatePostStatus(status, postID, true)
                    })
                })
        }

        //approve update
        const approvedIcon = $('<iconify-icon class="cursor-pointer hidden" icon="radix-icons:check" style="color: #3cb043;" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                //data to be sent
                const action = { action: 'updatePost' }
                const formdata = new FormData();
                formdata.append('action', JSON.stringify(action))
                formdata.append('postID', postID)
                formdata.append('caption', description.text())

                $.ajax({
                    url: '../PHP_process/postDB.php',
                    method: 'POST',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: response => {
                        if (response == 'Successful') {
                            //go back to normal
                            description.attr('contenteditable', false)
                            approvedIcon.addClass('hidden')
                            exitEdit.addClass('hidden')
                            editIcon.removeClass('hidden')
                        }
                    },

                })
            })
        //update description
        let currentDescript = description.text()
        editIcon.on('click', function () {
            description.attr('contenteditable', true)
                .focus()
            $(this).addClass('hidden')
            approvedIcon.removeClass('hidden')
            exitEdit.removeClass('hidden')
            isEditting = true

        })
        const editingWrapper = $('<div>').addClass('flex gap-2 flex-col')
        //attach all details to a postwrapper and to the root
        interactionContainer.append(leftContainer, deleteElement)
        postWrapper.append(header, description, swiperContainer, date_posted, interactionContainer)

        exitEdit.on('click', function () {
            // back to default view
            $(this).addClass('hidden')
            approvedIcon.addClass('hidden')
            editIcon.removeClass('hidden')
            description.attr('contenteditable', false)
                .text(currentDescript)
        })
        editingWrapper.append(editIcon, approvedIcon, exitEdit)
        container.append(postWrapper, editingWrapper)
        feedContainer.append(container);

        var percent = feedContainer.width() * 0.80;
        postWrapper.width(percent);

    }

    $('#closeComment').on('click', function () {
        $('#commentPost').addClass('hidden')
    })

    function updatePostStatus(status, postID, isProfile) {
        let action = { action: 'updatePostStatus' };
        const formdata = new FormData()
        formdata.append('action', JSON.stringify(action));
        formdata.append('postID', postID);
        formdata.append('status', status);

        //process the deletion
        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            success: response => {
                console.log(response)
                //close the modal
                $('#delete-modal').addClass('hidden')
                if (isProfile) {
                    restartPost()
                    displayToProfile(actionTracker, isAvailable) //reload the post again
                    $('#restoreModal').addClass('hidden') //hide the modal again
                }
                else {
                    // restart everything
                    restartTableContent()
                    getPostAdmin(postData, true)
                }


            },

        })
    }
    function restartTableContent() {
        offsetPost = 0;
        postData.append('action', JSON.stringify(postAction));
        postData.set('startDate', "")
        postData.set('endDate', "")
        postData.set('offset', offsetPost)
        table.clear().draw();
    }
    function restartPost() {
        offsetProfile = 0
        //remove the currently displayed
        $('.containerPostProfile').remove();
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
            success: (response) => { console.log(response) },
            error: (error) => { console.log(error) }
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
            success: (response) => { console.log(response) },
            error: (error) => { console.log(error) }
        })
    }
    //add retrieve new data
    $('#feedContainer',).on('scroll', function () {
        const containerHeight = $(this).height();
        const contentHeight = $(this)[0].scrollHeight;
        const scrollOffset = $(this).scrollTop();

        //once the bottom ends, it will reach another sets of data (post)
        if (scrollOffset + containerHeight >= contentHeight) {
            displayToProfile(actionTracker, isAvailable);
        }

    })


    $('.closeStatusPost').on('click', function () {
        $('#postStatusModal').addClass('hidden')
    })

    function viewingOfPost1(postID, name, accUN, description, images, likes, imgProfile) {
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
                .addClass('relative duration-700 ease-in-out h-full flex justify-center items-center')
                .attr('data-carousel-item', '')
                .attr('id', imageName);

            const format = imgFormat + image;
            const img = $('<img>')
                .addClass('object-contain h-full w-full')
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

    const formDataArchived = new FormData();
    formDataArchived.append('action', JSON.stringify(profileAction))
    formDataArchived.append('status', 'deleted');

    //show archieved post
    $('#archievedBtnProfile').on('click', function () {
        $('.loadingProfile').removeClass('hidden') //loading screen
        $(this).addClass('activeBtn')
        $('#availablePostBtn').removeClass('activeBtn')
        restartPost() //restart everything

        actionTracker = formDataArchived
        isAvailable = false
        displayToProfile(formDataArchived, isAvailable) //process the retrieval of post for archieved post
    })

    $('#availablePostBtn').on('click', function () {
        $(this).addClass('activeBtn')
        $('.loadingProfile').removeClass('hidden') //loading screen
        $('#archievedBtnProfile').removeClass('activeBtn')
        restartPost() //restart everything

        actionTracker = formDataAvailable
        isAvailable = true
        displayToProfile(formDataAvailable, isAvailable) //process the retrieval of post for archieved post
    })

    let offsetCommunity = 0
    let lengthRetrieved = 0;
    //community post tab
    $('#communityLi').on('click', function () {
        //load the data for community after the click
        offsetCommunity = 0
        $('#communityContainer').empty();
        $('#communityCollege').val('')
        $('#communityReportFilter').val('')
        getCommunityPost();
        getReport()
    })


    //get community post
    function getCommunityPost() {
        let action = {
            action: 'readAllPost'
        }
        let formData = new FormData()
        formData.append('action', JSON.stringify(action))
        formData.append('offset', offsetCommunity)
        //process retrieval
        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                if (response != 'failed') {
                    const data = JSON.parse(response)
                    if (data.response == 'Success') {
                        const length = data.colCode.length;
                        for (let i = 0; i < length; i++) {
                            const postID = data.postID[i]
                            const profilePic = (data.profilePic[i] == '') ? '../assets/icons/person.png' : imgFormat + data.profilePic[i]
                            const username = data.username[i]
                            const isLiked = data.isLiked[i];
                            const fullname = data.fullname[i]
                            const caption = data.caption[i]
                            const date = data.date[i]
                            const comment = data.comments[i];
                            const likes = data.likes[i];
                            const imagesObj = data.images[i];
                            const report = data.report[i];

                            displayCommunityPost(postID, profilePic, fullname, username, imagesObj, caption, date, likes, comment, isLiked, report)
                        }
                        offsetCommunity += length
                        lengthRetrieved = length;
                    }
                    else {
                        $("#noPostMsgCommunity").removeClass('hidden')
                            .appendTo('#communityContainer')
                    }
                }
                else {
                    $("#noPostMsgCommunity").removeClass('hidden')
                        .appendTo('#communityContainer')
                }

            },

        })
    }

    function displayCommunityPost(postID, imgProfile, fullname, username, images, caption, date, likes, comments, isLikedByUser, report) {
        const feedContainer = $('#communityContainer')
        const container = $('<div>').addClass('communityPost flex gap-1')
        const postWrapper = $('<div>').addClass('communityWrapper rounded-md center-shadow p-4');

        //adding details for header
        let header = $('<div>');
        let headerWrapper = $('<div>').addClass("flex gap-2 items-center");
        let img = imgProfile;
        let userProfile = $('<img>').addClass("h-10 w-10 rounded-full").attr('src', img);
        let authorDetails = $('<div>').addClass("flex-1");
        let fullnameElement = $('<p>').addClass("font-bold text-greyish_black").text(fullname);
        let usernameElement = $('<p>').addClass("text-gray-400 text-xs").text(username);

        //set up the header to be displayed
        authorDetails.append(fullnameElement, usernameElement)
        headerWrapper.append(userProfile, authorDetails)
        header.append(headerWrapper)

        //for body
        let description = $('<p>').addClass('text-sm text-gray-500 my-2').text(caption);
        let swiperContainer = null;
        //check if post is status only or with image
        if (images.length > 0) {
            //set up the swiper
            swiperContainer = $("<div>").addClass("swiper communitySwiper relative  w-full h-80 bg-black rounded-md cursor-pointer overflow-x-hidden")
            const swiperWrapper = $('<div>').addClass('swiper-wrapper h-full')

            //carousel the image
            images.forEach(value => {
                let postImg = imgFormat + value;
                //create slider
                const swiperSlider = $('<div>').addClass('swiper-slide h-full flex justify-center')
                const swiperImg = $('<img>')
                    .addClass('object-contain h-full w-full bg-black')
                    .attr('src', postImg)

                //slider to wrapper
                swiperSlider.append(swiperImg)
                swiperWrapper.append(swiperSlider);
            })
            // Navigation buttons
            let pagination = $('<div>').addClass("swiper-pagination");
            let prevBtn = $('<div>').addClass("swiper-button-prev");
            let nextBtn = $('<div>').addClass("swiper-button-next ");
            swiperContainer.append(swiperWrapper, pagination, prevBtn, nextBtn);

            swiperContainer.on('click', function (event) {
                // Check if the click event is coming from the navigation buttons
                if (!$(event.target).hasClass('swiper-button-prev') && !$(event.target).hasClass('swiper-button-next')) {
                    $('#modalPost').removeClass("hidden");
                    viewingOfPost1(postID, fullname, username, caption, images, likes, img);
                }
            });


            // Initialize Swiper with pagination
            new Swiper('.communitySwiper', {
                pagination: {
                    el: '.swiper-pagination',
                },
            });

            if (images.length === 1)
                swiperContainer.find('.swiper-button-prev, .swiper-button-next').hide();


        } else {
            postWrapper.css('min-height', '155px')
                .on('click', function (e) {
                    const target = e.target

                    if (!deleteElement.is(target) && deleteElement.has(target).length === 0 &&
                        !heartIcon.is(target) && heartIcon.has(target).length === 0 &&
                        !commentIcon.is(target) && commentIcon.has(target).length === 0)
                        viewStatusPost(postID, fullname, date, caption, likes, username)

                })
        }


        date = getFormattedDate(date)
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
            .on('click', function () {
                //toggle like button
                if (isLiked) {
                    //decrease the current total number of likes by 1
                    newlyAddedLike -= 1
                    console.log(newlyAddedLike)
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
            .on('click', function () {
                $('#commentPost').removeClass('hidden') //open the comment modal

                //set up the details for comment to be display
                $('#postProfile').attr('src', img)
                $('#postFullname').text(fullname)
                $('#postUsername').text(username)
                $('#replyToUsername').text(username)

                //insert a comment to database
                $('#commentBtn').on('click', function () {
                    let commentVal = $('#commentArea').val()
                    insertComment(postID, commentVal, commentElement)
                })
            })
        let likesElement = $('<p>').addClass('text-xs text-gray-500').text(likes)
        let commentElement = $('<p>').addClass('text-xs text-gray-500 comment').text(comments)
        let leftContainer = $('<div>').addClass('flex gap-2 items-center').append(heartIcon, likesElement, commentIcon, commentElement)
        let deleteElement = $('<p>')
            .addClass('text-xs text-red-400 cursor-pointer ')
            .text('Archive post')
            .on('click', function () {
                //open the delete prompt
                $('.deleteModalPost').removeClass('hidden')
                //update the post status into deleted
                $('#deleteByAdminBtn').on('click', function () {
                    const reasonForDel = $('#reasonForDel').val();
                    if (reasonForDel !== '') removePostByAdmin(postID, username, reasonForDel)

                })
            })


        //attach all details to a postwrapper and to the root
        interactionContainer.append(leftContainer, deleteElement)

        //report
        reportWrapper = $('<div>').addClass('flex flex-col gap-2 text-xs flex-wrap text-gray-400 py-2 text-end')
        let nudityCount = 0
        let violenceCount = 0
        let terrorismCount = 0
        let speechCount = 0
        let falseInfoCount = 0
        let suicideCount = 0
        let harassmentCount = 0

        if (report.response !== "Nothing") {
            const data = JSON.parse(report);
            data.report.forEach(function (value, index) {
                switch (value) {
                    case 'Nudity':
                        nudityCount++;
                        break;
                    case 'Violence':
                        violenceCount++;
                        break;
                    case 'Terrorism':
                        terrorismCount++;
                        break;
                    case 'Hate Speech':
                        speechCount++;
                        break;
                    case 'False Information':
                        falseInfoCount++;
                        break;
                    case 'Suicide or self-injury':
                        suicideCount++;
                        break;
                    case 'Harassment':
                        harassmentCount++;
                        break;
                }
            });

            spanNudity = $('<span>').text(nudityCount + ' Nudity')
            spanViolence = $('<span>').text(violenceCount + ' Violence')
            spanTerror = $('<span>').text(terrorismCount + ' Terrorism')
            spanHS = $('<span>').text(falseInfoCount + ' Hate Speech')
            spanFalseInfo = $('<span>').text(suicideCount + ' False Info')
            spanHrass = $('<span>').text(harassmentCount + ' Harassment')
            spanSOS = $('<span>').text(speechCount + ' SOS')

            reportWrapper.append(spanNudity, spanViolence, spanTerror
                , spanHS, spanFalseInfo, spanHrass, spanSOS);
        }
        postWrapper.append(header, description, swiperContainer, date_posted, interactionContainer)
        container.append(postWrapper, reportWrapper)
        feedContainer.append(container);

        var percent = feedContainer.width() * 0.80;
        postWrapper.width(percent);
    }


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


    function displayPostPrompt(message) {
        $('#promptMsgComment').removeClass('hidden')//display the prompt
            .text(message)

        //hide again after 4 seconds
        setTimeout(() => {
            $('#promptMsgComment').addClass('hidden')
        }, 4000)
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
                    $('#commentPost').addClass('hidden') //hide modal
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

    $('.cancelDeletionAdmin').on('click', function () {
        $('.deleteModalPost').addClass('hidden')
    })

    function removePostByAdmin(postID, username, reason) {
        let action = { action: 'removePostByAlumAdmin' };
        const formdata = new FormData()
        formdata.append('action', JSON.stringify(action));
        formdata.append('postID', postID);
        formdata.append('username', username);
        formdata.append('reason', reason);

        //process the deletion
        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            success: response => {
                console.log(response);
                if (response == 'Success') {
                    // //close the modal
                    $('.deleteModalPost').addClass('hidden')
                    offsetCommunity = 0
                    lengthRetrieved = 0;
                    const feedContainer = $('#communityContainer')
                    feedContainer.find('.communityPost').remove()
                    getCommunityPost()
                }

            }

        })
    }
    $('#communityContainer').on('scroll', function () {
        var container = $(this);
        var scrollPosition = container.scrollTop();
        var containerHeight = container.height();
        var contentHeight = container[0].scrollHeight;
        var scrollThreshold = 70; // Adjust this threshold as needed

        if (scrollPosition + containerHeight >= contentHeight - scrollThreshold && lengthRetrieved == 10) {
            getCommunityPost()
        }
    });

    //get report graph
    function getReport() {
        let action = {
            action: 'displayReportData'
        }
        let formData = new FormData();
        formData.append('action', JSON.stringify(action))

        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                const nudity = response.nudity;
                const violence = response.violence;
                const Terrorism = response.Terrorism;
                const HateSpeech = response.HateSpeech;
                const FalseInformation = response.FalseInformation;
                const SOS = response.SOS;
                const harassment = response.harassment;

                //show data in pie chart format
                typeChart = 'pie'
                labels = ['Nudity', 'Violence', 'Terrorism', 'Hate Speech', 'False Information',
                    'Suicide or self-injury', 'Harassment']
                data = [nudity, violence, Terrorism, HateSpeech, FalseInformation, SOS, harassment]
                backgroundColors = ['#FFDAB9', '#8B0000', '#555555', '#800080', '#D3D3D3', '#008080', '#FF8C00']
                updateChartReport(labels, data, typeChart, backgroundColors)

            }
        })
    }

    let colCodeFilter = "";
    let reportCatFilter = "";

    $('#communityCollege').on('change', function () {
        //restart again the display
        $('#communityContainer').empty();
        offsetCommunity = 0;

        colCodeFilter = $(this).val()
        getCommunityByFilter() //retrieve data with filter
    })

    $('#communityReportFilter').on('change', function () {
        //restart again the display
        $('#communityContainer').empty();
        offsetCommunity = 0;

        reportCatFilter = $(this).val()
        getCommunityByFilter() //retrieve data with filter
    })

    //filtering community post based on college and report
    function getCommunityByFilter() {
        let action = {
            action: 'readColReport'
        }
        let formData = new FormData()
        formData.append('action', JSON.stringify(action))
        formData.append('offset', offsetCommunity)
        formData.append('college', colCodeFilter)
        formData.append('report', reportCatFilter)

        $.ajax({
            url: '../PHP_process/postDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                if (response !== 'failed') {
                    const data = JSON.parse(response)
                    if (data.response == 'Success') {
                        let length = data.colCode.length;
                        for (let i = 0; i < length; i++) {

                            //data retrieved
                            const postID = data.postID[i]
                            const profilePic = imgFormat + data.profilePic[i]
                            const username = data.username[i]
                            const isLiked = data.isLiked[i];
                            const fullname = data.fullname[i]
                            const caption = data.caption[i]
                            const date = data.date[i]
                            const comment = data.comments[i];
                            const likes = data.likes[i];
                            const imagesObj = data.images[i];
                            const report = data.report[i];

                            //display the mark up for post and report
                            displayCommunityPost(postID, profilePic, fullname,
                                username, imagesObj, caption, date, likes, comment,
                                isLiked, report)
                        }
                        offsetCommunity += length
                        lengthRetrieved = length
                    }
                    else {
                        $("#noPostMsgCommunity").removeClass('hidden')
                            .appendTo('#communityContainer')
                    }
                }
                else {
                    $("#noPostMsgCommunity").removeClass('hidden')
                        .appendTo('#communityContainer')
                }

                if (colCodeFilter !== "" || reportCatFilter !== "")
                    getFilteredReportCount(reportCatFilter, colCodeFilter)
                else getReport()
            },

        })
    }

    function reportTrendFiltered(data, reportCategory) {
        const labels = ['January', 'February', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December']
        const typeChart = 'line'
        const backgroundColor = '#991b1b'
        const label = reportCategory + " Report"
        updateChartReport(labels, data, typeChart, backgroundColor, label)

    }

    function getFilteredReportCount(reportFilter, colCode) {
        const action = "retrieveCountReportFiltered";
        const form = new FormData();
        form.append('action', action);
        form.append('filter', reportFilter);
        form.append('colCode', colCode)

        $.ajax({
            url: '../PHP_process/reportData.php',
            method: 'POST',
            data: form,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                let data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; //default value
                if (response.response === 'Success') {
                    let length = response.month.length;

                    for (let i = 0; i < length; i++) {
                        let value = response.month[i]
                        let reportCount = response.reportCount[i];
                        // check if the value contains 0 in the beginning to be remove
                        if (value[0] === '0') value = value.substr(1)

                        value -= 1 //decrease by one to match the indexing in the array
                        data[value] = reportCount
                    }

                }

                reportTrendFiltered(data, reportFilter) //display the new chart
            }

        })
    }

    //show data in pie chart format
    const chart = $('#reportChart');
    let currentReportChart = new Chart(chart, {
        type: 'pie',
        data: {
            labels: [], // Set initial labels
            datasets: [{
                data: [], // Set initial data
                backgroundColor: [], // Set initial background colors
                borderWidth: 1,
                borderWidth: 1,
                tension: 0.1,
            }]
        },
        options: {
            responsive: true,
        }
    });


    function updateChartReport(labels, values, typeChart, colors, label) {
        // changing the chart 
        currentReportChart.data.labels = labels;
        currentReportChart.data.datasets[0].data = values;
        currentReportChart.config.type = typeChart; //type of chart
        currentReportChart.data.datasets[0].backgroundColor = colors
        currentReportChart.data.datasets[0].label = label
        currentReportChart.data.datasets[0].borderColor = colors

        if (typeChart !== 'pie') {
            currentReportChart.options.scales = {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, // Specify the step size for Y-axis values
                    }
                }
            }
        }
        else {
            currentReportChart.options.scales = {
                y: {
                    display: false
                } // Set Y-axis scale to an empty object to effectively remove it
            };
        }

        currentReportChart.update() //refresh the chart
    }
})

