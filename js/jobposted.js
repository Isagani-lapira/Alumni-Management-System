$(document).ready(function () {
    let offsetUserJob = 0
    let lengthChecker = 0;

    //admin job list post
    $("#jobMyPost").on("click", function () {
        offsetUserJob = 0;
        retrieveUserPost()
    });

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
            error: () => {
                lengthChecker = 0
            }
        })
    }


    //mark up for job repository in verified post
    function displayJobRepo(careerID, jobTitle, skills, status, companyName,
        jobDescript, jobQuali, location, applicantcount) {

        //sets of color
        const colorBG = 'gray-400'

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
            .addClass('flex items-center gap-2 text-blue-400')
            .html(
                '<iconify-icon icon="ri:verified-badge-line" style="color: #60a5fa;" width="14" height="14"></iconify-icon>' +
                status
            )

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
                $("#jobApplicant").text('Applicant: ' + applicantcount);
                console.log(applicantcount)
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

        $('#adminJobPostCont').append(wrapper)
    }


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
    $('#adminJobPost').on('scroll', function () {
        const containerHeight = $(this).height();
        const contentHeight = $(this)[0].scrollHeight;
        const scrollOffset = $(this).scrollTop();
        const threshold = 50; // Define the threshold in pixels

        //once the bottom ends, it will reach another sets of data (post)
        if (scrollOffset + containerHeight + threshold >= contentHeight && lengthChecker == 10)
            retrieveUserPost()//get another set of post

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


    //job table listing
    // job table
    $('#jobTable').DataTable({
        "paging": true,
        "ordering": true,
        "info": false,
        "lengthChange": false,
        "searching": true,
        "pageLength": 5
    })
    let table = $('#jobTable').DataTable();
    $('#jobTable').removeClass('dataTable').addClass('rounded-lg')

    // Attach a change event handler to the authorFilter select element
    $('#authorFilter').on('change', function () {
        let selectedVal = $(this).val();

        // Apply the filter based on the selected author type
        if (selectedVal === 'all') {
            // Clear the filter and show all rows
            table.search('').draw();
        } else if (selectedVal === 'admin') {
            // Apply a filter to show only rows with the selected author type
            table.column(2).search(selectedVal).draw();
        } else {
            //show only the alumni
            table.column(2).search(`^(?!(${selectedVal}|University Admin|College Admin)$).*`, true, false).draw();
        }
    });

    let offset = 0;
    $("#jobLI").on("click", function () {
        restartTable();
    });


    function jobList(offset) {
        let jobAction = {
            action: "read", // read the data
        };
        const jobData = new FormData();
        jobData.append("action", JSON.stringify(jobAction));
        jobData.append("offset", offset);

        $.ajax({
            url: "../PHP_process/jobTable.php",
            type: "POST",
            data: jobData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                // check if there's a value
                if (response.result === "Success") {
                    let data = response;
                    let jobTitles = data.jobTitle;

                    for (let i = 0; i < jobTitles.length; i++) {
                        // fetch all the data
                        let jobTitle = jobTitles[i];
                        let author = data.author[i];
                        let companyName = data.companyName[i];
                        let jobDescript = data.jobDescript[i];
                        let jobQuali = data.jobQuali[i];
                        let college = data.colCode[i];
                        let datePosted = data.date_posted[i];
                        let companyLogo = data.companyLogo[i];
                        let skills = data.skills[i];
                        let logo = imgFormat + companyLogo;

                        // Create row
                        let row = [
                            `<img class="w-16 h-16 mx-auto rounded-full" src="${logo}" />`,
                            jobTitle,
                            author,
                            college,
                            datePosted,
                            `<button class="py-2 px-4 bg-postButton rounded-lg text-white hover:bg-postHoverButton view-button" 
                            data-job-title="${jobTitle}" 
                            data-author="${author}" 
                            data-company-name="${companyName}" 
                            data-job-descript="${jobDescript}" 
                            data-job-quali="${jobQuali}" 
                            data-date-posted="${datePosted}" 
                            data-company-logo="${companyLogo}" 
                            data-skills="${skills}">View</button>`
                        ];


                        // Add the new row to the DataTable
                        table.row.add(row);
                    }

                    // Draw the DataTable to update the UI
                    table.draw();

                    // Check if there is more data to load
                    if (jobTitles.length > 0) {
                        jobList(offset + jobTitles.length);
                    }
                }
            },
        });
    }

    function displayJobDetails(logo, jobTitle, author, companyName, datePosted, jobDescript, jobQuali, skills) {
        $("#skillSets").empty();

        //set value to the view modal
        $("#viewJob").removeClass("hidden");
        $("#jobCompanyLogo").attr("src", logo);
        $("#viewJobColText").text(jobTitle);
        $("#viewJobAuthor").text(author);
        $("#viewJobColCompany").text(companyName);
        $("#viewPostedDate").text(datePosted);
        $("#jobOverview").text(jobDescript);
        $("#jobQualification").text(jobQuali);

        //retrieve the skills
        skills.forEach((skill) => {
            // Create a span and append it in the div
            let spSkill = $("<span>").html("&#x2022; " + skill);
            $("#skillSets").append(spSkill);
        });

    }

    // Add a click event listener to the "View" button
    $("#jobTable").on("click", ".view-button", function () {
        // Get the data attributes from the clicked button
        let jobTitle = $(this).data("job-title");
        let author = $(this).data("author");
        let companyName = $(this).data("company-name");
        let jobDescript = $(this).data("job-descript");
        let jobQuali = $(this).data("job-quali");
        let datePosted = $(this).data("date-posted");
        let companyLogo = $(this).data("company-logo");
        let skills = $(this).data("skills");
        skills = skills.split(',');

        // Call the displayJobDetails function with the extracted data
        displayJobDetails(companyLogo, jobTitle, author, companyName, datePosted, jobDescript, jobQuali, skills);
    });


    //job form
    const decodedPersonID = decodeURIComponent($("#accPersonID").val());
    $("#jobForm").on("submit", function (e) {
        e.preventDefault();

        var skills = skillArray();

        //check first if all input field are complete
        if (jobField()) {
            var data = new FormData(this);
            var action = {
                action: "create",
            };

            //data to be sent in the php
            data.append("action", JSON.stringify(action));
            data.append("author", "University Admin");
            data.append("skills", JSON.stringify(skills));
            data.append("personID", decodedPersonID);

            $.ajax({
                url: "../PHP_process/jobTable.php",
                type: "Post",
                data: data,
                processData: false,
                contentType: false,
                success: function (success) {
                    $("#promptMessage").removeClass("hidden");
                    $("#insertionMsg").html(success);
                    restartTable(); //refresh the table
                },
                error: function (error) {
                    $("#promptMessage").removeClass("hidden");
                    $("#insertionMsg").html(error);
                },
            });
        }
    });


    //retrieve all the skills have been written
    function skillArray() {
        var skills = [];
        $(".skillInput").each(function () {
            skills.push($(this).val());
        });

        return skills;
    }

    //check if the forms in the job field is all answered
    function jobField() {
        var allFieldCompleted = true;
        $(".jobField").each(function () {
            let currentElement = $(this)
            if (!currentElement.val()) {
                currentElement.removeClass("border-gray-400").addClass("border-accent");
                allFieldCompleted = false;
            } else currentElement.addClass("border-grayish").removeClass("border-accent");
        });
        return allFieldCompleted;
    }

    function restartTable() {
        offset = 0;
        table.clear().draw();
        jobList(offset);
    }

    //go back button in job tab
    $("#goBack").click(function () {
        $("#promptMessage").addClass("hidden");
        $("#jobPosting").hide();
        $("#jobList").show();
        $(".jobPostingBack").hide();
        $("#jobForm")[0].reset(); //restart the form 
    });
})