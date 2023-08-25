const imgConEmail = document.getElementById('imgContEmail')
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
        while (imgConEmail.firstChild) {
            imgConEmail.removeChild(imgConEmail.firstChild)
            selectedImgEM = [];
        }
        $('#TxtAreaAnnouncement').val('')
    })

    let imageSequenceEM = 1;
    let selectedImgEM = [];
    let selectedFileEM = [];

    //add image to the modal
    $('#imageSelection').change(() => {

        $('#errorMsgEM').addClass('hidden') //always set the error message as hidden when changing the file

        //file input
        var fileInput = $('#imageSelection')
        var file = fileInput[0].files[0] //get the first file that being select

        fileExtension = file.name.split('.').pop().toLowerCase() //getting the extension of the selected file
        //checking if the file is based on the extension we looking for
        if (validExtension.includes(fileExtension)) {
            if (file.size <= 1024 * 1024) { //check file size if the image is 1mb
                var reader = new FileReader()
                selectedImgEM.push(file); // Store the selected file in the array
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
                    selectedImgEM.splice(index, 1); // Remove the file from the selectedImgEM array
                    parent.parentNode.removeChild(parent)
                })

                // img element
                imageElement.className = 'flex-shrink-0 h-20 w-20 rounded-md m-2'
                imageElement.setAttribute('id', 'reservedPicture' + imageSequenceEM) //to make sure every id is unique

                //add to its corresponding container
                imgPlaceHolder.appendChild(imageElement)
                imgPlaceHolder.appendChild(xBtn)
                imgConEmail.appendChild(imgPlaceHolder)

                //assign the image path to the img element
                reader.onload = function (e) {
                    $('#reservedPicture' + imageSequenceEM).attr('src', e.target.result)
                    $('#imgContEmail').removeClass('hidden')
                    $('#TxtAreaEmail').addClass('h-3/6').removeClass('h-5/6') //make the text area smaller in height
                    imageSequenceEM++
                }

                reader.readAsDataURL(file)
            }
            else $('#errorMsgEM').removeClass('hidden').text('File size maximum of 1mb')
        }
        else
            $('#errorMsgEM').removeClass('hidden').text('Sorry we only allow images that has file extension of jpg, jpeg, png') //if the file is not based on the img extension we looking for


    })

    $('#fileSelection').on('change', function () {

        //file input
        var fileInput = $('#fileSelection')
        var file = fileInput[0].files[0] //get the first file that being select

        if (file.size <= 5 * 1024 * 1024) {
            $('#errorMsgEM').addClass('hidden') // hide the message
            selectedFileEM.push(file);
            //preview of the file
            fileContainerPrev = $('<div>').addClass('flex justify-evenly item-center')
            fileName = $('<p>').addClass('p-1 w-full text-xs').text(file.name)
            xBtn = $('<span>').text('x').addClass('cursor-pointer')
                .on('click', function (e) {
                    var parent = e.target.parentNode
                    var index = Array.from(parent.parentNode.children).indexOf(parent); //get a specific index which picture has been remove
                    selectedFileEM.splice(index, 1); // Remove the file from the selectedImgEM array
                    parent.parentNode.removeChild(parent)
                })

            fileContainerPrev.append(fileName, xBtn)
            $('#fileContEmail').show().append(fileContainerPrev)
        }
        else $('#errorMsgEM').removeClass('hidden').text('File size maximum of 5mb')

    })

    //clicked the gallery icon
    $('#galleryIcon').on('click', function () {
        $('#imageSelection').click()
    })

    //clicked the file icon
    $('#fileIcon').on('click', function () {
        $('#fileSelection').click()
    })


    //send email
    $('#emailForm').on('submit', (e) => {

        e.preventDefault();
        //check the type of recipient
        let recipient = $('input[name="recipient"]:checked').val();
        let emailSubj = $('#emailSubj').val();
        let formSend = new FormData();

        if (recipient == 'individualEmail') {
            let searchEmail = $('#searchEmail').val()
            formSend.append('searchEmail', searchEmail)
        }
        else {
            let user = $('input[name="selectedUser"]:checked').val();
            let college = $('#selectColToEmail').val();
            formSend.append('college', college);
            formSend.append('user', user);
        }

        let message = $('#TxtAreaEmail').val();
        formSend.append('recipient', recipient)
        formSend.append('subject', emailSubj)
        formSend.append('message', message);

        // Append each file individually to the FormData object
        for (let i = 0; i < selectedImgEM.length; i++) {
            formSend.append('images[]', selectedImgEM[i]);
        }
        for (let i = 0; i < selectedFileEM.length; i++) {
            formSend.append('files[]', selectedFileEM[i]);
        }

        $.ajax({
            url: '../PHP_process/sendEmail.php',
            type: 'POST',
            data: formSend,
            processData: false,
            contentType: false,
            success: (response) => {
                $('#promptMsg').removeClass('hidden')
                $('#message').text('Sending email..')
                if (response == 'user is not existing')
                    $('#userNotExist').show()
                else {
                    //success sending
                    $('#message').text('Email sent!')
                    $('#userNotExist').hide()
                    $('#modalEmail').hide()
                    setTimeout(() => {
                        $('#promptMsg').addClass('hidden')
                    }, 4000)
                }
            },
            error: (error) => console.log(error)
        })
    })
})