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

    //make a post
    $('#postBtn').on('click', function () {
        let caption = $('#TxtAreaAnnouncement').val();
        let college = $('#collegePost').val();

        if (college == "all") {
            $('#collegePost option').slice(1).each(function () { // to skip the index 0 (all)
                const collegeVal = $(this).val();
                postInsertion(caption, collegeVal);
            })
        }
        else {
            postInsertion(caption, college);
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
                console.log(response)
                $('#modal').hide();
                $('#promptMsg').removeClass('hidden')
                $('#message').text('Announcement successfully posted!')
                setTimeout(() => {
                    $('#promptMsg').addClass('hidden')
                }, 4000)
                selectedFiles = [];
            },
            error: (error) => { console.log(error) }
        })
    }

    let postAction = {
        action: 'read',
    }
    let offsetPost = 0;
    let tempCount = 0;
    let postData = new FormData();
    postData.append('action', JSON.stringify(postAction));
    postData.append('startDate', "")
    postData.append('endDate', "")
    postData.append('offset', offsetPost)

    $('#announcementLI').on('click', function () {
        getPostAdmin(postData)
    })

    let totalPostCount = $('#totalPosted').html();
    $('.totalPost').text(totalPostCount)
    let toAddProfile = true;
    // //show post of admin
    function getPostAdmin(data) {
        $('#postTBody').empty()
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
                        data.response[i]
                        let postID = data.postID[i]
                        let collegeCode = data.colCode[i]
                        let caption = data.caption[i]
                        let date = data.date[i]
                        let comment = data.comments[i];
                        let likes = data.likes[i];
                        date = textDateFormat(date) //change to text format
                        let imagesObj = data.images;
                        let containerAnn = 'announcementCont'
                        let containerProfile = 'profileCont'
                        addPost(postID, fullname, username, caption, imagesObj, date, i, likes, comment, containerAnn, collegeCode) //add post in table;
                        addPost(postID, fullname, username, caption, imagesObj, date, i, likes, comment, containerProfile, null) //add post in profile;
                    }
                    toAddProfile = false //won't be affected by date range 
                    offsetPost += length
                    tempCount = length
                }
                else {
                    $('#noPostMsg').show();
                    //disable the next button
                    $('#nextPost').attr('disabled', true)
                        .addClass('hidden')
                }

            },
            error: (error) => { console.log(error) }
        })
    }

    //show next set of post
    $('#nextPost').on('click', function () {
        postData.delete('offset')
        postData.append('offset', offsetPost);
        getPostAdmin(postData)
    })

    //show prev set of post
    $('#prevPost').on('click', function () {
        //check if it still not 0
        if (tempCount != 0) {
            offsetPost = tempCount
            postData.delete('offset');
            postData.append('offset', offsetPost); //set new offset that is increase by 10
            getPostAdmin(postData); //retrieve new sets of data

            //enable the next button
            $('#nextPost').removeAttr('disabled')
                .removeClass('hidden')
        }

    })

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

    function addPost(postID, name, accUN, postcaption, images, postdate, position, likes, comments, container, colCode) {

        if (container == "announcementCont")
            announcementTbDisplay(postID, colCode, name, accUN, postcaption, images, postdate, position, comments, likes)
        else if (container == "profileCont" && toAddProfile) {
            postDisplay(name, accUN, postcaption, images, postdate, position, comments, likes)
        }

    }

    function announcementTbDisplay(postID, colCode, name, accUN, postcaption, images, postdate, position, comments, likes) {
        let tbody = $('#postTBody')

        //create of rows
        let row = $('<tr>')
        let colCodeData = $('<td>').text(colCode);
        let likesData = $('<td>').text(likes);
        let commentsData = $('<td>').text(comments);
        let postdateData = $('<td>').text(postdate);
        let action = $('<td>').addClass('flex justify-center gap-2')

        let delBtn = $('<button>').addClass(' text-sm text-red-400 rounded-lg p-1 hover:bg-red-400 hover:text-white').text('Delete')
        let viewBtn = $('<button>').addClass('bg-blue-400 text-sm text-white rounded-lg py-1 px-2 hover:bg-blue-500').text('View')
        viewBtn.on('click', function () {
            $('#modalPost').removeClass('hidden')
            viewingOfPost(postID, name, accUN, postcaption, images, position, likes)
        })
        action.append(delBtn, viewBtn)
        row.append(colCodeData, likesData, commentsData, postdateData, action)
        tbody.append(row)

    }

    //all post to be displayed on the profile tab
    function postDisplay(name, accUN, postcaption, images, postdate, position, comments, likes) {
        containerPost = $('<div>').addClass("shadow-sm shadow-gray-600 w-3/4 rounded-md p-3 h-max mt-10")
        toBeAppend = '#profileContainer'
        let header = $('<div>').addClass("flex items-center")
        let avatar = $('<img>').addClass("profilePic rounded-full h-10 w-10")
        let containerNames = $('<div>').addClass("px-3")
        let userFN = $('<p>').addClass("font-semibold").text(name)
        let username = $('<p>').addClass("text-sm text-gray-500").text(accUN)
        containerNames.append(userFN, username);
        avatar.attr('src', profilePic)

        let caption = $('<p>').addClass("font-light text-gray-600 text-sm mt-5").text(postcaption) //post caption
        let imgContainer = $('<div>').addClass("imgContainer flex flex-wrap gap-2 mt-3")

        // Retrieve and display all the images and add it to the current position/post
        images[position].forEach((image) => {
            let imageData = imgFormat + image;
            let img = $('<img>').addClass("flex-1 h-44 w-36 rounded-md object-contain bg-gray-300").attr('src', imageData);
            imgContainer.append(img);
        });
        let date = $('<p>').addClass("text-xs text-gray-600 p-2").text(postdate)
        let footerContainer = $('<div>').addClass("flex mt-3 gap-2 px-3")

        //check if the comment is 0 or greater than 0
        comments = (comments == 0) ? "No" : comments;
        let comment = $('<p>').addClass("text-gray-500 text-sm flex-1 cursor-pointer").text(comments + " comment")
        let share = $('<i>').addClass("fa-solid fa-share text-accent cursor-pointer")
        let like = $('<i>').addClass("fa-regular fa-heart text-accent cursor-pointer").text(likes)

        header.append(avatar, containerNames)
        footerContainer.append(comment, share, like);

        containerPost.append(header, caption, imgContainer, date, footerContainer)
        $(toBeAppend).append(containerPost)

    }

    function viewingOfPost(postID, name, accUN, description, images, position, likes) {
        $('#profilePic').attr('src', profilePic);
        $('#postFullName').text(name);
        $('#postUN').text(accUN);
        $('#noOfLikes').text(likes);
        $('#postDescript').text(description).addClass('text-sm my-2 text-gray-400');

        const carouselWrapper = $('#carousel-wrapper');
        const carouselIndicators = $('#carousel-indicators');

        let totalImgNo = images[position].length;
        images[position].forEach((image, index) => {
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




    //close the post modal view
    $('#closePostModal').on('click', function () {
        $('#modalPost').addClass('hidden')
        $('#carousel-wrapper').empty()
        $("#carousel-indicators").empty();
    })


    //get today's date
    const datePicker = new Date()
    const thisyear = datePicker.getFullYear();
    const thismonth = datePicker.getMonth() + 1;
    const thisday = datePicker.getDate();
    let defaultStart = thismonth + '/' + thisday + '/' + thisyear
    let defaultEnd = thismonth + 1 + '/' + thisday + '/' + thisyear


    $(function () {
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            startDate: defaultStart,
            endDate: defaultEnd
        }, function (start, end, label) {
            let startDate = start.format('YYYY-MM-DD')
            let endDate = end.format('YYYY-MM-DD')
            let formData = new FormData();
            formData.append('action', JSON.stringify(postAction))
            formData.append('startDate', startDate)
            formData.append('endDate', endDate)
            formData.append('offset', offsetPost)
            getPostAdmin(formData)
            $('#paginationBtnPost').addClass('hidden')
        });
    });


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
                    let noCommentMsg = $('<p>').addClass('text-gray-500').text('No available comment')
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
