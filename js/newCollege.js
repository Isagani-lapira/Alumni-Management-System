
$(document).ready(function () {
    $("#btnCancelToCollege").click(function () {
        window.location.href = "/admin/admin.html"
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

        //read the file name then get the url of it
        const read = new FileReader()

        read.onload = function (e) {
            $('#imgAddLogo').removeClass('hidden')
            $('.lblLogo').addClass('hidden')
            $('#imgAddLogo').attr('src', e.target.result)
        }

        read.readAsDataURL(file)
    }

})
