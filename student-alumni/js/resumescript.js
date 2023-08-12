$(document).ready(function () {


    let currentPage = 1

    $('#resumeBtnNext').on('click', function () {
        if (currentPage == 1) {
            const className = '.personalInput'
            if (isInputComplete(className)) {
                currentPage++
                navigateToPage(currentPage)
            }
        }
        else if (currentPage == 2) {
            const rar = '.academicBgInput'
            if (isInputComplete(rar)) {
                currentPage++
                navigateToPage(currentPage)
            }
        }
    })

    $('#resumeBtnPrev').on('click', function () {
        currentPage--
        navigateToPage(currentPage)
    })

    function navigateToPage(page) {
        $('.resumePages').each(function () { $(this).addClass('hidden') })
        switch (page) {
            case 1:
                $('#personInfoPage').removeClass('hidden')
                break;
            case 2:
                $('#academicInfoPage').removeClass('hidden')
                break;
            case 3:
                $('#workExpPage').removeClass('hidden')
                break;
            case 4:
                $('#skillPage').removeClass('hidden')
                break;
        }

        //check if the prev button should be visible
        isHidden = (page > 1) ? true : false;
        console.log(isHidden);

        if (isHidden) $('#resumeBtnPrev').removeClass('hidden')
        else $('#resumeBtnPrev').addClass('hidden')

    }

    //check if the input are complete to be able to proceed
    function isInputComplete(classPage) {
        let isComplete = true
        $(classPage).each(function () {
            let value = $(this).val();
            let element = $(this)
            //return as soon as it see null in input
            if (value === "") {
                isComplete = false

                //add indication of no input inserted
                element.addClass('border-red-400')
            } else element.addClass('border-gray-400').removeClass('border-red-400')
        })

        return isComplete;
    }


    const date = new Date()
    let currentYear = parseInt(date.getFullYear())

    $('.yearSelection').each(function () {
        let target = $(this)
        //create a option up to 1945
        const maxYear = 1945;
        for (let i = currentYear; i > maxYear; i--) {
            //markeup for option
            let option = $('<option>').val(i).text(i)
            target.append(option)
        }
    })
})