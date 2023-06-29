$(document).ready(function () {


    $('#loginForm').on('submit', (e) => {
        e.preventDefault();

        let allHaveVal = true;

        //traverse all the input available
        $('input').each(function () {

            let val = $(this).val()
            let field;
            field = ($(this).attr('name') === "password") ? $('.pass_details') : $(this); //look for password field

            //check if there's a value on a specific input
            if (val === "") {
                field.addClass('border-accent').removeClass('border-gray-400'); //add red border
                allHaveVal = false;

            } else field.removeClass('border-accent').addClass('border-gray-400');
        })

        //check if the the input have all value
        if (allHaveVal) {
            let formData = $('#loginForm')[0]; //get the form 
            let data = new FormData(formData); //the form we will send to the php file

            //action will be using
            let action = {
                action: 'read',
            }
            data.append('action', JSON.stringify(action))

            //perform ajax operation
            $.ajax({
                type: "POST",
                url: "../PHP_process/userData.php",
                data: data,
                processData: false,
                contentType: false,
                success: (response) => {
                    console.log(response);
                },
                error: (error) => console.log(error)
            });
        }
    })

});