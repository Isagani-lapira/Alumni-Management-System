$(document).ready(function () {


    let currentPage = 1
    const enabledBtn = "bg-blue-400 hover:bg-blue-500"
    const disabledBtn = "bg-blue-300"
    function enabledTheNext() {
        $('#resumeBtnNext')
            .removeClass(disabledBtn)
            .addClass(enabledBtn)
            .attr('disabled', false)
    }

    function disabledTheNext() {
        $('#resumeBtnNext')
            .attr('disabled', true)
            .addClass('bg-blue-300')
            .removeClass('bg-blue-400 hover:bg-blue-500')
    }

    $('#resumeBtnNext').on('click', function () {
        if (currentPage == 1) {
            // personal information page
            const className = '.personalInput'
            if (isInputComplete(className)) {
                currentPage++
                navigateToPage(currentPage)
            }
        }
        else if (currentPage == 2) {
            //academic background page
            const className = '.academicBgInput'
            if (isInputComplete(className)) {
                currentPage++
                enabledTheNext()
                navigateToPage(currentPage)
            }
        }
        else if (currentPage == 3) {
            //work experience
            currentPage++
            navigateToPage(currentPage)
        }
        else if (currentPage == 4) {
            // skill page
            let skillCount = 0;
            $('.skillInput').each(function () {
                let value = $(this).val();

                if (value !== "") {
                    skillCount++
                }
            })
            //check if it reach the minimum of 2 skill
            if (skillCount > 1) {
                currentPage++
                navigateToPage(currentPage)
            }
        }
    })

    $('#resumeBtnPrev').on('click', function () {
        currentPage--
        $('#resumeBtnNext')
            .addClass('bg-accent hover:bg-blue-500')
            .removeClass('bg-green-400 hover:bg-green-500')
            .text('Next')
        navigateToPage(currentPage)

    })

    //change the page base on what the he's current page
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
                disabledTheNext()
                break;
            case 4:
                $('#skillPage').removeClass('hidden')
                enabledTheNext()
                break;
            case 5:
                $('#resumeSummary').removeClass('hidden')
                //change the button to success button
                $('#resumeBtnNext')
                    .attr('disabled', true)
                    .addClass('bg-green-300')
                    .removeClass('bg-blue-400 hover:bg-blue-500')
                    .text('Update resume')
                break;
        }

        //check if the prev button should be visible
        isHidden = (page > 1) ? true : false;

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
                element.addClass('border border-red-400')
            } else element.removeClass('border border-red-400')
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

    $('#addWorkExp2').on('click', function () {
        let classPage = ".firstWork"
        if (isInputComplete(classPage)) {
            showWorkInputField($(this))
            $('#addWorkExp3').removeClass('hidden') //show the next add icon
            enabledTheNext()
        } else {
            disabledTheNext()
        }

    })

    // third work experience
    $('#addWorkExp3').on('click', function () {
        let classPage = ".secondWork"
        if (isInputComplete(classPage)) {
            console.log('pumasok')
            showWorkInputField($(this))
            $('#addWorkExp4').removeClass('hidden') //show the next add icon
            enabledTheNext()
        } else disabledTheNext() //if the input is incomplete then it dont allow to be next
    })
    $('#addWorkExp4').on('click', function () {
        let classPage = ".thirdWork"
        if (isInputComplete(classPage)) {
            showWorkInputField($(this))
            $('#addWorkExp4').removeClass('hidden') //show the next add icon
            enabledTheNext()
        } else disabledTheNext()//if the input is incomplete then it dont allow to be next
    })

    function showWorkInputField(element) {
        const targetParent = $(element).parent()

        //find all the input that is invisible
        const inputFields = targetParent.find('input, select');

        //traverse and show
        inputFields.removeClass('invisible')
    }

    $('#noExp').on('click', function () {
        $('#workExpWrapper').addClass('hidden')
        enabledTheNext() // allows to be next
    })
    $('#withExp').on('click', function () {
        $('#workExpWrapper').removeClass('hidden')
        disabledTheNext()
    })

    $('#objectiveInput').on('input', function () {
        let value = $(this).val()

        //allows the update resume button now
        if (value !== "") {
            //change button to update
            $('#resumeBtnNext')
                .attr('disabled', false)
                .addClass('bg-green-400 hover:bg-green-500')
                .removeClass('bg-green-300')
        }
    })
})