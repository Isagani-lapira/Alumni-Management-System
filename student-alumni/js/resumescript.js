$(document).ready(function () {

    let pageNo = 0

    $('#editResumeBtn').on('click', function () {
        haveResume()
            .then((response) => {
                if (response) { //have resume
                    // display all the data for page 1
                    getResumeData(true)
                }
            })
    })

    $('#resumeBtnNext').on('click', function () {
        let inputFieldComplete = true
        // check first if all the field has value
        //skill required minimum of 3 skill
        if (pageNo == 2) {
            let countSkill = 0;

            $('.skillInput').each(function () {
                let val = $(this).val();
                if (val !== '') countSkill++
            })
            if (countSkill < 3) inputFieldComplete = false
        }
        else {
            $('#pageNo' + pageNo + ' .requiredValue').each(function () {
                let element = $(this)
                let value = element.val().trim();

                if (value === '') inputFieldComplete = false

            })
        }

        if (inputFieldComplete) {
            pageNo++
            $('#pageNo' + pageNo).removeClass('hidden') //show the next page
            $('#pageNo' + (pageNo - 1)).addClass('hidden') //hide the previous page

            // change button on the last page of edit resume
            if (pageNo == 4) {
                $('#resumeBtnUpdate').removeClass('hidden')
                $('#resumeBtnNext').addClass('hidden')

                // enable update button if the last page has value
                if ($('#objectiveInput').val().trim() !== '') {
                    $('#resumeBtnUpdate')
                        .addClass('bg-green-400 hover:bg-green-500')
                        .removeClass('bg-green-300')
                        .attr('disabled', false)
                }
            }
        }

        if (pageNo == 0) $('#resumeBtnPrev').addClass('hidden')
        $('#resumeBtnPrev').removeClass('hidden') // display the previous to be use 
    })


    // go back to previous page
    $('#resumeBtnPrev').on('click', function () {
        pageNo--
        if (pageNo >= 0) {
            $('#pageNo' + (pageNo + 1)).addClass('hidden')
            $('#pageNo' + pageNo).removeClass('hidden')
        }
        if (pageNo == 0) $('#resumeBtnPrev').addClass('hidden')
    })
    // check first if have resume
    function haveResume() {
        const action = "haveResume"
        const formData = new FormData();
        formData.append('action', action)

        return new Promise((resolve, reject) => {
            $.ajax({
                url: '../PHP_process/resume.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: response => { resolve(response) },
                error: error => { reject(error) },
            })
        })

    }


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
        formData.append('references', JSON.stringify(referenceData));

        $.ajax({
            url: '../PHP_process/resume.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                console.log(response)
                if (response == "Successful") {
                    //display the successful modal
                    $('#editResumeModal').addClass('hidden')
                    $('#successModal').removeClass('hidden')
                    //hide the success modal
                    setTimeout(function () {
                        $('#successModal').addClass('hidden')
                    }, 7000)
                } else { console.log("dito") }
            },
            error: error => { console.log(error) }
        })
    }



    $('.closeEditorResume').on('click', restartResume)
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

    function getResumeData(isEditting = false) {
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
                    let length = response.fullname.length
                    // check if there's a value to be display
                    if (length > 0) {
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

                        if (isEditting)
                            addResumeDataToEditing(objective, skills, education, workExp, references)
                        else
                            setResumeDetails(objective, fullname, contactNo, address, emailAdd, skills, education, workExp, references);
                    }

                }
            },
            error: error => { console.log(error) }
        })
    }

    function addResumeDataToEditing(objective, skills, education, workExp, references) {
        // set up the data for school details
        let length = education.educationLevel.length
        for (let i = 0; i < length; i++) {
            let schoolName = education.degree[i];
            let educationYr = education.year[i].split('-')
            let startedYr = educationYr[0]
            let endYr = educationYr[1];

            // update value of input field
            $('#degree' + i).val(schoolName)
            $('#startYr' + i).val(startedYr)
            $('#endYr' + i).val(endYr)
        }


        // add work experience
        let workLength = workExp.companyName.length;
        for (let i = 0; i < workLength; i++) {
            let jobTitle = workExp.jobTitle[i];
            let workDescript = workExp.workDescript[i];
            let companyName = workExp.companyName[i];
            let years = workExp.year[i].split('-');
            let startYr = years[0];
            let endYr = years[1];

            $('#workTitle' + i).val(jobTitle)
            $('#workDescript' + i).val(workDescript)
            $('#workCompanyName' + i).val(companyName)
            $('#workStartYr' + i).val(startYr)
            $('#workEndYr' + i).val(endYr)
        }


        // add skills
        let skillLength = skills.length;
        for (let i = 0; i < skillLength; i++) {
            $('#skill' + i).val(skills[i])
        }

        // add references
        let refLength = 3
        for (let i = 0; i < refLength; i++) {
            let fullname = references.fullname[i];
            let jobTitle = references.jobTitle[i];
            let emailAdd = references.fullname[i];
            let contactNo = references.fullname[i];


            $('#refFN' + i).val(fullname)
            $('#refJobTitle' + i).val(jobTitle)
            $('#refContactNo' + i).val(emailAdd)
            $('#refEmailAdd' + i).val(contactNo)
        }

        // add value to summary
        $('#objectiveInput').val(objective)


    }

    // set all the select with max of todays year and minimum of 1995
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const lowestYear = 1995
    $('.yearSelection ').each(function () {
        for (let i = currentYear; i > lowestYear; i--) {
            const option = $('<option>').val(i).text(i);
            $(this).append(option)
        }
    })

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


    $('#refFN, #refJobTitle,#refContactNo,#refEmailAdd,#refFNSecond, #refJobTitleSecond, #refContactSecond, #refEmailThird')
        .on('input', function () {
            let isRefComplete = false;
            //check if all input have value
            let referencesInput = $('.referencesInput')
            referencesInput.each(function () {
                let value = $(this).val();
                if (value == "") isRefComplete = false
                else isRefComplete = true
            })

            if (isRefComplete) enabledTheNext()
            else disabledTheNext()

        })

    $('#closeViewResume').on('click', function () {
        $('#viewResumeModal').addClass('hidden')
    })

    const currentURL = window.location.href;
    const url = new URL(currentURL);
    let resumeOpen = url.searchParams.get('resumeOpen');

    if (resumeOpen === 'true') {
        console.log(resumeOpen)
        $('#viewResumeBtn').click()
    }
})