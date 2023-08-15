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

    let skills = [];
    $('#resumeBtnNext').on('click', function () {
        if (currentPage == 1) {
            // personal information page
            const className = '.personalInput'
            // check if all input are completed
            if (isInputComplete(className)) {
                currentPage++
                navigateToPage(currentPage)
            }
        }
        else if (currentPage == 2) {
            //academic background page
            const className = '.academicBgInput'
            // check if all input are completed
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
        $('#resumeBtnUpdate').addClass('hidden') //hide the update button
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

    //cancel the resume modal
    $('#editResumeModal').on('click', function (e) {
        const target = e.target
        const wrapper = $('#resumeWrapper')

        if (!wrapper.is(target) && !wrapper.has(target).length) {
            restartResume()
        }
    })

    function restartResume() {
        $('#editResumeModal').addClass('hidden')

        primaryEduc = [];
        secondaryEduc = [];
        tertiaryEduc = [];
        work = []
        skills = []
        currentPage = 1;
    }


    //view resume
    $('#viewResumeBtn').on('click', function () {
        $('#viewResumeModal').removeClass('hidden')
        //retrieve and set the resume details
        getResumeData()
    })

    function getResumeData() {
        const action = "retrievalData"; //action to be perform
        const formData = new FormData();
        formData.append('action', action);

        $.ajax({
            url: '../PHP_process/resume.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: response => {
                if (response.response = "Success") {
                    //store the data that has been respond by the server
                    const objective = response.objective
                    const fullname = response.fullname
                    const contactNo = response.contactNo
                    const address = response.address
                    const emailAdd = response.emailAdd
                    const skills = response.skills
                    const education = response.education;
                    const workExp = response.workExp;
                    const references = response.references;
                    setResumeDetails(objective, fullname, contactNo, address, emailAdd, skills, education, workExp, references);
                }
            },
            error: error => { console.log(error) }
        })
    }

    function setResumeDetails(objective, fullname, contactNo, address, emailadd,
        skills, educations, workExp, references) {
        $('#fullnameResume').text(fullname)
        $('#contactNoResume').text(contactNo)
        $('#addressResume').text(address)
        $('#emailAddResume').text(emailadd)
        $('#objectiveResume').text(objective)
        const bulletIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 2048 2048"><path fill="#555" d="M1024 640q79 0 149 30t122 82t83 123t30 149q0 80-30 149t-82 122t-123 83t-149 30q-80 0-149-30t-122-82t-83-122t-30-150q0-79 30-149t82-122t122-83t150-30z"/></svg>';

        //display the skills
        skills.forEach(skill => {
            //mark up for resume skills
            const skillElement = $('<span>')
                .text(skill)

            const container = $('<span>').addClass('flex gap-4 items-center')
                .append(bulletIcon, skillElement)

            $("#skillWrapper").append(container); // append to the root container
        });

        //display education
        const educLength = educations.educationLevel.length
        for (let i = 0; i < educLength; i++) {
            //  <span class="font-thin">Bulacan State University (2018-2024)</span>\
            const educationLvl = educations.educationLevel[i];
            const university = educations.degree[i];
            const year = educations.year[i];

            //mark up for education
            const univElement = $('<span>').text(university)
            const yearElement = $('<span>').text(year);

            //append to wrapper and to root
            const primaryLvlWrapper = $('#primaryLvl')
            const secondaryLvlWrapper = $('#secondaryLvl')
            const tertiaryLvlWrapper = $('#tertiaryLvl')

            //check what level the education is
            if (educationLvl == "primary education") primaryLvlWrapper.append(univElement, yearElement)
            else if (educationLvl == "secondary education") secondaryLvlWrapper.append(univElement, yearElement)
            else if (educationLvl == "tertiary education") tertiaryLvlWrapper.append(univElement, yearElement)


        }

        // add work experience if there's any
        //check first if there's a value
        if (workExp !== null) {
            //set up the work experience

            const lengthWorkExp = workExp.jobTitle.length;
            for (let i = 0; i < lengthWorkExp; i++) {

                const jobTitle = workExp.jobTitle[i];
                const companyName = workExp.companyName[i];
                const workDescript = workExp.workDescript[i];
                const year = workExp.year[i];

                //mark up for work experience resume

                // job title
                const headerElement = $('<header>')
                    .addClass('font-bold')
                    .text(jobTitle);

                // company name
                const spanElement = $('<span>')
                    .text(companyName);


                // work description
                const workDescElement = $('<p>')
                    .addClass('text-justify')
                    .text(workDescript);

                // work year
                const yearElement = $('<span>')
                    .text('( ' + year + ' )')

                const wrapper = $('<div>')
                    .addClass('flex flex-col gap-1')
                    .append(headerElement, spanElement, workDescElement, yearElement)

                const liElement = $('<li>')
                    .attr('type', 'disc')
                    .append(wrapper)

                $('#workExpList').append(liElement)
            }


        }
        else console.log('ala')

        //add references
        const refLength = references.jobTitle.length;
        for (let i = 0; i < refLength; i++) {
            //data received
            const fullname = references.fullname[i];
            const jobTitle = references.jobTitle[i];
            const contactNo = references.contactNo[i];
            const emailAdd = references.emailAdd[i];

            //mark up for references
            const fullnameElement = $('<header>')
                .addClass('font-bold text-sm')
                .text(fullname);
            const jobTitleElement = $('<span>')
                .text(jobTitle);
            const contactNoElement = $('<span>')
                .text(contactNo);
            const emailElement = $('<span>')
                .text(emailAdd);

            //place in the wrapper then add it on the root
            const refWrapper = $('<div>')
                .addClass('flex flex-col')
                .append(fullnameElement, jobTitleElement,
                    contactNoElement, emailElement)

            $('#referenceContainer').append(refWrapper) //root
        }

        $("#referenceContainer").append()
    }

    $('#printResume').on('click', function () {
        const resumeWrapperModal = $('#resumeWrapperModal')
        const printWindow = window.open('', '_blank');

        const printContent = `
                <html>
                    <head>
                        <title>Print Resume</title>
                        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                    </head>
                    <body>
                        ${resumeWrapperModal.html()}
                        <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
                    </body>
                </html>`;

        // Set the content of the new window
        printWindow.document.open();
        printWindow.document.write(printContent);
        printWindow.document.close();


        // Wait for resources to load, then print
        printWindow.onload = function () {
            printWindow.print();
        };

    })

    $('#downloadResume').on('click', async () => {
        const resumeContent = $('#resumeWrapperModal');

        // Show the modal or make sure the content is visible (example code, actual implementation may vary)
        resumeContent.show();


        // Combine the custom styles and resume content
        const contentWithStyles = resumeContent[0].outerHTML;

        // Generate and save the PDF with specified A4 size
        await html2pdf().set({
            margin: 0, // Adjust margins as needed
            filename: 'resume.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' } // Set A4 size
        }).from(contentWithStyles).save();
    });
})