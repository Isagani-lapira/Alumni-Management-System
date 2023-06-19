let inputVal = []
$(document).ready(function () {
    $("#btnCancelToCollege").click(function () {
        window.location.href = "../admin/admin.php"
    })

    //get logo that have been chosen
    $('#collegeLogo').change(function () {
        changeLogo('#imgAddLogo')
    })

    $('#imgAddLogo').click(function () {
        changeLogo('#imgAddLogo')
        $('.lblLogo').click()
    })

    //add the chosen logo
    function changeLogo(id) {
        const fileInput = $('#collegeLogo')
        const file = fileInput[0].files[0]
        const validExtension = ['jpg', 'jpeg', 'png']
        const fileExtension = file.name.split('.').pop().toLowerCase()

        //check if the file extension is valid
        if (validExtension.includes(fileExtension)) {
            //read the file name then get the url of it
            const read = new FileReader()

            read.onload = function (e) {
                $('#errorExtMsg').hide() // hide the error message for the extension
                $('#imgAddLogo').removeClass('hidden')
                $('.lblLogo').addClass('hidden')
                $(id).attr('src', e.target.result)
            }

            read.readAsDataURL(file)
        }
        else {
            $('#errorExtMsg').show()
        }

    }

    $('#btnBrowse').click(function () {
        $('.lblLogo').click()
    })

    // back to filling up the college form
    $('#btnBackFill').on('click', () => {
        $('#fillUpCol').show()
        $('#reviewCol').dhide()
    })


    $('#btntoReview').on('click', () => {
        console.log('rar')

        var allFieldsCompleted = true;
        //check if the field are completed
        $('input').each(function () {
            if (!$(this).val()) {
                $(this).removeClass('border-grayish').addClass('border-accent')
                allFieldsCompleted = false;
            }
            else {
                $(this).addClass('border-grayish').removeClass('border-accent')
                inputVal.push($(this).val())
            }
        });

        if (allFieldsCompleted) {
            //allows to proceed to the next level
            $('#fillUpCol').hide()
            $('#reviewCol').show()
            reviewDetails(inputVal)
        }
    })


    function reviewDetails(value) {
        let username, pass = ""
        let index = 1;
        const fields = document.querySelectorAll('.answer')
        fields.forEach((element) => {
            if (index == 6) {
                element.innerHTML = value[index] + ' ' + value[index + 1]
                username = document.getElementById('usernameVal').innerHTML = value[index] + value[index + 1] + 'BulSU-' + value[2]
                pass = document.getElementById('passwordVal').innerHTML = value[index] + value[index + 1] + 'BulSU-' + value[2]

                changeLogo("#chosenLogo")
                $('#chosenLogo').removeClass('hidden')
                index += 2
            }
            else {
                if (index == 13) element.innerHTML = $('input[name="gender"]:checked').val()
                else element.innerHTML = value[index]
                index++
            }

            console.log(index)
        })
    }

})


