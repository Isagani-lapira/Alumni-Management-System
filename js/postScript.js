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
            selectedFiles.length = 0;
        }
        $('#TxtAreaAnnouncement').val('')
    })

    let imageSequence = 1;
    const selectedFiles = [];
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
            success: (response) => { console.log(response) },
            error: (error) => { console.log(error) }
        })
    })

    let postAction = {
        action: 'read',
    }
    let postData = new FormData();
    postData.append('action', JSON.stringify(postAction));

    // //show post of admin
    $.ajax({
        url: '../PHP_process/postDB.php',
        type: 'POST',
        data: postData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: (response) => {
            let data = response;
            if (data.response == 'Success') {
                let length = data.colCode.length;
                let username = data.username;
                for (let i = 0; i < length; i++) {
                    data.response[i]
                    let postID = data.postID[i]
                    data.colCode[i]
                    let caption = data.caption[i]
                    let date = data.date[i]
                    let imagesObj = data.images[0];

                    addPost(username, caption, imagesObj, date)
                }
            }
            else console.log('ayaw');

        },
        error: (error) => { console.log(error) }
    })


    function addPost($username, $caption, $images, $date) {
        let container = $('<div>').addClass("shadow-sm shadow-gray-600 w-1/2 rounded-md p-3 h-max mt-10")
        let header = $('<div>').addClass("flex items-center")
        let avatar = $('<img>').addClass("rounded-full h-10 w-10")
        let containerNames = $('<div>').addClass("px-3")
        let userFN = $('<p>').addClass("font-semibold")
        let username = $('<p>').addClass("text-sm text-gray-500").text($username)
        containerNames.append(userFN, username);

        let caption = $('<p>').addClass("font-light text-gray-600 text-sm mt-5").text($caption)

        let imgContainer = $('<div>').addClass("imgContainer flex flex-wrap gap-2 mt-3")

        //retrieve all the images
        for (let i = 0; i < $images.length; i++) {
            let imgFormat = 'data:image/jpeg;base64,' + $images[i];
            let img = $('<img>').addClass("flex-1 w-36 rounded-md").attr('src', imgFormat)
            imgContainer.append(img);
        }
        let date = $('<p>').addClass("text-xs text-gray-500 p-2").text($date)
        let footerContainer = $('<div>').addClass("flex mt-3 gap-2 px-3")
        let comments = $('<p>').addClass("text-gray-500 text-sm flex-1").text('comments')
        let share = $('<i>').addClass("fa-solid fa-share text-accent")
        let like = $('<i>').addClass("fa-regular fa-heart text-accent")

        header.append(avatar, containerNames)
        footerContainer.append(comments, share, like);

        container.append(header, caption, imgContainer, date, footerContainer)
        $('#postContainer').append(container)
    }
})
