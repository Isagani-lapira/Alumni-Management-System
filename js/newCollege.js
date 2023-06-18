let inputVal = []
$(document).ready(function () {
    $("#btnCancelToCollege").click(function () {
        window.location.href = "../admin/admin.php"
    })

    //get logo that have been chosen
    $('#collegeLogo').change(function () {
        changeLogo()
    })

    $('#imgAddLogo').click(function () {
        changeLogo()
        $('.lblLogo').click()
    })

    //add the chosen logo
    function changeLogo() {
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
                $('#imgAddLogo').attr('src', e.target.result)
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

})

function reviewDetails(value) {

    let index = 1;
    const fields = document.querySelectorAll('.answer')
    fields.forEach((element) => {
        element.innerHTML = value[index]
        index++
    })
}
