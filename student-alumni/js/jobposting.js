const imgFormat = "data:image/jpeg;base64,";
$(document).ready(function () {
    //post a job
    $('#jobPostingForm').on('submit', function (e) {
        e.preventDefault();

        const action = {
            action: 'createjobuser',
        }
        const formData = new FormData(e.target)
        formData.append('action', JSON.stringify(action))
        formData.append('author', accUsername)

        //process ajax insertion
        $.ajax({
            url: '../PHP_process/jobTable.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                if (response == 'Successful') {
                    $('#successJobModal').removeClass('hidden')

                    //close modal in 4seconds
                    setTimeout(() => {
                        $('#successJobModal').addClass('hidden')
                        $('#createJobModal').addClass('hidden')
                    }, 4000);

                    $('#jobPostingForm')[0].reset() //reset the data in the form
                }
            },
            error: error => { console.log(error) }
        })
    })

    $('#createJobPost').on('click', function () {
        $('#createJobModal').removeClass('hidden')
    })

    // close create job modal
    $('#createJobModal').on('click', function (e) {
        target = e.target
        modal = $('#jobContainer')

        if (!modal.is(target) && modal.has(target).length == 0) {
            $('#createJobModal').addClass('hidden')
        }
    })

    //close job
    $('#cancelJobPosting').on('click', function () {
        $('#createJobModal').addClass('hidden')
    })

    let offsetUserJob = 0;
    let lengthChecker = 0;
    // current user job post
    $('#verif-btn').on('click', function () {
        offsetUserJob = 0;
        $('#jobRepo').empty()
        retrieveUserPost()
    })

    function retrieveUserPost() {
        //process retrieval
        const action = { action: 'currentUserJobPost' };

        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('offset', offsetUserJob)
        $.ajax({
            url: '../PHP_process/jobTable.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            success: response => {
                if (response.result === 'Success') {
                    const length = response.jobTitle.length;
                    //data that has been retrieved
                    for (let i = 0; i < length; i++) {
                        const careerID = response.careerID[i];
                        const jobTitle = response.jobTitle[i];
                        const skills = response.skills[i];
                        const status = response.status[i];
                        const companyName = response.companyName[i];
                        const jobDescript = response.jobDescript[i];
                        const jobQuali = response.jobQuali[i];
                        const location = response.location[i];
                        const applicantcount = response.applicantCount[i];

                        displayJobRepo(careerID, jobTitle, skills, status, companyName,
                            jobDescript, jobQuali, location, applicantcount);
                    }

                    offsetUserJob += length
                    lengthChecker = length;
                }
            },
            error: error => { console.log(error) }
        })
    }
    //mark up for job repository in verified post
    function displayJobRepo(careerID, jobTitle, skills, status, companyName,
        jobDescript, jobQuali, location, applicantcount) {

        //sets of color
        const colorBG = (status === 'verified') ? 'accent' : 'gray-400'

        const wrapper = $('<div>')
            .addClass('rounded-md max-w-sm flex flex-col center-shadow')

        const headerPart = $('<div>')
            .addClass('h-full flex flex-col rounded-t-md p-3 justify-between bg-' + colorBG)

        const jobTitleElement = $('<h1>')
            .addClass('text-lg text-white font-bold my-2')
            .text(jobTitle);

        const list = $('<div>')
            .addClass('flex flex-wrap gap-1 text-xs text-white italic items-center')

        //retrieve all the skill and display in on a div to be included on the container
        skills.forEach(skill => {
            let bulletIcon = '<iconify-icon icon="fluent-mdl2:radio-bullet" style="color: #ffffff;"></iconify-icon>';
            let skillElement = $('<span>').html(skill)
            list.append(bulletIcon, skillElement)
        })

        const footer = $('<div>')
            .addClass('bg-gray-200 rounded-b-md p-3 flex flex-wrap justify-between items-center text-xs text-gray-400')

        const applicant = $('<span>')
            .addClass('flex items-center gap-2 text-' + colorBG)
            .html(
                '<iconify-icon icon="uiw:user" width="14" height="14"></iconify-icon>' +
                '<span>Applicant: <span class="font-bold">' + applicantcount + '</span></span>'
            )

        const verifiedElement = $('<span>')
            .addClass('flex items-center gap-2')
            .html(
                '<iconify-icon icon="ri:verified-badge-line" style="color: #868e96;" width="14" height="14"></iconify-icon>' +
                status
            )

        if (status == 'unverified') {
            status = 'Not yet verified'
        } else {
            verifiedElement.addClass('text-blue-400')
                .html('<iconify-icon icon="ri:verified-badge-fill" style="color: #60a5fa;"></iconify-icon>' + status)
        }
        const leftSide = $('<div>').append(applicant, verifiedElement)
        const proceedBtn = $('<button>')
            .html('<iconify-icon icon="maki:arrow" style="color: #868e96;" width="24" height="24"></iconify-icon>')
            .on('click', function () {
                $("#skillSets").empty(); //remove the recent added skill and requirements
                $("#viewJob").removeClass("hidden");

                //set value to the view modal
                $("#viewJob").removeClass("hidden");
                $("#viewJobColText").text(jobTitle);
                $("#viewJobColCompany").text(companyName);
                $("#jobOverview").text(jobDescript);
                $("#jobQualification").text(jobQuali);
                $("#statusJob").text(status);
                $("#jobApplicant").addClass('text-' + colorBG).text('Applicant: ' + applicantcount);

                $('#aplicantListBtn').addClass('cursor-text') //default
                if (parseInt(applicantcount) > 0) {
                    $('#aplicantListBtn').removeClass('cursor-text')
                        .on('click', function () {
                            //open the list
                            displayApplicant(careerID)
                        })
                }

                $('.headerJob').addClass('bg-' + colorBG)
                //retrieve the skills
                skills.forEach((skill) => {
                    //create a span and append it in the div
                    let spSkill = $("<span>").html("&#x2022; " + skill);
                    $("#skillSets").append(spSkill);
                });

                $('#locationJobModal').text(location)

            })

        footer.append(leftSide, proceedBtn)
        headerPart.append(jobTitleElement, list);
        wrapper.append(headerPart, footer);

        $('#jobRepo').append(wrapper)
    }

    //allows modal to be close when Click else where
    $("#viewJob").on("click", function (e) {
        const target = e.target
        const modal = $('#viewingJobModal')

        if (!modal.is(target) && modal.has(target).length == 0) {
            $('#viewJob').addClass('hidden')
        }

    });

    $('#jobRepo').on('scroll', function () {
        const containerHeight = $(this).height();
        const contentHeight = $(this)[0].scrollHeight;
        const scrollOffset = $(this).scrollTop();
        const threshold = 50; // Define the threshold in pixels

        //once the bottom ends, it will reach another sets of data (post)
        if (scrollOffset + containerHeight + threshold >= contentHeight)
            retrieveUserPost()//get another set of post

    })


    // retrieve details of applicant
    function displayApplicant(careerID) {
        const action = { action: 'applicantDetails' }
        const formData = new FormData();
        formData.append('action', JSON.stringify(action));
        formData.append('careerID', careerID);

        // process retrieval of applicant details
        $.ajax({
            url: '../PHP_process/jobTable.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                if (response.response == "Success") {
                    $('#listApplicantContainer').empty()
                    $('#listOfApplicantModal').removeClass('hidden')
                    const length = response.fullname.length;

                    // mark up the data retrieved
                    for (let i = 0; i < length; i++) {
                        let fullname = response.fullname[i];
                        let resumeID = response.resumeID[i];

                        let wrapper = $('<div>')
                            .addClass('justify-between flex items-center')

                        let fullnameElement = $('<p>')
                            .addClass('italic text-gray-500')
                            .text(fullname)

                        let viewResume = $('<button>')
                            .addClass('py-2 px-4 rounded-lg bg-accent text-white font-bold text-xs hover:bg-darkAccent')
                            .text('Resume')
                            .on('click', function () {
                                $('#listOfApplicantModal').addClass('hidden')
                                $('#viewJob').addClass('hidden')
                                displayApplicantResume(resumeID)
                            })

                        wrapper.append(fullnameElement, viewResume)
                        $('#listApplicantContainer').append(wrapper)
                    }
                }
            },
            error: error => { console.log(error) }
        })
    }


    $('.modaListApplicant button').on('click', function () {
        $('#listOfApplicantModal').addClass('hidden')
    })

    function displayApplicantResume(resumeID) {
        const action = "showApplicantResume";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('resumeID', resumeID)
        $.ajax({
            url: '../PHP_process/resume.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: response => {
                if (response.response == 'Success') {
                    $('#viewResumeModal').removeClass('hidden')

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

                    // set up the details of the resume
                    setResumeDetails(objective, fullname, contactNo, address, emailAdd, skills, education, workExp, references)
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

    $('#closeViewResume').on('click', function () {
        $('#viewResumeModal').addClass('hidden')
    })

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
            margin: 4,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' } // Set A4 size
        }).from(contentWithStyles).save();
    });
})
