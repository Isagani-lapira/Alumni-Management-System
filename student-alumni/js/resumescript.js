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

    const skills = [];
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
                    skills.push(value) //add to the array
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
        $('#resumeBtnNext').removeClass('hidden') //show next button
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
                $('#resumeBtnNext').addClass('hidden')
                //change the button to success button
                $('#resumeBtnUpdate').removeClass('hidden')
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

    let work = [];

    function getWork(selector) {
        const jobTitleInput = $(selector + '.job-title').val();
        const companyNameInput = $(selector + '.company-name').val();
        const startYearSelect = $(selector + '.year:nth-child(4)').val();
        const endYearSelect = $(selector + '.year:nth-child(5)').val();
        const year = startYearSelect + '-' + endYearSelect;
        // Create an object and push it to the work array
        work.push({
            jobTitle: jobTitleInput,
            companyName: companyNameInput,
            year: year
        });

    }
    $('#addWorkExp2').on('click', function () {
        let classPage = ".firstWork"
        if (isInputComplete(classPage)) {
            showWorkInputField($(this))
            $('#addWorkExp3').removeClass('hidden') //show the next add icon
            //get the data
            getWork(classPage)
            enabledTheNext()
        } else {
            disabledTheNext()
        }

    })

    // third work experience
    $('#addWorkExp3').on('click', function () {
        let classPage = ".secondWork"
        if (isInputComplete(classPage)) {
            showWorkInputField($(this))
            $('#addWorkExp4').removeClass('hidden') //show the next add icon
            //get the data
            getWork(classPage)
            enabledTheNext()
        } else disabledTheNext() //if the input is incomplete then it dont allow to be next
    })
    $('#addWorkExp4').on('click', function () {
        let classPage = ".thirdWork"
        if (isInputComplete(classPage)) {
            showWorkInputField($(this))
            $('#addWorkExp4').removeClass('hidden') //show the next add icon
            //get the data
            getWork(classPage)
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
            $('#resumeBtnUpdate')
                .attr('disabled', false)
                .addClass('bg-green-400 hover:bg-green-500')
                .removeClass('bg-green-300')
        }
    })

    let primaryEduc = [];
    let secondaryEduc = [];
    let tertiaryEduc = [];
    $('#resumeBtnUpdate').on('click', function () {
        //get primary education
        $('.primary').each(function () {
            var value = $(this).val()
            primaryEduc.push(value)
        })

        //get secondary education
        $('.secondary').each(function () {
            var value = $(this).val()
            secondaryEduc.push(value)
        })

        // get tertiary
        $('.tertiary').each(function () {
            var value = $(this).val()
            tertiaryEduc.push(value)
        })

        setResume()
    })
    function setResume() {
        let data = $('#formResume')[0];
        var formData = new FormData(data);

        //gather data that collected
        const action = "insertData"
        const firstname = $('#firstname').val()
        const lastname = $('#lastname').val()
        const address = $('#address').val()
        const contactNo = $('#contactNo').val()
        const emailAdd = $('#emailAdd').val()
        const objective = $('#objectiveInput').val()

        //data to be send
        formData.append('action', action)
        formData.append('firstname', firstname)
        formData.append('lastname', lastname)
        formData.append('address', address)
        formData.append('contactNo', contactNo)
        formData.append('emailAdd', emailAdd)
        formData.append('objective', objective)
        formData.append('primary', JSON.stringify(primaryEduc))
        formData.append('secondary', JSON.stringify(secondaryEduc))
        formData.append('tertiary', JSON.stringify(tertiaryEduc))
        formData.append('work', JSON.stringify(work));
        formData.append('skills', JSON.stringify(skills));

        $.ajax({
            url: '../PHP_process/resume.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => { console.log(response) },
            error: error => { console.log(error) }
        })
    }
})