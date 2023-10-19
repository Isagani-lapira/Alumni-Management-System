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
        var filename = file.name
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
            else $('#errorMsgEM').removeClass('hidden').text(filename + ' file size greater than 1mb')
        }
        else
            $('#errorMsgEM').removeClass('hidden').text('Sorry we only allow images that has file extension of jpg, jpeg, png') //if the file is not based on the img extension we looking for


    })

    $('#fileSelection').on('change', function () {

        //file input
        var fileInput = $('#fileSelection')
        var file = fileInput[0].files[0] //get the first file that being select
        var nameOfFile = file.name
        if (file.size <= 5 * 1024 * 1024) {
            $('#errorMsgEM').addClass('hidden') // hide the message
            selectedFileEM.push(file);
            //preview of the file
            fileContainerPrev = $('<div>').addClass('flex justify-evenly item-center')
            fileName = $('<p>').addClass('p-1 w-full text-xs').text(nameOfFile)
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
        else $('#errorMsgEM').removeClass('hidden').text(nameOfFile + ' file size greater than 5mb')

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

            if (college === null) {
                $('.selectColWrapper').removeClass('border-gray-400').addClass('border-accent')
                return //for avoiding unselected college
            }
            else $('.selectColWrapper').addClass('border-gray-400').removeClass('border-accent')

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

        // submit email
        if (checkerInput(emailSubj, message)) {
            $('#loadingScreen').removeClass('hidden') //open loading animation
            $.ajax({
                url: '../PHP_process/sendEmail.php',
                type: 'POST',
                data: formSend,
                processData: false,
                contentType: false,
                success: (response) => {
                    $('#loadingScreen').addClass('hidden')
                    if (response == 'user is not existing')
                        $('#userNotExist').show()
                    else {
                        $('#promptMsg').removeClass('hidden')
                        // restart email
                        emailOffset = 0;
                        countNextEmail = 0;
                        tempOffsetEmail = 0;
                        //retrieve emails
                        getEmailSent(actionDefault)
                        actionTracker = actionDefault
                        //success sending
                        $('#message').text('Email sent!')
                        $('#userNotExist').hide()
                        $('#modalEmail').addClass('hidden')
                        setTimeout(() => {
                            $('#promptMsg').addClass('hidden')
                        }, 4000)
                    }
                },
                error: (error) => console.log(error)
            })
        }


    })

    $('#btnEmail').on('click', function () {
        $('#emailForm')[0].reset() // restart everything
        $("#modalEmail").removeClass('hidden')
    })

    let emailOffset = 0;
    let tempOffsetEmail = 0;
    let countNextEmail = 0;

    const actionByFilter = "retrieveByFilter"
    const actionDefault = "retrieveEmails"
    let colCodeTracker = ""
    let actionTracker = ""
    $('#emailLi').on('click', function () {
        emailOffset = 0;
        countNextEmail = 0;
        tempOffsetEmail = 0;
        //retrieve emails
        $('#newsAndUpdate-tab').removeClass('hidden')
        getEmailSent(actionDefault)
        actionTracker = actionDefault
    })


    function checkerInput(emailSubj, message) {
        let isComplete = true
        // check first if the fields are complete
        if (emailSubj === '') {
            $('#emailSubj').removeClass('border-gray-400').addClass('border-accent')
            isComplete = false

        } else $('#emailSubj').addClass('border-gray-400').removeClass('border-accent')

        if (message === '') {
            $('.modal-descript').removeClass('border-gray-400').addClass('border-accent')
            isComplete = false

        } else $('.modal-descript').addClass('border-gray-400').removeClass('border-accent')  // back to normal, remove error red indicator


        return isComplete
    }

    function getEmailSent(actionData, colCode = "") {
        //perform ajax operation 
        const action = { action: actionData };
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('offset', emailOffset);
        formData.append('colCode', colCode);

        $.ajax({
            url: '../PHP_process/emailDB.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: response => {
                $('#emailTBody').empty(); //remove the data previously retrieved
                //check for the data retrieved
                if (response.result == "Success") {
                    const length = response.recipient.length;

                    for (let i = 0; i < length; i++) {
                        const recipient = response.recipient[i];
                        const colCode = response.colCode[i];
                        const dateSent = response.dateSent[i];

                        //row data
                        const row = $('<tr>')
                        const recip = $("<td>").text(recipient)
                            .addClass('text-start')
                        const college = $("<td>").text(colCode)
                            .addClass('text-start')
                        const date = $("<td>").text(dateSent)
                            .addClass('text-start')

                        row.append(recip, college, date);

                        $('#emailTBody').append(row)
                    }
                    emailOffset += length
                    tempOffsetEmail = length
                    //disable next if the length didn't rich 10
                    if (length < 10) $('#nextEmail').addClass('hidden')
                }
            },
            error: error => { console.log(error) }
        })
    }


    //previous sets of email
    $('#prevEmail').on('click', function () {
        countNextEmail -= countNextEmail
        emailOffset = countNextEmail
        if (countNextEmail >= 0) {
            countNextEmail -= tempOffsetEmail
            $('#nextEmail').removeClass('hidden')
            getEmailSent(actionTracker, colCodeTracker)
        }
    })

    //retrieve new sets of emails
    $('#nextEmail').on('click', function () {
        countNextEmail += tempOffsetEmail
        getEmailSent(actionTracker, colCodeTracker)
        $('#prevEmail').removeClass('hidden')
    })

    $('#emCol').on('change', function () {
        // restart email counting
        emailOffset = 0;
        countNextEmail = 0;
        tempOffsetEmail = 0;
        let colCode = $(this).val();

        if (colCode == '') {
            getEmailSent(actionDefault)
            actionTracker = actionDefault
            colCodeTracker = ""
        }
        else {
            getEmailSent(actionByFilter, colCode) //filter data
            actionTracker = actionByFilter
            colCodeTracker = colCode
        }
    })



    //close modal email
    $(".cancelEmail").click(function () {
        $("#modalEmail").addClass('hidden')
    });
})