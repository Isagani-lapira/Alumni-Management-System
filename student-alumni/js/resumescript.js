$(document).ready(function () {

    let pageNo = 0
    let haveResumeNoWorkExp = false;
    let resumeIDVal = ""
    let isCreate = true;
    $('#editResumeBtn').on('click', function () {
        haveResume()
            .then((response) => {
                if (response) { //have resume
                    // display all the data for page 1
                    getResumeData(true)
                    isCreate = false;
                }
                else {
                    $('#objectiveInput').on('input', function () {
                        let objectiveVal = $(this).val()
                        if (objectiveVal !== "") {
                            $('#resumeBtnUpdate')
                                .addClass('bg-green-400 hover:bg-green-500')
                                .removeClass('bg-green-300')
                                .attr('disabled', false)
                        }
                    })

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
            if (pageNo === 4) {
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

            let lastPage = pageNo - 1
            if (lastPage == 0) getAcademicData()
            else if (lastPage == 1) getWorkExperience()
            else if (lastPage == 2) getSkills()
            else if (lastPage == 3) getReferences()

        }

        if (pageNo == 0) $('#resumeBtnPrev').addClass('hidden')
        $('#resumeBtnPrev').removeClass('hidden') // display the previous to be use 
    })


    let educationBGData = [];
    let workExpData = [];
    let referenceData = [];
    let skillData = [];
    function getAcademicData() {
        const level = ['primary education', 'secondary education', 'tertiary education'];
        let index = 0;
        $('.education').each(function () {
            let schoolName = $(this).find('input').val();
            let startYr = $(this).find('select:eq(0)').val();
            let endYr = $(this).find('select:eq(1)').val();
            let yearDuration = startYr + '-' + endYr;

            let collection = {
                level: level[index],
                school: schoolName,
                year: yearDuration
            }

            educationBGData.push(collection)
            index++
        })

    }

    // get the data collected in work experience section
    function getWorkExperience() {
        $('.workExpPage .experience').each(function () {
            const workTitle = $(this).find('input:eq(0)').val()
            const workDescript = $(this).find('input:eq(1)').val()
            const companyName = $(this).find('input:eq(2)').val()
            const startYr = $(this).find('select:eq(0)').val()
            const endYr = $(this).find('select:eq(1)').val()
            const yearDuration = startYr + '-' + endYr

            const collection = {
                workTitle: workTitle,
                workDescript: workDescript,
                companyName: companyName,
                yearDuration: yearDuration,
            }
            workExpData.push(collection)
        })
    }

    function getSkills() {
        $('.skillData').each(function () {
            let value = $(this).find('input').val();
            skillData.push(value)
        })
    }

    function getReferences() {
        $('.referenceData').each(function () {
            const fullname = $(this).find('input:eq(0)').val()
            const jobTitle = $(this).find('input:eq(1)').val()
            const contactNo = $(this).find('input:eq(2)').val()
            const emailAdd = $(this).find('input:eq(3)').val()

            const collection = {
                fullname: fullname,
                jobTitle: jobTitle,
                contactNo: contactNo,
                emailAdd: emailAdd
            }

            referenceData.push(collection)
        })
    }

    $('#resumeBtnUpdate').on('click', function () {
        // do insertion if there's no value yet
        if (isCreate) {
            processInsertionData(educationBGData, workExpData, referenceData, skillData)
        }
        // haveresume but no work experience, add the newly added work experience
        if (haveResumeNoWorkExp) insertWorkExperienceData(resumeIDVal, workExpData)


        // show successful modal and hide after 3 seconds
        $('#successModal').removeClass('hidden')
        $('#editResumeModal').addClass('hidden')
        setTimeout(() => {
            $('#successModal').addClass('hidden')
        }, 3000)

    })


    function processInsertionData(educationalData, workExpData, referenceData, skillData) {
        // data of personal information
        const firstname = $('#firstnameEdit').val()
        const lastname = $('#lastnameEdit').val()
        const contactNo = $('#contactNoEdit').val()
        const address = $('#addressEdit').val()
        const emailAdd = $('#emailAddEdit').val()
        const objective = $('#objectiveInput').val()

        // data to be send on server side
        const action = "insertData";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('firstname', firstname)
        formData.append('lastname', lastname)
        formData.append('contactNo', contactNo)
        formData.append('address', address)
        formData.append('emailAdd', emailAdd)
        formData.append('objective', objective)
        formData.append('educationalData', JSON.stringify(educationalData))
        formData.append('skillData', JSON.stringify(skillData))
        formData.append('workExpData', JSON.stringify(workExpData))
        formData.append('referenceData', JSON.stringify(referenceData))

        $.ajax({
            url: '../PHP_process/resume.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        })
    }

    function insertWorkExperienceData(resumeID, workArray) {
        const action = "addWorkExp";
        const formData = new FormData();
        formData.append('action', action)
        formData.append('resumeID', resumeID)
        formData.append('workArray', JSON.stringify(workArray));

        $.ajax({
            url: '../PHP_process/resume.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: response => { console.log(response) },
            error: error => { console.log(error) }
        })
    }

    // go back to previous page
    $('#resumeBtnPrev').on('click', function () {
        pageNo--
        // go back to  previous page
        if (pageNo >= 0) {
            $('#pageNo' + (pageNo + 1)).addClass('hidden')
            $('#pageNo' + pageNo).removeClass('hidden')
            removeCurrentDataCollect(pageNo)
            $('#resumeBtnUpdate').addClass('hidden')
            $('#resumeBtnNext').removeClass('hidden')
        }

        // hide the prev button if the page is in the first page
        if (pageNo == 0) $('#resumeBtnPrev').addClass('hidden')
    })

    function removeCurrentDataCollect(pageNo) {
        let lastNextPage = pageNo + 1
        if (lastNextPage == 3) referenceData = []
        else if (lastNextPage == 2) skillData = []
        else if (lastNextPage == 1) educationBGData = []
    }

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

    function updateResumeDetails(actionPerform, table, column, conditionCol, newVal, conditionColVal, resumeID) {
        const action = actionPerform;
        const formData = new FormData();
        formData.append('action', action);
        formData.append('table', table);
        formData.append('column', column);
        formData.append('newVal', newVal);
        formData.append('resumeID', resumeID);
        formData.append('conditionCol', conditionCol);
        formData.append('conditionColVal', conditionColVal);

        $.ajax({
            url: '../PHP_process/resume.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: response => { console.log(response) },
            error: error => { console.log(error) },
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

        $('#primaryLvl').empty();
        $('#secondaryLvl').empty();
        $('#tertiaryLvl').empty();
        $('#workExpList').empty();
        $('#skillWrapper').empty();
        $('#referenceContainer').empty();
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
                        const resumeID = response.resumeID
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
                            addResumeDataToEditing(resumeID, objective, skills, education, workExp, references)
                        else
                            setResumeDetails(objective, fullname, contactNo, address, emailAdd, skills, education, workExp, references);
                    }

                }
            },
            error: error => { console.log(error) }
        })
    }

    function addResumeDataToEditing(resumeID, objective, skills, education, workExp, references) {

        const action = "updateResume";
        const workTbl = "work_exp"
        const educationTbl = "education"
        const skillTbl = "resume_skill"
        const referencesTbl = "reference_resume"
        const resumeTbl = "resume"

        // set up the data for school details
        let length = education.educationLevel.length
        for (let i = length - 1; i >= 0; i--) {
            let schoolName = education.degree[i];
            let educationYr = education.year[i].split('-');
            let startedYr = educationYr[0];
            let endYr = educationYr[1];

            //Update value of input field    
            $('#degree' + i).val(schoolName).on('change', function () {
                const newVal = $(this).val();
                const degree = "degree";
                const level = $(this).data('educationlevel');
                const conditionalCol = 'education_level';
                updateResumeDetails(action, educationTbl, degree, conditionalCol, newVal, level, resumeID);
            });

            $('#startYr' + i).val(startedYr).on('change', function () {
                const newVal = $(this).val() + '-' + endYr;
                const year = "year";
                const level = $(this).data('educationlevel');
                const conditionalCol = 'education_level';
                updateResumeDetails(action, educationTbl, year, conditionalCol, newVal, level, resumeID);
            });

            $('#endYr' + i).val(endYr).on('change', function () {
                const newVal = startedYr + '-' + $(this).val();
                const year = "year";
                const level = $(this).data('educationlevel');
                const conditionalCol = 'education_level';
                updateResumeDetails(action, educationTbl, year, conditionalCol, newVal, level, resumeID);

            });
        }

        // add work experience
        let workLength = (workExp != null) ? workExp.companyName.length : 0;
        if (workLength > 0) {
            for (let i = 0; i < workLength; i++) {
                let workID = workExp.workID[i];
                let jobTitle = workExp.jobTitle[i];
                let workDescript = workExp.workDescript[i];
                let companyName = workExp.companyName[i];
                let years = workExp.year[i].split('-');
                let startYr = years[0];
                let endYr = years[1];

                // work title
                $('#workTitle' + i).val(jobTitle)
                    .on('change', function () {
                        const newVal = $(this).val()
                        const job_title = "job_title";
                        let conditionColVal = jobTitle;

                        updateResumeDetails(action, workTbl, job_title, job_title, newVal, conditionColVal, resumeID);
                    })

                // work description
                $('#workDescript' + i).val(workDescript)
                    .on('change', function () {
                        const newVal = $(this).val()
                        const description = "work_description";
                        let conditionColVal = workDescript;

                        const conditionalCol = 'work_description';
                        updateResumeDetails(action, workTbl, description, conditionalCol, newVal, conditionColVal, resumeID);
                    })

                // company name
                $('#workCompanyName' + i).val(companyName)
                    .on('change', function () {
                        const newVal = $(this).val()
                        const companyNameCol = "companyName";
                        let conditionColVal = companyName;

                        updateResumeDetails(action, workTbl, companyNameCol, companyNameCol, newVal, conditionColVal, resumeID);
                    })

                // working start
                $('#workStartYr' + i).val(startYr)
                    .on('change', function () {
                        const newVal = $(this).val() + '-' + endYr
                        const year = "year";
                        const conditionalCol = 'workID';

                        updateResumeDetails(action, workTbl, year, conditionalCol, newVal, workID, resumeID);
                    })

                // working end
                $('#workEndYr' + i).val(endYr)
                    .on('change', function () {
                        const newVal = startYr + '-' + $(this).val()
                        const year = "year";
                        const conditionalCol = 'workID';
                        updateResumeDetails(action, workTbl, year, conditionalCol, newVal, workID, resumeID);
                    })
            }
        } else {
            haveResumeNoWorkExp = true
            resumeIDVal = resumeID;
        }

        insertNewWork(resumeID)


        // add skills
        let skillLength = skills.length;
        for (let i = 0; i < skillLength; i++) {
            $('#skill' + i).val(skills[i])
                .on('change', function () {
                    const newVal = $(this).val()
                    const skillCol = "skill";
                    const conditionalCol = 'skill';
                    const conditionColVal = skills[i];
                    updateResumeDetails(action, skillTbl, skillCol, conditionalCol, newVal, conditionColVal, resumeID);
                })
        }

        insertNewSkill(resumeID)

        // add references
        let refLength = 3
        for (let i = 0; i < refLength; i++) {
            let refID = references.refID[i];
            let fullname = references.fullname[i];
            let jobTitle = references.jobTitle[i];
            let contactNo = references.contactNo[i];
            let emailAdd = references.emailAdd[i];


            $('#refFN' + i).val(fullname)
                .on('change', function () {
                    const newVal = $(this).val()
                    const fullnameCol = "reference_name";
                    const conditionalCol = 'referenceID';
                    updateResumeDetails(action, referencesTbl, fullnameCol, conditionalCol, newVal, refID, resumeID);

                })

            $('#refJobTitle' + i).val(jobTitle)
                .on('change', function () {
                    const newVal = $(this).val()
                    const job_titleCol = "job_title";
                    const conditionalCol = 'referenceID';

                    updateResumeDetails(action, referencesTbl, job_titleCol, conditionalCol, newVal, refID, resumeID);
                })
            $('#refContactNo' + i).val(contactNo)
                .on('change', function () {
                    const newVal = $(this).val()
                    const contactNoCol = "contactNo";
                    const conditionalCol = 'referenceID';

                    updateResumeDetails(action, referencesTbl, contactNoCol, conditionalCol, newVal, refID, resumeID);
                })
            $('#refEmailAdd' + i).val(emailAdd)
                .on('change', function () {
                    const newVal = $(this).val()
                    const emailAddCol = "emailAddress";
                    const conditionalCol = 'referenceID';

                    updateResumeDetails(action, referencesTbl, emailAddCol, conditionalCol, newVal, refID, resumeID);
                })
        }

        // add value to summary
        $('#objectiveInput').val(objective)
            .on('change', function () {
                const newVal = $(this).val()
                const objectiveCol = "objective";
                const conditionalCol = 'resumeID';
                updateResumeDetails(action, resumeTbl, objectiveCol, conditionalCol, newVal, resumeID, resumeID);
            })


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


    function insertNewWork(resumeID) {
        // insert new work
        $('.job-title').each(function () {
            // elements
            const element = $(this)
            let companyNameInput = element.siblings('.company-name');
            let responsibilityInput = element.siblings('.responsibility');
            const container = element.closest('.experience');

            // data to retrieve
            let companyName = ""
            let responsibility = "";
            let yearInput = "";
            let value = element.val()


            // add listener only to those with no work experience field
            if (value === '') {
                let startYearSelect = container.find('.yearSelection:first');
                let endYearSelect = container.find('.yearSelection:last');

                // job title
                element.on('change', function () {
                    value = $(this).val()
                    processInsertion(resumeID, value, companyName, responsibility, yearInput)
                })

                // company name
                companyNameInput.on('change', function () {
                    companyName = $(this).val()
                    processInsertion(resumeID, value, companyName, responsibility, yearInput)
                })

                // responsibility
                responsibilityInput.on('change', function () {
                    responsibility = $(this).val();
                    processInsertion(resumeID, value, companyName, responsibility, yearInput)
                })

                // start year
                startYearSelect.on('change', function () {
                    yearInput = $(this).val() + '-' + endYearSelect.val()
                    if (endYearSelect.val() !== null)
                        processInsertion(resumeID, value, companyName, responsibility, yearInput)
                })

                // end year
                endYearSelect.on('change', function () {
                    yearInput = $(this).val() + '-' + startYearSelect.val()
                    if (startYearSelect.val() !== null)
                        processInsertion(resumeID, value, companyName, responsibility, yearInput)
                })
            }


        })
    }

    // adding new skill
    function insertNewSkill(resumeID) {
        $('.skillInput').each(function () {
            const element = $(this)
            const value = element.val();

            // add listener to every skill that is empty
            if (value === '') {
                element.on('change', function () {
                    let skill = $(this).val();

                    if (skill !== "") processNewSkill(resumeID, skill) //add skill
                })
            }
        })
    }
    function processNewSkill(resumeID, skill) {
        const action = "individualAddSkill";
        const formData = new FormData();
        formData.append('action', action);
        formData.append('resumeID', resumeID);
        formData.append('skill', skill)

        $.ajax({
            url: '../PHP_process/resume.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        })
    }

    function processInsertion(resumeID, jobTitle, companyNameValue, responsibilityValue, year) {
        const action = "insertImgData";

        if (jobTitle !== '' && companyNameValue !== '' && responsibilityValue !== '' && year !== '') {
            const formData = new FormData();
            formData.append('action', action);
            formData.append('resumeID', resumeID)
            formData.append('jobTitle', jobTitle)
            formData.append('companyName', companyNameValue)
            formData.append('workDescript', responsibilityValue)
            formData.append('year', year)

            $.ajax({
                url: '../PHP_process/resume.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
            })
        }
    }
    function setResumeDetails(objective, fullname, contactNo, address, emailadd,
        skills, educations, workExp, references) {
        restartResume()
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
        $('#viewResumeBtn').click()
    }
})