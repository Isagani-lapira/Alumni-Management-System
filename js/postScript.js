const imgContPost = document.getElementById('imgContPost')

$(document).ready(function () {

    let validExtension = ['jpeg', 'jpg', 'png'] //only allowed extension
    let fileExtension
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
        let formData = new FormData();
        let caption = $('#TxtAreaAnnouncement').val();
        let college = $('#collegePost').val();

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
                $('#modal').hide();
                $('#promptMsg').removeClass('hidden')
                setTimeout(() => {
                    $('#promptMsg').addClass('hidden')
                }, 4000)
                selectedFiles = [];
            },
            error: (error) => { console.log(error) }
        })
    })

    let postAction = {
        action: 'read',
    }
    let postData = new FormData();
    postData.append('action', JSON.stringify(postAction));
    postData.append('startDate', "")
    postData.append('endDate', "")
    getPostAdmin(postData)

    let toAddProfile = true;
    // //show post of admin
    function getPostAdmin(data) {
        $('#postTBody').empty()
        $.ajax({
            url: '../PHP_process/postDB.php',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: (response) => {
                let data = response;
                if (data.response == 'Success') {
                    $('#noPostMsg').hide()
                    let length = data.colCode.length;

                    $('.totalPost').text(length); //total number of posted 

                    let username = data.username;
                    let fullname = "Isagani Lapira Jr."; //change base on the full name of the user
                    let avatar = ""; //change base on the avatar of the user
                    for (let i = 0; i < length; i++) {
                        data.response[i]
                        // let postID = data.postID
                        let collegeCode = data.colCode[i]
                        let caption = data.caption[i]
                        let date = data.date[i]
                        let comment = data.comments[i];
                        date = textDateFormat(date) //change to text format
                        let imagesObj = data.images;

                        let containerAnn = 'announcementCont'
                        let containerProfile = 'profileCont'
                        addPost(fullname, username, caption, imagesObj, date, i, comment, containerAnn, collegeCode) //add post in table;
                        addPost(fullname, username, caption, imagesObj, date, i, comment, containerProfile, null) //add post in profile;
                    }
                    toAddProfile = false //won't be affected by date range 
                }
                else $('#noPostMsg').show();

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

    function addPost(name, accUN, postcaption, images, postdate, position, comments, container, colCode) {

        if (container == "announcementCont")
            announcementTbDisplay(colCode, name, accUN, postcaption, images, postdate, position, comments)
        else if (container == "profileCont" && toAddProfile) {
            postDisplay(name, accUN, postcaption, images, postdate, position, comments)
        }



    }

    function announcementTbDisplay(colCode, name, accUN, postcaption, images, postdate, position, comments) {
        let tbody = $('#postTBody')

        //create of rows
        let row = $('<tr>')
        let colCodeData = $('<td>').text(colCode);
        let commentsData = $('<td>').text(comments);
        let postdateData = $('<td>').text(postdate);
        let action = $('<td>').addClass('flex justify-center gap-2')

        let delBtn = $('<button>').addClass(' text-sm text-red-400 rounded-lg p-1 hover:bg-red-400 hover:text-white').text('Delete')
        let viewBtn = $('<button>').addClass('bg-blue-400 text-sm text-white rounded-lg py-1 px-2 hover:bg-blue-500').text('View')
        viewBtn.on('click', function () {
            $('#modalPost').removeClass('hidden')
            viewingOfPost(name, accUN, postcaption, images, position)
        })
        action.append(delBtn, viewBtn)
        row.append(colCodeData, commentsData, postdateData, action)
        tbody.append(row)

    }
    function postDisplay(name, accUN, postcaption, images, postdate, position, comments) {
        containerPost = $('<div>').addClass("shadow-sm shadow-gray-600 w-3/4 rounded-md p-3 h-max mt-10")
        toBeAppend = '#profileContainer'
        let header = $('<div>').addClass("flex items-center")
        let avatar = $('<img>').addClass("rounded-full h-10 w-10 border-2 border-accent")
        let containerNames = $('<div>').addClass("px-3")
        let userFN = $('<p>').addClass("font-semibold").text(name)
        let username = $('<p>').addClass("text-sm text-gray-500").text(accUN)
        containerNames.append(userFN, username);

        let caption = $('<p>').addClass("font-light text-gray-600 text-sm mt-5").text(postcaption)

        let imgContainer = $('<div>').addClass("imgContainer flex flex-wrap gap-2 mt-3")

        // Retrieve and display all the images and add it to the current position/post
        images[position].forEach((image) => {
            let imgFormat = 'data:image/jpeg;base64,' + image;
            let img = $('<img>').addClass("flex-1 h-44 w-36 rounded-md").attr('src', imgFormat);
            imgContainer.append(img);
        });
        let date = $('<p>').addClass("text-xs text-gray-600 p-2").text(postdate)
        let footerContainer = $('<div>').addClass("flex mt-3 gap-2 px-3")

        //check if the comment is 0 or greater than 0
        comments = (comments == 0) ? "No" : comments;
        let comment = $('<p>').addClass("text-gray-500 text-sm flex-1 cursor-pointer").text(comments + " comment")
        let share = $('<i>').addClass("fa-solid fa-share text-accent cursor-pointer")
        let like = $('<i>').addClass("fa-regular fa-heart text-accent cursor-pointer")

        header.append(avatar, containerNames)
        footerContainer.append(comment, share, like);

        containerPost.append(header, caption, imgContainer, date, footerContainer)
        $(toBeAppend).append(containerPost)

    }

    function viewingOfPost(name, accUN, description, images, position) {
        $('#postFullName').text(name)
        $('#postUN').text(accUN)
        $('#postDescript').text(description)

        const carouselWrapper = $("#carousel-wrapper");
        const carouselIndicators = $("#carousel-indicators");

        let totalImgNo = images[position].length;
        images[position].forEach((image, index) => {

            let imageName = 'item-' + index
            const item = $("<div>")
                .addClass("hidden duration-700 ease-in-out")
                .attr("data-carousel-item", "")
                .attr('id', imageName);

            const format = 'data:image/jpeg;base64,' + image
            const img = $("<img>")
                .addClass("absolute block w-full h-full")
                .attr("src", format)
                .attr("alt", "Carousel Image");

            item.append(img);
            carouselWrapper.append(item);

            const indicator = $("<button>")
                .attr("type", "button")
                .addClass("w-3 h-3 rounded-full")
                .attr("aria-current", index === 0 ? "true" : "false")
                .attr("aria-label", "Slide " + (index + 1))
                .attr("data-carousel-slide-to", index.toString());

            carouselIndicators.append(indicator);
        })

        // Show the first image and update the indicator
        carouselWrapper.find("[data-carousel-item]").first().removeClass("hidden");
        carouselIndicators.find("[data-carousel-slide-to]").first().attr("aria-current", "true");


        let currentImageDisplay = 0;
        $('#btnNext').on('click', function () {
            currentImageDisplay = (currentImageDisplay == (totalImgNo - 1)) ? currentImageDisplay : currentImageDisplay + 1; //check first reach the end
            $('#item-' + currentImageDisplay).removeClass('hidden') //show the next image
        })
        $('#btnPrev').on('click', function () {
            if (currentImageDisplay != 0) //check if the image is the last image
                $('#item-' + currentImageDisplay).addClass('hidden')
            currentImageDisplay = (currentImageDisplay == 0) ? 0 : currentImageDisplay - 1
        })
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

            getPostAdmin(formData)
        });
    });

})
